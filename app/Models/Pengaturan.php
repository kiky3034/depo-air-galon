<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    protected $table = 'pengaturans';
    protected $primaryKey = 'id_pengaturan';

    protected $fillable = [
        'nama_usaha', 'alamat_usaha', 'no_telp_usaha',
        'nama_bank', 'no_rekening', 'atas_nama', 'ongkir_default',
    ];

    /**
     * Get the single settings record
     */
    public static function getSetting()
    {
        return self::first();
    }
}
