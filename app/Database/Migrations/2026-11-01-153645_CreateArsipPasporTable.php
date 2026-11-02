<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateArsipPasporTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'kode_permohonan' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'nama_billing' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'tgl_lahir' => [
                'type' => 'DATE',
            ],
            'jenis_permohonan' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'dicetak_tanggal' => [
                'type' => 'DATE',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_by' => [
                'type' => 'INTEGER',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('kode_permohonan');
        $this->forge->addKey('created_at');

        $this->forge->addForeignKey(
            'created_by',
            'users',
            'id',
            'CASCADE',
            'RESTRICT'
        );

        $this->forge->createTable('arsip_paspor', true);
    }

    public function down()
    {
        $this->forge->dropTable('arsip_paspor', true);
    }
}