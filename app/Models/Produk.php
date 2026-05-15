<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produks';
    protected $primaryKey = 'id_produk';

    protected $fillable = [
        'jenis_air', 'satuan_stok', 'isi_per_unit', 'harga', 'stok', 'jenis_layanan',
    ];

    public function detailTransaksis()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_produk', 'id_produk');
    }

    /**
     * Cek apakah stok menipis
     */
    public function isStokMenipis(): bool
    {
        if ($this->satuan_stok === 'pcs' && $this->stok < 8) return true;
        if ($this->satuan_stok === 'liter' && $this->stok < 40) return true;
        return false;
    }

    /**
     * Hitung pengurangan stok berdasarkan satuan
     */
    public function hitungPenguranganStok(int $jumlah): int
    {
        return ($this->satuan_stok === 'liter') ? $jumlah * $this->isi_per_unit : $jumlah;
    }
}
