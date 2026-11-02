<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSheetsSyncToArsipPaspor extends Migration
{
    public function up()
    {
        $this->forge->addColumn('arsip_paspor', [
            'sheets_synced' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'after'      => 'created_by',
            ],
            'sheets_synced_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'sheets_synced',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn(
            'arsip_paspor',
            ['sheets_synced', 'sheets_synced_at']
        );
    }
}
