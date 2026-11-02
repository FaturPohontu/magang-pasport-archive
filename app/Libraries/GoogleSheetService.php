<?php

namespace App\Libraries;

use Google\Client;
use Google\Service\Sheets;
use Google\Service\Sheets\ValueRange;

class GoogleSheetService
{
    protected $service;
    protected $spreadsheetId;

    public function __construct()
    {
        // Masukkan Spreadsheet ID milik Anda di sini
        $this->spreadsheetId = env('GOOGLE_SHEETS_SPREADSHEET_ID');
        // $this->spreadsheetId = ''; 

        $client = new Client();
        $client->setApplicationName('Passport OCR System');
        $client->setScopes([Sheets::SPREADSHEETS]);

        // Path ke file credentials.json
        $credentialsPath = APPPATH . 'Config/credentials.json';
        $client->setAuthConfig($credentialsPath);

        $this->service = new Sheets($client);
    }

    /**
     * Menambahkan baris data baru di paling bawah Spreadsheet
     */
    public function appendRow(array $dataRow): bool
    {
        try {

            array_unshift($dataRow, ''); // Menambahkan nilai kosong di awal array

            // [Kode Permohonan, Nama Billing, Tanggal Lahir, Jenis Permohonan, Dicetak Tanggal]
            $body = new ValueRange([
            'values' => [array_values($dataRow)]
            ]);

            $params = [
                'valueInputOption' => 'USER_ENTERED' // Agar tanggal/angka terformat otomatis di Sheets
            ];

            $range = 'Sheet1!B:F'; 

            $this->service->spreadsheets_values->append(
                $this->spreadsheetId,
                $range,
                $body,
                $params
            );
            return true;

        } catch (\Exception $e) {
            log_message('error', 'Google Sheet Error: ' . $e->getMessage());
            return false;
        }
    }
}