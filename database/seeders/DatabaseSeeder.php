<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Pengaturan;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin default
        User::create([
            'nama' => 'Administrator',
            'username' => 'admin',
            'password' => 'admin123',
            'role' => 'admin',
            'no_telp' => '081234567890',
            'alamat' => 'Semarang',
        ]);

        // Pemilik default
        User::create([
            'nama' => 'Pemilik Tirta Kencana',
            'username' => 'pemilik',
            'password' => 'pemilik123',
            'role' => 'pemilik',
            'no_telp' => '081234567891',
            'alamat' => 'Semarang',
        ]);

        // Pegawai default
        User::create([
            'nama' => 'Pegawai 1',
            'username' => 'pegawai',
            'password' => 'pegawai123',
            'role' => 'pegawai',
            'no_telp' => '081234567892',
            'alamat' => 'Semarang',
        ]);

        // Pengaturan default
        Pengaturan::create([
            'nama_usaha' => 'Tirta Kencana',
            'alamat_usaha' => 'Jl. Contoh No. 1, Semarang',
            'no_telp_usaha' => '081234567890',
            'nama_bank' => 'Bank BCA',
            'no_rekening' => '1234567890',
            'atas_nama' => 'Tirta Kencana',
            'ongkir_default' => 5000,
        ]);
    }
}
