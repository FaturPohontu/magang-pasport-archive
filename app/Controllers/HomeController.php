<?php

namespace App\Controllers;

use App\Libraries\OcrService;
use App\Libraries\GoogleSheetService; // Import Service Google Sheet
use App\Models\ArsipPasporModel;
use Config\Database;

class HomeController extends BaseController
{
    public function index()
    {
        return view('passport/index', ['title' => 'Scan & Verifikasi Paspor']);
    }

    public function processBatch()
    {
        ini_set('max_execution_time', '120');

        $images = $this->request->getFileMultiple('passport_images');

        if (empty($images)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Tidak ada file gambar yang diunggah.'
            ]);
        }

        // Batasi maksimal 5 foto dalam 1 proses 
        if (count($images) > 5) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Maksimal 5 foto yang dapat diproses sekaligus.'
            ]);
        }

        $uploadPath = WRITEPATH . 'uploads/';

        if(!is_dir($uploadPath)) {
            mkdir($uploadPath, 0750, true);
        }

        $ocrService = new OcrService();
        $extractedData = [];

        foreach ($images as $imgFile) {

            if (!$imgFile || !$imgFile->isValid() || $imgFile->hasMoved()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message'=> 'File gambar tidak valid atau tidak ditemukan.'
                ]);
            }
            
            $newName = $imgFile->getRandomName();
            $filePath = $uploadPath . $newName;

            try {
                $imgFile->move($uploadPath, $newName);

                // Jalankan OCR dan parsing melalui OCRService
                $result = $ocrService->scanImage($filePath);

                if(empty($result['status']) || empty($result['data'])) {
                    log_message(
                        'error',
                        'OCR gagal untuk file: ' . ($result['error'] ?? 'Hasil OCR tidak tersedia.')
                    );

                    return $this->response->setJSON([
                        'status' => 'error',
                        'message' => 'Gagal membaca salah satu gambar. Silahkan periksa kualitas gambar dan coba lagi.'
                    ]);
                }

                $extractedData[] = [
                    'kode_permohonan'  => $result['data']['kode_permohonan'] ?? '',
                    'nama_billing'     => $result['data']['nama_billing'] ?? '',
                    'tgl_lahir'        => $result['data']['tgl_lahir'] ?? '',
                    'jenis_permohonan' => $result['data']['jenis_permohonan'] ?? '',
                    'dicetak_tanggal'  => $result['data']['dicetak_tanggal'] ?? ''
                ];

            } catch (\Throwable $e) {
                log_message('error', 'Kesalahan proses OCR:' . $e->getMessage());

                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Terjadi Kesalahan saat memproses gambar.'
                ]);
            } finally {
                // Hapus gambar sementara, baik OCR berhasil maupun gagal
                if (is_file($filePath)) {
                    unlink($filePath);
                }
            }
        }
    
            
        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $extractedData
        ]);
    }

    public function saveBatch()
    {
        // Pastikan pengguna sudah login.
        $userId = session()->get('user_id');

        if (!$userId) {
            return $this->response->setStatusCode(401)->setJSON([
                'status'  => 'error',
                'message' => 'Sesi login berakhir. Silakan login kembali.'
            ]);
        }

        // Ambil data JSON dari JavaScript.
        $payload = $this->request->getJSON(true);

        if (!is_array($payload) || !isset($payload['rows']) || !is_array($payload['rows'])) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => 'error',
                'message' => 'Format data yang dikirim tidak valid.'
            ]);
        }

        $rows = $payload['rows'];

        if (count($rows) < 1 || count($rows) > 5) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => 'error',
                'message' => 'Jumlah data harus antara 1 sampai 5.'
            ]);
        }

        $model = new ArsipPasporModel();
        $db = Database::connect();

        $preparedRows = [];

        // Fungsi untuk mengubah tanggal menjadi format MySQL Y-m-d.
        $normalizeDate = static function ($value) {
            $value = trim((string) $value);

            if ($value === '') {
                return false;
            }

            $formats = ['!Y-m-d', '!d/m/Y', '!d-m-Y'];

            foreach ($formats as $format) {
                $date = \DateTime::createFromFormat($format, $value);
                $errors = \DateTime::getLastErrors();

                if (
                    $date !== false &&
                    ($errors === false ||
                        ($errors['warning_count'] === 0 && $errors['error_count'] === 0))
                ) {
                    return $date->format('Y-m-d');
                }
            }

            return false;
        };

        // Validasi seluruh data sebelum menyimpannya.
        foreach ($rows as $index => $row) {
            if (!is_array($row)) {
                return $this->response->setStatusCode(422)->setJSON([
                    'status'  => 'error',
                    'message' => 'Format data pada baris ' . ($index + 1) . ' tidak valid.'
                ]);
            }

            $kode = trim((string) ($row['kode_permohonan'] ?? ''));
            $nama = trim((string) ($row['nama_billing'] ?? ''));
            $jenis = trim((string) ($row['jenis_permohonan'] ?? ''));

            $tglLahir = $normalizeDate($row['tgl_lahir'] ?? '');
            $tglCetak = $normalizeDate($row['dicetak_tanggal'] ?? '');

            if (
                $kode === '' ||
                $nama === '' ||
                $jenis === '' ||
                $tglLahir === false ||
                $tglCetak === false
            ) {
                return $this->response->setStatusCode(422)->setJSON([
                    'status'  => 'error',
                    'message' => 'Data pada baris ' . ($index + 1) .
                        ' belum lengkap atau format tanggal tidak valid.'
                ]);
            }

            if (
                strlen($kode) > 100 ||
                strlen($nama) > 150 ||
                strlen($jenis) > 50
            ) {
                return $this->response->setStatusCode(422)->setJSON([
                    'status'  => 'error',
                    'message' => 'Ada data yang melebihi batas panjang karakter pada baris ' .
                        ($index + 1) . '.'
                ]);
            }

            $preparedRows[] = [
                'kode_permohonan' => $kode,
                'nama_billing' => $nama,
                'tgl_lahir' => $tglLahir,
                'jenis_permohonan' => $jenis,
                'dicetak_tanggal' => $tglCetak,
                'created_by' => $userId,
                'sheets_synced' => 0,
                'sheets_synced_at' => null,
            ];
        }

        // =========================================
        // TAHAP 1: SIMPAN SEMUA DATA KE MYSQL
        // =========================================

        $db->transBegin();

        $savedRecords = [];

        foreach ($preparedRows as $row) {
            $insertId = $model->insert($row, true);

            if (!$insertId) {
                $db->transRollback();

                log_message(
                    'error',
                    'Gagal menyimpan data arsip ke MySQL: ' .
                    json_encode($model->errors())
                );

                return $this->response->setStatusCode(500)->setJSON([
                    'status'  => 'error',
                    'message' => 'Gagal menyimpan data ke database. Tidak ada data batch yang disimpan.'
                ]);
            }

            $savedRecords[] = [
                'id' => $insertId,
                'data' => $row
            ];
        }

        if ($db->transStatus() === false) {
            $db->transRollback();

            return $this->response->setStatusCode(500)->setJSON([
                'status'  => 'error',
                'message' => 'Transaksi database gagal.'
            ]);
        }

        $db->transCommit();

        // =========================================
        // TAHAP 2: SINKRONISASI KE GOOGLE SHEETS
        // =========================================

        $syncedCount = 0;
        $failedCount = 0;

        $sheetService = new \App\Libraries\GoogleSheetService();

        foreach ($savedRecords as $record) {
            $data = $record['data'];

            $rowArray = [
                $data['kode_permohonan'],
                $data['nama_billing'],
                $data['tgl_lahir'],
                $data['jenis_permohonan'],
                $data['dicetak_tanggal']
            ];

            try {
                $isSaved = $sheetService->appendRow($rowArray);

                if ($isSaved) {
                    $updated = $model->update($record['id'], [
                        'sheets_synced' => 1,
                        'sheets_synced_at' => date('Y-m-d H:i:s')
                    ]);

                    if ($updated) {
                        $syncedCount++;
                    } else {
                        $failedCount++;

                        log_message(
                            'error',
                            'Data arsip ID ' . $record['id'] .
                            ' Berhasil dikirim ke Sheets, tetapi status sinkronisasi gagal diperbarui.'
                        );
                    }
                } else {
                    $failedCount++;
                }
            } catch (\Throwable $e) {
                $failedCount++;

                log_message(
                    'error',
                    'Sinkronisasi Google Sheets gagal untuk arsip ID ' .
                    $record['id'] . ': ' . $e->getMessage()
                );
            }
        }

        // =========================================
        // TAHAP 3: KIRIM HASIL KE JAVASCRIPT
        // =========================================

        if ($failedCount === 0) {
            return $this->response->setJSON([
                'status' => 'success',
                'mysql_saved' => count($savedRecords),
                'sheets_synced' => $syncedCount,
                'message' => 'Semua data berhasil disimpan ke MySQL dan Google Sheets.'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'partial',
            'mysql_saved' => count($savedRecords),
            'sheets_synced' => $syncedCount,
            'sheets_failed' => $failedCount,
            'message' => count($savedRecords) .
                ' data berhasil disimpan ke MySQL. Namun, ' .
                $failedCount .
                ' data belum berhasil disinkronkan ke Google Sheets.'
        ]);
    }

}