<?php

namespace App\Libraries;

use thiagoalessio\TesseractOCR\TesseractOCR;

class OcrService
{
    protected $executablePath;

    public function __construct()
    {
        $this->executablePath = 'C:\\Program Files\\Tesseract-OCR\\tesseract.exe';
    }

    public function scanImage(string $filePath): array
    {
        try {
            $ocr = new TesseractOCR($filePath);
            
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $ocr->executable($this->executablePath);
            }

            // Jalankan Tesseract
            $rawText = $ocr->run();

            return $this->parsePassportData($rawText);

        } catch (\Exception $e) {
            return [
                'status'   => false,
                'error'    => $e->getMessage(),
                'raw_text' => ''
            ];
        }
    }

    private function parsePassportData(string $text): array
    {
        $extracted = [
            'kode_permohonan'  => '',
            'nama_billing'     => '',
            'tgl_lahir'        => '',
            'jenis_permohonan' => '',
            'dicetak_tanggal'  => '',
            'raw_text'         => $text
        ];

        // 1. Dicetak Tanggal (Contoh: "Dicetak tanggal : 06-10-2026")
        if (preg_match('/Dicetak\s*tanggal\s*[:\.]?\s*(\d{2}[\/\-]\d{2}[\/\-]\d{4})/i', $text, $matches)) {
            $extracted['dicetak_tanggal'] = $this->formatDateToInput($matches[1]);
        }

        // 2. Kode Permohonan (Contoh: "Kode Permohonan : 1188000014643499" - deretan angka 14-16 digit)
        if (preg_match('/Kode\s*Permohonan[\s\S]*?(\d{14,16})/i', $text, $matches)) {
            $extracted['kode_permohonan'] = $matches[1];
        } else if (preg_match('/\b(11\d{12,14})\b/', $text, $matches)) {
        // Fallback: Mencari pola angka diawali '11' sebanyak 14-16 digit (sesuai nomor resi imigrasi)
        $extracted['kode_permohonan'] = $matches[1];
    }

        // 3. Nama di Billing (Contoh: "Nama di Billing : ISMIYATI A NOE")
        if (preg_match('/(?:Nama\s*di\s*Billing|Billing)\s*[\:\.\;\=\|\-\_]*\s*([A-Z\s\.\'\`]+?)(?=\s*Tanggal|\s*Jenis|\r|\n|$)/i', $text, $matches)) {
        $cleanName = preg_replace('/^(?:di\s*Billing|Billing|Nama)\s*/i', '', trim($matches[1]));
        $extracted['nama_billing'] = trim($cleanName);
    }

        // 4. Tanggal Lahir (Contoh: "Tanggal Lahir : 03-04-2003")
        if (preg_match('/Tanggal\s*Lahir\s*[\:\.\;\=\|\-\_]*\s*(\d{2}[\/\-]\d{2}[\/\-]\d{4})/i', $text, $matches)) {
        $extracted['tgl_lahir'] = $this->formatDateToInput($matches[1]);
    } else {
        // Fallback: Ambil semua format tanggal (dd-mm-yyyy) dan cari tanggal yang TIDAK SAMA dengan dicetak_tanggal
        if (preg_match_all('/(\d{2}[\/\-]\d{2}[\/\-]\d{4})/', $text, $allDates)) {
            foreach ($allDates[1] as $dateFound) {
                $formattedDate = $this->formatDateToInput($dateFound);
                if ($formattedDate !== $extracted['dicetak_tanggal']) {
                    $extracted['tgl_lahir'] = $formattedDate;
                    break;
                }
            }
        }
    }

        // 5. Jenis Permohonan (Contoh: "Jenis Permohonan : BARU" atau "PENGGANTIAN")
        if (preg_match('/Jenis\s*Permohonan\s*[:\.]?\s*([A-Z]+)/i', $text, $matches)) {
        $extracted['jenis_permohonan'] = trim($matches[1]);
    }

        return [
            'status' => true,
            'data'   => $extracted
        ];
    }

    // Helper merubah DD-MM-YYYY menjadi YYYY-MM-DD untuk <input type="date">
    private function formatDateToInput(string $dateStr): string
    {
        $parts = preg_split('/[\/\-]/', $dateStr);
        if (count($parts) === 3) {
            return $parts[2] . '-' . $parts[1] . '-' . $parts[0];
        }
        return '';
    }
}