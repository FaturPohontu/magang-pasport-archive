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
        $password = 'Fatur1405';

        $userModel->insert([
            'username'      => 'admin',
            'password_hash' => password_hash(
                $password,
                PASSWORD_DEFAULT
            ),
            'nama'          => 'Fatur',
            'role'          => 'admin',
        ]);

        echo "Akun admin berhasil dibuat.\n";
    }
}