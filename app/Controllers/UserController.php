<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class UserController extends BaseController
{
    // Menampilkan daftar Petugas aktif
    public function index()
    {
        $userModel = new UserModel();

        $keyword = trim((string) $this->request->getGet('q'));

        $builder = $userModel
            ->select('id, nama, username, role, is_active, created_at')
            ->where('role', 'petugas')
            ->where('is_active', 1);

        if ($keyword !== '') {
            $builder->groupStart()
                ->like('nama', $keyword)
                ->orLike('username', $keyword)
                ->groupEnd();
        }

        $data = [
            'users'   => $builder->orderBy('created_at', 'DESC')
                                ->paginate(10, 'petugas'),
            'pager'   => $userModel->pager,
            'keyword' => $keyword,
        ];

        return view('users/index', $data);
    }

    // Menambahkan akun Petugas
    public function store()
    {
        $rules = [
            'nama' => [
                'label' => 'Nama lengkap',
                'rules' => 'required|min_length[3]|max_length[150]',
            ],
            'username' => [
                'label' => 'Username',
                'rules' => 'required|min_length[3]|max_length[100]|alpha_numeric_punct|is_unique[users.username]',
            ],
            'password' => [
                'label' => 'Password',
                'rules' => 'required|min_length[8]|max_length[255]',
            ],
            'password_confirm' => [
                'label' => 'Konfirmasi password',
                'rules' => 'required|matches[password]',
            ],
        ];

        if (! $this->validate($rules)) {
            return redirect()->to('/users')
                ->withInput()
                ->with('errors', $this->validator->getErrors())
                ->with('error', 'User atau Password sudah ada.');
        }

        $userModel = new UserModel();

        $userModel->insert([
            'nama'          => trim((string) $this->request->getPost('nama')),
            'username'      => trim((string) $this->request->getPost('username')),
            'password_hash' => password_hash(
                (string) $this->request->getPost('password'),
                PASSWORD_DEFAULT
            ),
            // Role ditentukan oleh sistem, bukan dari input form
            'role'          => 'petugas',
            'is_active'     => 1,
        ]);

        return redirect()->to('/users')
            ->with('success', 'Akun Petugas berhasil ditambahkan.');
    }

    // Menonaktifkan akun Petugas
    public function deactivate($id = null)
    {
        $userModel = new UserModel();

        $petugas = $userModel->find($id);

        if (! $petugas || $petugas['role'] !== 'petugas') {
            return redirect()->to('/users')
                ->with('error', 'Akun Petugas tidak ditemukan.');
        }

        if ((int) $petugas['id'] === (int) session()->get('user_id')) {
            return redirect()->to('/users')
                ->with('error', 'Anda tidak dapat menonaktifkan akun sendiri.');
        }

        if ((int) $petugas['is_active'] !== 1) {
            return redirect()->to('/users')
                ->with('error', 'Akun Petugas sudah nonaktif.');
        }

        $userModel->update($id, [
            'is_active' => 0,
        ]);

        return redirect()->to('/users')
            ->with('success', 'Akun Petugas berhasil dinonaktifkan.');
    }
}