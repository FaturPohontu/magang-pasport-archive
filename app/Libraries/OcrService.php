<?php

namespace App\Libraries;

use thiagoalessio\TesseractOCR\TesseractOCR;

class OcrService
{
    protected $executablePath;

    public function __construct()
    {
        // Sesuaikan path ini dengan lokasi tesseract.exe di sistem Anda (Windows)
        // Jika di Linux/Mac biasanya cukup 'tesseract'
        $this->executablePath = 'C:\\Program Files\\Tesseract-OCR\\tesseract.exe';
    }

    public function scanImage(string $filePath): array
    {
        try {
            // 1. Jalankan Tesseract pada berkas gambar
            $ocr = new TesseractOCR($filePath);
            
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $ocr->executable($this->executablePath);
            }

            // Anda bisa menambahkan ->lang('ind', 'eng') jika mengunduh paket bahasa
            $rawText = $ocr->run();

            // 2. Parsel/ekstrak data teks hasil scan
            return $this->parsePassportData($rawText);

        } catch (\Exception $e) {
            return [
                'status'  => false,
                'error'   => $e->getMessage(),
                'raw_text' => ''
            ];
        }
    }

    /**
     * Regex Sederhana untuk mengekstrak data umum dari hasil Tesseract
     */
    private function parsePassportData(string $text): array
    {
        // Parsing data menggunakan Regular Expression (Regex)
        $data = [
            'nik_paspor'      => '',
            'nama'            => '',
            'kewarganegaraan' => 'WNI', // Default awal
            'tgl_lahir'       => '',
            'raw_text'        => $text // Menyimpan mentahan teks untuk debugging
        ];

        // Contoh Ekstraksi No. Paspor (Biasanya gabungan huruf & angka 8-9 karakter)
        if (preg_match('/[A-Z][0-9]{7,8}/', $text, $matches)) {
            $data['nik_paspor'] = $matches[0];
        }

        // Contoh Deteksi Kewarganegaraan berdasarkan kata kunci
        if (preg_match('/INDONESIA/i', $text)) {
            $data['kewarganegaraan'] = 'WNI';
        } elseif (preg_match('/FOREIGN|ASING/i', $text)) {
            $data['kewarganegaraan'] = 'WNA';
        }

        return [
            'status' => true,
            'data'   => $data
        ];
    }
}