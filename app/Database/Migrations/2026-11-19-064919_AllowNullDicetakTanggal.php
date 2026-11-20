<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AllowNullDicetakTanggal extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('arsip_paspor', [
            'dicetak_tanggal' => [
                'name' => 'dicetak_tanggal',
                'type' => 'DATE',
                'null' => true,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->modifyColumn('arsip_paspor', [
            'dicetak_tanggal' => [
                'name' => 'dicetak_tanggal',
                'type' => 'DATE',
                'null' => false,
            ],
        ]);
    }
}
