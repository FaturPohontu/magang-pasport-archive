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
        $postData = $this->request->getPost();

        // Validasi input
        if (empty($postData['nik_paspor']) || empty($postData['nama'])) {
            return redirect()->back()->with('error', 'Data paspor tidak lengkap.')->withInput();
        }

        // TODO: Panggil Google Sheets Service/Library di sini untuk append data
        // GoogleSheetService::appendRow($postData);

        return redirect()->to('/scan')->with('success', 'Data paspor berhasil disimpan ke Spreadsheet!');
    }
}