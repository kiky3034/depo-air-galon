<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pelanggan;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Pengaturan;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $total_user = User::count();
        $total_pelanggan = Pelanggan::count();
        $total_transaksi = Transaksi::count();
        $total_produk = Produk::count();

        $transaksi_terbaru = Transaksi::with('pelanggan')
            ->orderByDesc('id_transaksi')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'total_user', 'total_pelanggan', 'total_transaksi', 'total_produk', 'transaksi_terbaru'
        ));
    }

    // ========== DATA USER ==========
    public function dataUser(Request $request)
    {
        $cari = $request->get('cari', '');

        // Tabel 1: Pegawai & Pemilik
        $query_staff = User::whereIn('role', ['pegawai', 'pemilik']);
        if ($cari) {
            $query_staff->where(function ($q) use ($cari) {
                $q->where('nama', 'like', "%$cari%")->orWhere('username', 'like', "%$cari%");
            });
        }
        $staff_users = $query_staff->orderByDesc('id_user')->get();

        // Tabel 2: Admin
        $admin_users = User::where('role', 'admin')->orderByDesc('id_user')->get();

        return view('admin.data_user', compact('staff_users', 'admin_users', 'cari'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'username' => 'required|unique:users,username',
            'password' => 'required|min:3',
            'role' => 'required|in:pemilik,pegawai',
            'foto' => 'nullable|image|max:5120',
        ]);

        $data = $request->only('nama', 'username', 'password', 'role', 'no_telp', 'alamat');
        if ($request->hasFile('foto')) {
            $nama_foto = 'user_' . time() . '.' . $request->foto->extension();
            $request->foto->move(public_path('upload/user'), $nama_foto);
            $data['foto'] = $nama_foto;
        }
        User::create($data);
        return back()->with('success', 'User berhasil ditambahkan!');
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'nama' => 'required',
            'username' => 'required|unique:users,username,' . $id . ',id_user',
            'foto' => 'nullable|image|max:5120',
        ]);

        $data = $request->only('nama', 'username', 'no_telp', 'alamat');
        if ($request->filled('role')) {
            $data['role'] = $request->role;
        }
        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }
        if ($request->hasFile('foto')) {
            // Hapus foto lama
            if ($user->foto && file_exists(public_path('upload/user/' . $user->foto))) {
                unlink(public_path('upload/user/' . $user->foto));
            }
            $nama_foto = 'user_' . $user->id_user . '_' . time() . '.' . $request->foto->extension();
            $request->foto->move(public_path('upload/user'), $nama_foto);
            $data['foto'] = $nama_foto;
        }
        $user->update($data);
        return back()->with('success', 'User berhasil diperbarui!');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->id_user == session('id_user')) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri!');
        }
        $user->delete();
        return back()->with('success', 'User berhasil dihapus!');
    }

    // ========== DATA TRANSAKSI ==========
    public function dataTransaksi(Request $request)
    {
        $cari = $request->get('cari', '');
        $query = Transaksi::with(['pelanggan', 'details.produk']);

        if ($cari) {
            $query->whereHas('pelanggan', function ($q) use ($cari) {
                $q->where('nama', 'like', "%$cari%")
                  ->orWhere('kode_pelanggan', 'like', "%$cari%");
            });
        }

        $transaksis = $query->orderByDesc('id_transaksi')->paginate(10)->appends(['cari' => $cari]);

        $total_t = Transaksi::count();
        $total_belum_bayar = Transaksi::where('status_bayar', 'Belum Bayar')->count();
        $nominal_lunas = Transaksi::where('status_bayar', 'Sudah Bayar')->sum('total_harga');

        $pengaturan = Pengaturan::getSetting();

        return view('admin.data_transaksi', compact('transaksis', 'cari', 'total_t', 'total_belum_bayar', 'nominal_lunas', 'pengaturan'));
    }

    public function updateStatusTransaksi(Request $request, $id)
    {
        $transaksi = Transaksi::findOrFail($id);
        if ($request->has('status_bayar')) {
            $transaksi->status_bayar = $request->status_bayar;
        }
        if ($request->has('status_antar')) {
            $transaksi->status_antar = $request->status_antar;
        }
        $transaksi->save();
        return back()->with('success', 'Status transaksi diperbarui!');
    }

    public function deleteTransaksi($id)
    {
        $transaksi = Transaksi::with('details.produk')->findOrFail($id);

        // Rollback stok
        foreach ($transaksi->details as $detail) {
            $produk = $detail->produk;
            if ($produk) {
                $kembali = $produk->hitungPenguranganStok($detail->jumlah);
                $produk->increment('stok', $kembali);
            }
        }

        $transaksi->details()->delete();
        $transaksi->delete();
        return back()->with('success', 'Transaksi berhasil dihapus dan stok dikembalikan!');
    }

    // ========== EDIT TRANSAKSI ==========
    public function editTransaksi($id)
    {
        $transaksi = Transaksi::with('details')->findOrFail($id);
        $pengaturan = Pengaturan::getSetting();
        $produks = Produk::orderBy('jenis_air')->get();
        $pelanggans = Pelanggan::orderBy('nama')->get();

        $detail_lama = [];
        foreach ($transaksi->details as $d) {
            $detail_lama[$d->id_produk] = $d->jumlah;
        }

        return view('admin.edit_transaksi', compact('transaksi', 'pengaturan', 'produks', 'pelanggans', 'detail_lama'));
    }

    public function updateTransaksi(Request $request, $id)
    {
        $transaksi = Transaksi::with('details.produk')->findOrFail($id);

        // 1. Rollback stok lama
        foreach ($transaksi->details as $detail) {
            $produk = $detail->produk;
            if ($produk) {
                $kembali = $produk->hitungPenguranganStok($detail->jumlah);
                $produk->increment('stok', $kembali);
            }
        }

        // 2. Hapus detail lama
        $transaksi->details()->delete();

        // 3. Insert detail baru
        $metode_kirim = $request->metode;
        $ongkir = ($metode_kirim == 'antar') ? ($request->ongkos_kirim ?? 0) : 0;
        $status_antar = ($metode_kirim == 'antar') ? ($request->status_antar ?? 'Belum') : '-';
        $total_produk = 0;

        foreach ($request->produk as $i => $id_produk) {
            $jumlah = $request->jumlah[$i] ?? 0;
            if ($jumlah > 0) {
                $produk = Produk::find($id_produk);
                $subtotal = $produk->harga * $jumlah;
                $kurang = $produk->hitungPenguranganStok($jumlah);

                DetailTransaksi::create([
                    'id_transaksi' => $id,
                    'id_produk' => $id_produk,
                    'jumlah' => $jumlah,
                    'harga_satuan' => $produk->harga,
                    'subtotal' => $subtotal,
                ]);

                $produk->decrement('stok', $kurang);
                $total_produk += $subtotal;
            }
        }

        $grand_total = $total_produk + $ongkir;
        $transaksi->update([
            'id_pelanggan' => $request->id_pelanggan,
            'ongkos_kirim' => $ongkir,
            'total_harga' => $grand_total,
            'status_antar' => $status_antar,
            'metode_bayar' => $request->metode_bayar,
        ]);

        return redirect()->route('admin.transaksi')->with('success', 'Transaksi berhasil diperbarui!');
    }

    // ========== PRODUK ==========
    public function tampilProduk()
    {
        $produks = Produk::orderByDesc('id_produk')->get();
        $total_produk = Produk::count();
        $stok_menipis = Produk::where(function ($q) {
            $q->where(function ($q2) {
                $q2->where('satuan_stok', 'pcs')->where('stok', '<', 8);
            })->orWhere(function ($q2) {
                $q2->where('satuan_stok', 'liter')->where('stok', '<', 40);
            });
        })->count();

        return view('admin.tampil_produk', compact('produks', 'total_produk', 'stok_menipis'));
    }

    public function tambahProduk()
    {
        return view('admin.tambah_produk');
    }

    public function storeProduk(Request $request)
    {
        $request->validate([
            'jenis_air' => 'required',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|numeric|min:0',
        ]);

        // Cek duplikat
        $exists = Produk::where('jenis_air', $request->jenis_air)
                        ->where('jenis_layanan', $request->jenis_layanan)
                        ->exists();
        if ($exists) {
            return back()->with('error', 'Produk dengan jenis air dan layanan yang sama sudah ada!');
        }

        Produk::create($request->only('jenis_air', 'satuan_stok', 'isi_per_unit', 'harga', 'stok', 'jenis_layanan'));
        return redirect()->route('admin.produk')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function editProduk($id)
    {
        $produk = Produk::findOrFail($id);
        return view('admin.edit_produk', compact('produk'));
    }

    public function updateProduk(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);
        $produk->update($request->only('jenis_air', 'satuan_stok', 'isi_per_unit', 'harga', 'stok', 'jenis_layanan'));
        return redirect()->route('admin.produk')->with('success', 'Produk berhasil diperbarui!');
    }

    public function deleteProduk($id)
    {
        $produk = Produk::findOrFail($id);
        if ($produk->detailTransaksis()->count() > 0) {
            return back()->with('error', 'Gagal menghapus! Produk ini sudah memiliki riwayat transaksi.');
        }
        $produk->delete();
        return back()->with('success', 'Produk berhasil dihapus!');
    }

    // ========== PENGATURAN ==========
    public function pengaturan()
    {
        $data = Pengaturan::getSetting();
        return view('admin.pengaturan', compact('data'));
    }

    public function updatePengaturan(Request $request)
    {
        $pengaturan = Pengaturan::first();
        if (!$pengaturan) {
            $pengaturan = new Pengaturan();
        }
        $pengaturan->fill($request->only(
            'nama_usaha', 'alamat_usaha', 'no_telp_usaha',
            'nama_bank', 'no_rekening', 'atas_nama', 'ongkir_default'
        ));
        $pengaturan->save();
        return back()->with('success', 'Pengaturan berhasil diperbarui!');
    }
}
