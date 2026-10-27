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
        $this->spreadsheetId = '1fczya0xbScChjNh3Ji7UHOdvJm9yCt9QaG6qvAS_7IY'; 

        $client = new Client();
        $client->setApplicationName('Passport OCR System');
        $client->setScopes([Sheets::SPREADSHEETS]);
        
        $client->setHttpClient(new \GuzzleHttp\Client(['verify' => false]));

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

            // Urutan array $dataRow disesuaikan dengan urutan kolom di Spreadsheet:
            // [Kode Permohonan, Nama Billing, Tanggal Lahir, Jenis Permohonan, Dicetak Tanggal]
            $body = new ValueRange([
            'values' => [array_values($dataRow)]
            ]);

            $params = [
                'valueInputOption' => 'USER_ENTERED' // Agar tanggal/angka terformat otomatis di Sheets
            ];

            // 'Sheet1' adalah nama tab/sheet di spreadsheet Anda
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