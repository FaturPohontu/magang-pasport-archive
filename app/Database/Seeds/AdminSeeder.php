<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\UserModel;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $userModel = new UserModel();

        // Hindari membuat akun admin duplikat
        $adminSudahAda = $userModel
            ->where('username', 'admin')
            ->first();

        if ($adminSudahAda) {
            echo "Akun admin sudah tersedia.\n";
            return;
        }

        // Ganti password ini dengan password kuat milikmu
        $password = env('ADMIN_INITIAL_PASSWORD');

        if (! is_string($password) || strlen($password) < 12) {
            throw new \RuntimeException(
                'ADMIN_INITIAL_PASSWORD belum diatur atau kurang dari 12 karakter.'
            );
        }

        $userModel->insert([
            'username'      => 'admin',
            'password_hash' => password_hash(
                $password,
                PASSWORD_DEFAULT
            ),
            'nama'          => 'Administrator',
            'role'          => 'admin',
        ]);

        echo "Akun admin berhasil dibuat.\n";
    }
}