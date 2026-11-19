<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CreateInitialAdmin extends Seeder
{
    public function run()
    {
        // Periksa apakah akun Admin sudah tersedia
        $admin = $this->db->table('users')
            ->where('role', 'admin')
            ->countAllResults();

        if ($admin > 0) {
            echo "Akun Admin sudah tersedia.\n";
            return;
        }

        // Buat akun Admin pertama
        $this->db->table('users')->insert([
            'username'      => 'admin',
            'nama'          => 'Administrator SIPASKANIM',
            'password_hash' => password_hash(
                'admin123',
                PASSWORD_DEFAULT
            ),
            'role'          => 'admin',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);

        echo "Akun Admin berhasil dibuat.\n";
    }
}
