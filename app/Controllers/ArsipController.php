<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ArsipPasporModel;

class ArsipController extends BaseController
{
    public function index()
    {
        // Pastikan pengguna sudah login
        if (! session()->get('isLoggedIn')) {
            return redirect()->to('/login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $model = new ArsipPasporModel();

        // Ambil kata kunci pencarian
        $keyword = $this->request->getGet('q');

        if (! is_string($keyword)) {
            $keyword = '';
        }

        $keyword = trim($keyword);

        // Pencarian berdasarkan kode permohonan atau nama billing
        if ($keyword !== '') {
            $model->groupStart()
                ->like('kode_permohonan', $keyword)
                ->orLike('nama_billing', $keyword)
                ->groupEnd();
        }

        // Ambil data terbaru terlebih dahulu
        $dataArsip = $model
            ->orderBy('created_at', 'DESC')
            ->paginate(10, 'arsip');

        return view('arsip/index', [
            'dataArsip' => $dataArsip,
            'pager'     => $model->pager,
            'keyword'   => $keyword,
        ]);
    }
}
