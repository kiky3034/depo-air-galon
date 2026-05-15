<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Pegawai\PegawaiController;
use App\Http\Controllers\Pelanggan\PelangganController;
use App\Http\Controllers\Pemilik\PemilikController;

// ========== AUTH (PUBLIC) ==========
Route::get('/', fn() => session()->has('id_user') ? redirect()->route(session('role') . '.dashboard') : redirect()->route('login'));
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.process');
Route::get('/register-mandiri', [AuthController::class, 'showRegisterMandiri'])->name('register.mandiri');
Route::post('/register-mandiri', [AuthController::class, 'registerMandiri'])->name('register.mandiri.process');

// ========== ADMIN ==========
Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    // User
    Route::get('/user', [AdminController::class, 'dataUser'])->name('user');
    Route::post('/user', [AdminController::class, 'storeUser'])->name('user.store');
    Route::put('/user/{id}', [AdminController::class, 'updateUser'])->name('user.update');
    Route::delete('/user/{id}', [AdminController::class, 'deleteUser'])->name('user.delete');
    // Transaksi
    Route::get('/transaksi', [AdminController::class, 'dataTransaksi'])->name('transaksi');
    Route::put('/transaksi/{id}/status', [AdminController::class, 'updateStatusTransaksi'])->name('transaksi.status');
    Route::delete('/transaksi/{id}', [AdminController::class, 'deleteTransaksi'])->name('transaksi.delete');
    Route::get('/transaksi/{id}/edit', [AdminController::class, 'editTransaksi'])->name('transaksi.edit');
    Route::put('/transaksi/{id}', [AdminController::class, 'updateTransaksi'])->name('transaksi.update');
    // Produk
    Route::get('/produk', [AdminController::class, 'tampilProduk'])->name('produk');
    Route::get('/produk/tambah', [AdminController::class, 'tambahProduk'])->name('produk.tambah');
    Route::post('/produk', [AdminController::class, 'storeProduk'])->name('produk.store');
    Route::get('/produk/{id}/edit', [AdminController::class, 'editProduk'])->name('produk.edit');
    Route::put('/produk/{id}', [AdminController::class, 'updateProduk'])->name('produk.update');
    Route::delete('/produk/{id}', [AdminController::class, 'deleteProduk'])->name('produk.delete');
    // Pengaturan
    Route::get('/pengaturan', [AdminController::class, 'pengaturan'])->name('pengaturan');
    Route::put('/pengaturan', [AdminController::class, 'updatePengaturan'])->name('pengaturan.update');
});

// ========== PEGAWAI ==========
Route::prefix('pegawai')->name('pegawai.')->middleware('role:pegawai')->group(function () {
    Route::get('/dashboard', [PegawaiController::class, 'dashboard'])->name('dashboard');
    // Pelanggan
    Route::get('/pelanggan', [PegawaiController::class, 'dataPelanggan'])->name('pelanggan');
    Route::get('/pelanggan/tambah', [PegawaiController::class, 'tambahPelanggan'])->name('pelanggan.tambah');
    Route::post('/pelanggan', [PegawaiController::class, 'storePelanggan'])->name('pelanggan.store');
    Route::get('/pelanggan/{id}/edit', [PegawaiController::class, 'editPelanggan'])->name('pelanggan.edit');
    Route::put('/pelanggan/{id}', [PegawaiController::class, 'updatePelanggan'])->name('pelanggan.update');
    Route::delete('/pelanggan/{id}', [PegawaiController::class, 'deletePelanggan'])->name('pelanggan.delete');
    Route::get('/pelanggan/{id}/riwayat', [PegawaiController::class, 'riwayatPesananPelanggan'])->name('pelanggan.riwayat');
    // Transaksi
    Route::get('/transaksi', [PegawaiController::class, 'dataTransaksi'])->name('transaksi');
    Route::get('/transaksi/tambah', [PegawaiController::class, 'tambahPesanan'])->name('transaksi.tambah');
    Route::post('/transaksi', [PegawaiController::class, 'storePesanan'])->name('transaksi.store');
    Route::put('/transaksi/{id}/status', [PegawaiController::class, 'updateStatusTransaksi'])->name('transaksi.status');
    Route::delete('/transaksi/{id}', [PegawaiController::class, 'deleteTransaksi'])->name('transaksi.delete');
    Route::get('/transaksi/{id}/edit', [PegawaiController::class, 'editTransaksi'])->name('transaksi.edit');
    Route::put('/transaksi/{id}', [PegawaiController::class, 'updateTransaksi'])->name('transaksi.update');
    // Produk
    Route::get('/produk', [PegawaiController::class, 'tampilProduk'])->name('produk');
    Route::get('/produk/tambah', [PegawaiController::class, 'tambahProduk'])->name('produk.tambah');
    Route::post('/produk', [PegawaiController::class, 'storeProduk'])->name('produk.store');
    Route::get('/produk/{id}/edit', [PegawaiController::class, 'editProduk'])->name('produk.edit');
    Route::put('/produk/{id}', [PegawaiController::class, 'updateProduk'])->name('produk.update');
    Route::delete('/produk/{id}', [PegawaiController::class, 'deleteProduk'])->name('produk.delete');
});

// ========== PELANGGAN ==========
Route::prefix('pelanggan')->name('pelanggan.')->middleware('role:pelanggan')->group(function () {
    Route::get('/dashboard', [PelangganController::class, 'dashboard'])->name('dashboard');
    Route::get('/pesanan/buat', [PelangganController::class, 'buatPesanan'])->name('pesanan.buat');
    Route::post('/pesanan', [PelangganController::class, 'storePesanan'])->name('pesanan.store');
    Route::get('/riwayat', [PelangganController::class, 'riwayatPesanan'])->name('riwayat');
    Route::post('/pesanan/batal', [PelangganController::class, 'batalkanPesanan'])->name('pesanan.batal');
    Route::post('/pesanan/bukti', [PelangganController::class, 'uploadBukti'])->name('pesanan.bukti');
    Route::get('/profil', [PelangganController::class, 'profil'])->name('profil');
    Route::put('/profil', [PelangganController::class, 'updateProfil'])->name('profil.update');
    Route::put('/profil/password', [PelangganController::class, 'updatePassword'])->name('profil.password');
});

// ========== PEMILIK ==========
Route::prefix('pemilik')->name('pemilik.')->middleware('role:pemilik')->group(function () {
    Route::get('/dashboard', [PemilikController::class, 'dashboard'])->name('dashboard');
    Route::get('/pelanggan', [PemilikController::class, 'dataPelanggan'])->name('pelanggan');
    Route::get('/pelanggan/{id}/riwayat', [PemilikController::class, 'riwayatPesananPelanggan'])->name('pelanggan.riwayat');
    Route::get('/pegawai', [PemilikController::class, 'dataPegawai'])->name('pegawai');
    Route::get('/transaksi', [PemilikController::class, 'dataTransaksi'])->name('transaksi');
    Route::get('/laporan', [PemilikController::class, 'laporan'])->name('laporan');
});
