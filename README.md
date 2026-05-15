# Tirta Kencana - Sistem Informasi Depot Air Galon

Aplikasi manajemen depot air galon (Tirta Kencana) yang telah dimigrasi dari PHP Native ke Laravel. Aplikasi ini memiliki fitur manajemen stok otomatis, riwayat transaksi, 4 level hak akses (Role), dan fitur upload bukti pembayaran.

## 📌 Fitur Utama

- **4 Role Akses:** Administrator, Pemilik, Pegawai, Pelanggan.
- **Manajemen Transaksi:** Pembelian air galon dengan metode antar/ambil sendiri, serta pembayaran cash/transfer.
- **Manajemen Produk & Stok:** Perhitungan stok otomatis saat transaksi terjadi, serta rollback stok saat transaksi dihapus.
- **Manajemen User:** Penambahan user dan pegawai beserta upload foto profil (fallback otomatis menggunakan ui-avatars jika kosong).
- **Laporan:** Fitur cetak laporan transaksi harian/bulanan untuk pemilik depot.
- **Notifikasi Toast:** Feedback interaktif menggunakan toast notification.

## 🛠️ Persyaratan Sistem (Requirements)

Sebelum menginstal, pastikan komputer/server Anda telah menginstal:

- **PHP** >= 8.2
- **Composer** (untuk instalasi dependency Laravel)
- **MySQL / MariaDB**
- **Git** (Opsional, untuk clone repository)

---

## 🚀 Panduan Instalasi (Langkah demi Langkah)

### 1. Clone / Download Repository

Silakan clone repository ini menggunakan Git:

```bash
git clone https://github.com/username-anda/depo-air-galon.git
cd depo-air-galon
```

_(Atau download sebagai .ZIP, lalu ekstrak dan buka folder tersebut di terminal/command prompt)._

### 2. Install Dependency PHP (Vendor)

Jalankan perintah berikut untuk mengunduh semua package yang dibutuhkan Laravel:

```bash
composer install
```

### 3. Konfigurasi Environment (.env)

Copy file `.env.example` dan ubah namanya menjadi `.env`:

```bash
cp .env.example .env
```

_(Di Windows Command Prompt: `copy .env.example .env`)_

Buka file `.env` di text editor Anda, lalu sesuaikan koneksi database. Contoh:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=depo-air-galon  # <-- Pastikan database ini sudah Anda buat di phpMyAdmin!
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate Application Key

Jalankan perintah ini agar Laravel meng-generate kunci enkripsi untuk aplikasi Anda:

```bash
php artisan key:generate
```

### 5. Buat Database Kosong di MySQL

Buka aplikasi MySQL Anda (seperti **phpMyAdmin**, HeidiSQL, DBeaver, dll) dan buat sebuah database baru sesuai dengan nama yang Anda masukkan di file `.env` tadi (contoh: `depo_air_galon`). **Biarkan kosong saja.**

### 6. Migrasi & Seeding Database

Jalankan perintah ini untuk membangun tabel-tabel secara otomatis beserta data awalnya (dummy data & akun default):

```bash
php artisan migrate:fresh --seed
```

### 7. Jalankan Server Lokal

Setelah semua selesai, jalankan server pengembangan bawaan Laravel:

```bash
php artisan serve
```

Akses aplikasi melalui browser di: **http://127.0.0.1:8000**

---

## 🔐 Akun Login Default

Karena Anda sudah menjalankan perintah `--seed` pada langkah ke-6, Anda bisa langsung login menggunakan akun berikut:

| Role Akses             | Username              | Password       |
| :--------------------- | :-------------------- | :------------- |
| **Administrator**      | `admin`               | `admin123`     |
| **Pemilik**            | `pemilik`             | `pemilik123`   |
| **Pegawai**            | `pegawai`             | `pegawai123`   |
| **Pelanggan** (Sample) | _cek dari menu admin_ | `pelanggan123` |

_(Note: Pelanggan baru bisa melakukan **Registrasi Mandiri** langsung melalui halaman login)._

---

## 📂 Struktur Direktori Upload

Aplikasi ini menyimpan file upload pengguna di luar direktori `/storage` Laravel, yaitu langsung ke public. Struktur foldernya sebagai berikut:

- `public/upload/user/` → Menyimpan foto profil user/pegawai.
- `public/upload/bukti_bayar/` → Menyimpan bukti transfer dari pelanggan.

**Catatan Git:** Semua file `.jpg`, `.png`, dll yang di-upload saat testing lokal **TIDAK AKAN** ter-push ke GitHub karena telah diabaikan di `.gitignore`. Hanya file kosong bernama `.gitkeep` yang akan ter-push untuk menjaga ketersediaan folder saat aplikasi di-clone.

---

## 🛠️ Troubleshooting (Masalah yang Sering Terjadi)

**1. Gambar Upload Tidak Muncul?**
Pastikan folder `public/upload/user` dan `public/upload/bukti_bayar` memiliki akses _Read & Write_ jika di-deploy ke Linux Server (chmod 775).

**2. Error "No application encryption key has been specified."?**
Anda lupa menjalankan langkah 4. Jalankan: `php artisan key:generate`.

**3. Error "Base table or view not found"?**
Anda lupa menjalankan langkah 6. Jalankan: `php artisan migrate:fresh --seed`.
