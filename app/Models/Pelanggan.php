<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    protected $table = 'pelanggan';
    protected $primaryKey = 'id_pelanggan';

    protected $fillable = [
        'kode_pelanggan', 'nama', 'no_hp', 'alamat',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'id_pelanggan', 'id_pelanggan');
    }

    /**
     * Generate kode pelanggan otomatis: PLG + id
     */
    public static function generateKode(): string
    {
        $max = self::max('id_pelanggan');
        $nextId = $max ? $max + 1 : 1;
        return 'PLG' . $nextId;
    }
}
