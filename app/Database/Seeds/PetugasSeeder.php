<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\UserModel;

class PetugasSeeder extends Seeder
{
    public function run()
    {
        $userModel = new UserModel();

        // Hindari membuat akun admin duplikat
        $adminSudahAda = $userModel
            ->where('username', 'petugas')
            ->first();

        if ($adminSudahAda) {
            echo "Akun admin sudah tersedia.\n";
            return;
        }

        // Ganti password ini dengan password kuat milikmu
        $password = env('PETUGAS_INITIAL_PASSWORD');

        if (! is_string($password) || strlen($password) < 12) {
            throw new \RuntimeException(
                'PETUGAS_INITIAL_PASSWORD belum diatur atau kurang dari 12 karakter.'
            );
        }

        $userModel->insert([
            'username'      => 'petugas',
            'password_hash' => password_hash(
                $password,
                PASSWORD_DEFAULT
            ),
            'nama'          => 'Petugas',
            'role'          => 'petugas',
            'is_active'     => 1,
        ]);

        echo "Akun petugas berhasil dibuat.\n";
    }
}