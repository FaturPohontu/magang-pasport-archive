<?php

namespace App\Controllers;

use App\Libraries\OcrService;

class HomeController extends BaseController
{
    // 1. Menampilkan halaman Scan & Input Data
    public function index()
    {
        $data = [
            'title' => 'Scan Resi Paspor'
        ];
        return view('passport/index', $data);
    }

    // 2. Menerima upload foto resi dan menjalankan OCR
    public function processOcr()
    {
        $file = $this->request->getFile('passport_image');

        // 1. Validasi File Unggahan
        if (!$file || !$file->isValid()) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'File gambar tidak valid atau tidak ditemukan.'
            ]);
        }

        // 2. Simpan sementara gambar di folder writable/uploads
        $newName = $file->getRandomName();
        $file->move(WRITEPATH . 'uploads', $newName);
        $fullPath = WRITEPATH . 'uploads/' . $newName;

        // 3. Jalankan OCR Service
        $ocrService = new OcrService();
        $result = $ocrService->scanImage($fullPath);

        // 4. Hapus berkas temporary setelah selesai dibaca
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }

        // 5. Kembalikan hasil ke Frontend/AJAX
        if ($result['status'] === true) {
            return $this->response->setJSON([
                'status' => 'success',
                'data'   => $result['data']
            ]);
        } else {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Gagal membaca gambar: ' . $result['error']
            ]);
        }
    }

    // 3. Menyimpan data hasil validasi/input manual ke Google Sheets
    public function saveToSheet()
    {
        $postData = [
            'kode_permohonan'  => $this->request->getPost('kode_permohonan'),
            'nama_billing'     => $this->request->getPost('nama_billing'),
            'tgl_lahir'        => $this->request->getPost('tgl_lahir'),
            'jenis_permohonan' => $this->request->getPost('jenis_permohonan'),
            'dicetak_tanggal'  => $this->request->getPost('dicetak_tanggal'),
        ];

        if (empty($postData['kode_permohonan']) || empty($postData['nama_billing'])) {
            return redirect()->back()->with('error', 'Kode Permohonan dan Nama Billing wajib diisi!')->withInput();
        }

        // TODO: Kirim $postData ke Google Sheet Service
        // GoogleSheetService::appendRow(array_values($postData));

        return redirect()->to('/scan')->with('success', 'Data resi permohonan berhasil disimpan ke Spreadsheet!');
    }
}