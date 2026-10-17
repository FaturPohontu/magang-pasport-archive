<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UserModel;

class AuthController extends BaseController
{
    public function login()
    {
        // Jika sudah login, arahkan ke dashboard
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/login');
    }

    public function attemptLogin()
    {
        $rules = [
            'username' => 'required|max_length[100]',
            'password' => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $username = trim((string) $this->request->getPost('username'));
        $password = (string) $this->request->getPost('password');

        $userModel = new UserModel();
        $user = $userModel->where('username', $username)->first();

        if (! $user || ! password_verify($password, $user['password_hash'])) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Username atau password salah.');
        }

        // Regenerasi session setelah autentikasi berhasil
        session()->regenerate();

        session()->set([
            'user_id'    => $user['id'],
            'username'   => $user['username'],
            'nama'       => $user['nama'],
            'role'       => $user['role'],
            'isLoggedIn' => true,
        ]);

        return redirect()->to('/dashboard');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/login')
            ->with('success', 'Kamu berhasil logout.');
    }
}