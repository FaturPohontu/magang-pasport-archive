<?php

namespace App\Controllers;

use App\Libraries\OcrService;
use App\Libraries\GoogleSheetService; // Import Service Google Sheet

class HomeController extends BaseController
{
    public function index()
    {
        return view('passport/index', ['title' => 'Scan & Verifikasi Paspor']);
    }

    public function processOcr()
    {
        $file = $this->request->getFile('passport_image');

        if (!$file || !$file->isValid()) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'File gambar tidak valid atau tidak ditemukan.'
            ]);
        }

        $newName = $file->getRandomName();
        $file->move(WRITEPATH . 'uploads', $newName);
        $fullPath = WRITEPATH . 'uploads/' . $newName;

        $ocrService = new OcrService();
        $result = $ocrService->scanImage($fullPath);

        if (file_exists($fullPath)) {
            unlink($fullPath);
        }

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

    public function saveToSheet()
    {
        // 1. Tangkap data dari form verifikasi sesuai urutan kolom Spreadsheet
        $kodePermohonan  = $this->request->getPost('kode_permohonan');
        $namaBilling     = $this->request->getPost('nama_billing');
        $tglLahir        = $this->request->getPost('tgl_lahir');
        $jenisPermohonan = $this->request->getPost('jenis_permohonan');
        $dicetakTanggal  = $this->request->getPost('dicetak_tanggal');

        // Validasi input wajib
        if (empty($kodePermohonan) || empty($namaBilling)) {
            return redirect()->back()->with('error', 'Kode Permohonan dan Nama Billing wajib diisi!')->withInput();
        }

        // 2. Susun data menjadi array searah dengan urutan kolom Google Sheets[cite: 1]
        $rowArray = [
            $kodePermohonan,
            $namaBilling,
            $tglLahir,
            $jenisPermohonan,
            $dicetakTanggal
        ];

        // 3. Panggil GoogleSheetService
        $sheetService = new GoogleSheetService();
        $isSaved = $sheetService->appendRow($rowArray);

        if ($isSaved) {
            return redirect()->to('/scan')->with('success', 'Data resi permohonan berhasil disimpan ke Google Spreadsheet!');
        } else {
            return redirect()->back()->with('error', 'Gagal menyimpan data ke Google Spreadsheet. Periksa koneksi/credentials.')->withInput();
        }
    }
}