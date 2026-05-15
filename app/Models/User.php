<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory;

    protected $primaryKey = 'id_user';

    protected $fillable = [
        'nama', 'username', 'password', 'role', 'no_telp', 'alamat', 'foto', 'id_pelanggan',
    ];

    protected $hidden = ['password'];

    // Plain text password - no hashing (sesuai project asli)
    // Override untuk disable auto-hashing
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = $value;
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'id_user', 'id_user');
    }
}
