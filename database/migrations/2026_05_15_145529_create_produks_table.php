<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produks', function (Blueprint $table) {
            $table->id('id_produk');
            $table->string('jenis_air');
            $table->enum('satuan_stok', ['pcs', 'liter'])->default('pcs');
            $table->integer('isi_per_unit')->default(19);
            $table->integer('harga')->default(0);
            $table->integer('stok')->default(0);
            $table->enum('jenis_layanan', ['isi_ulang', 'galon_baru_segel'])->default('isi_ulang');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produks');
    }
};
