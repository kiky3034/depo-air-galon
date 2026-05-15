<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksis';
    protected $primaryKey = 'id_transaksi';

    protected $fillable = [
        'id_pelanggan', 'id_user', 'tanggal', 'ongkos_kirim', 'total_harga',
        'status_antar', 'status_bayar', 'metode_bayar', 'bukti_bayar',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function details()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_transaksi', 'id_transaksi');
    }

    public function isLunas(): bool
    {
        return in_array($this->status_bayar, ['Sudah Bayar', 'Lunas']);
    }
}
