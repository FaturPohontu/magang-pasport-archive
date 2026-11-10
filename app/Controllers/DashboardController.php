<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function index()
    {
        // Pastikan pengguna sudah login
        if (! session()->get('isLoggedIn')) {
            return redirect()->to('/login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $db = \Config\Database::connect();

        // Gunakan waktu lokal Gorontalo (WITA)
        $timezone = new \DateTimeZone('Asia/Makassar');
        $now = new \DateTimeImmutable('now', $timezone);

        // Rentang hari ini
        $startToday = $now->setTime(0, 0, 0);
        $startTomorrow = $startToday->modify('+1 day');

        // Rentang tahun ini
        $startYear = $now
            ->setDate((int) $now->format('Y'), 1, 1)
            ->setTime(0, 0, 0);

        $startNextYear = $startYear->modify('+1 year');

        // 1. Jumlah arsip yang disimpan hari ini
        $scanHariIni = $db->table('arsip_paspor')
            ->where('created_at >=', $startToday->format('Y-m-d H:i:s'))
            ->where('created_at <', $startTomorrow->format('Y-m-d H:i:s'))
            ->countAllResults();

        // 2. Jumlah seluruh arsip paspor
        $totalArsip = $db->table('arsip_paspor')
            ->countAllResults();

        // 3. Jumlah arsip yang disimpan pada tahun berjalan
        $scanTahunIni = $db->table('arsip_paspor')
            ->where('created_at >=', $startYear->format('Y-m-d H:i:s'))
            ->where('created_at <', $startNextYear->format('Y-m-d H:i:s'))
            ->countAllResults();

        return view('dashboard/index', [
            'scanHariIni'  => $scanHariIni,
            'totalArsip'   => $totalArsip,
            'scanTahunIni' => $scanTahunIni,
            'tahunIni'     => $now->format('Y'),
        ]);
    }
}
