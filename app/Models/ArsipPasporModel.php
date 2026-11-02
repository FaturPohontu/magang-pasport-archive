<?php

namespace App\Models;

use CodeIgniter\Model;

class ArsipPasporModel extends Model
{
    protected $table            = 'arsip_paspor';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $protectFields    = true;

    protected $allowedFields = [
        'kode_permohonan',
        'nama_billing',
        'tgl_lahir',
        'jenis_permohonan',
        'dicetak_tanggal',
        'created_by',
        'sheets_synced',
        'sheets_synced_at',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';

    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
