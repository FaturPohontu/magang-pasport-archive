<?php

namespace App\Controllers;

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

        if (!$file || !$file->isValid()) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Gagal mengunggah berkas gambar.'
            ]);
        }

        // TODO: Eksekusi OCR Engine / Script Python / API OCR di sini
        // Contoh data dummy respon hasil scan OCR:
        $ocrData = [
            'nik_paspor'  => 'A12345678',
            'nama'        => 'JOHN DOE',
            'kewarganegaraan' => 'WNI', // WNI atau WNA
            'tgl_lahir'   => '1995-05-20',
        ];

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $ocrData
        ]);
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