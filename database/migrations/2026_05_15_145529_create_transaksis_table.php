<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->unsignedBigInteger('id_pelanggan');
            $table->unsignedBigInteger('id_user')->nullable(); // null = pesanan mandiri pelanggan
            $table->date('tanggal');
            $table->integer('ongkos_kirim')->default(0);
            $table->integer('total_harga')->default(0);
            $table->string('status_antar')->default('-'); // -, Belum, Selesai, Sudah, Datang Sendiri
            $table->string('status_bayar')->default('Belum Bayar'); // Belum Bayar, Sudah Bayar
            $table->string('metode_bayar')->default('Cash'); // Cash, Transfer
            $table->string('bukti_bayar')->nullable();
            $table->timestamps();

            $table->foreign('id_pelanggan')->references('id_pelanggan')->on('pelanggan')->onDelete('cascade');
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
