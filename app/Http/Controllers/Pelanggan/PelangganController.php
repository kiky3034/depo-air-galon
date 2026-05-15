<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Pengaturan;
use App\Models\User;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    /**
     * Get id_pelanggan dari session, fallback ke DB jika null
     */
    private function getIdPelanggan()
    {
        $id = session('id_pelanggan');
        if (!$id) {
            $user = User::find(session('id_user'));
            $id = $user?->id_pelanggan;
            if ($id) {
                session(['id_pelanggan' => $id]);
            }
        }
        return $id;
    }

    public function dashboard()
    {
        $id_pelanggan = $this->getIdPelanggan();
        $total_pesanan = Transaksi::where('id_pelanggan', $id_pelanggan)->count();
        $sedang_diantar = Transaksi::where('id_pelanggan', $id_pelanggan)->where('status_antar', 'Belum')->count();
        $produks = Produk::where('stok', '>', 0)->orderByDesc('id_produk')->get();
        return view('pelanggan.dashboard', compact('total_pesanan', 'sedang_diantar', 'produks'));
    }

    public function buatPesanan()
    {
        $pengaturan = Pengaturan::getSetting();
        $produks = Produk::where('stok', '>', 0)->orderByDesc('id_produk')->get();
        return view('pelanggan.buat_pesanan', compact('pengaturan', 'produks'));
    }

    public function storePesanan(Request $request)
    {
        $id_pelanggan = $this->getIdPelanggan();
        if (!$id_pelanggan) {
            return back()->with('error', 'Data pelanggan tidak ditemukan. Hubungi admin.');
        }
        $pengaturan = Pengaturan::getSetting();
        $ongkir = ($request->metode == 'antar') ? ($pengaturan->ongkir_default ?? 0) : 0;
        $status_antar = ($request->metode == 'antar') ? 'Belum' : '-';

        $transaksi = Transaksi::create([
            'id_user' => null, 'id_pelanggan' => $id_pelanggan,
            'tanggal' => now()->toDateString(), 'ongkos_kirim' => $ongkir,
            'total_harga' => 0, 'status_antar' => $status_antar,
            'metode_bayar' => $request->metode_pembayaran ?? 'Cash', 'status_bayar' => 'Belum Bayar',
        ]);

        $total = 0; $ada = false;
        foreach ($request->produk as $i => $id_produk) {
            $jumlah = $request->jumlah[$i] ?? 0;
            if ($jumlah > 0) {
                $ada = true; $produk = Produk::find($id_produk);
                $kurang = $produk->hitungPenguranganStok($jumlah);
                $subtotal = $produk->harga * $jumlah;
                DetailTransaksi::create(['id_transaksi' => $transaksi->id_transaksi, 'id_produk' => $id_produk, 'jumlah' => $jumlah, 'harga_satuan' => $produk->harga, 'subtotal' => $subtotal]);
                $produk->decrement('stok', $kurang); $total += $subtotal;
            }
        }
        if (!$ada) { $transaksi->delete(); return back()->with('error', 'Pilih minimal 1 produk!'); }
        $transaksi->update(['total_harga' => $total + $ongkir]);
        return redirect()->route('pelanggan.riwayat')->with('success', 'Pesanan berhasil dibuat!');
    }

    public function riwayatPesanan()
    {
        $id_pelanggan = $this->getIdPelanggan();
        $pengaturan = Pengaturan::getSetting();
        $jml_transfer = Transaksi::where('id_pelanggan', $id_pelanggan)->where('metode_bayar', 'Transfer')->count();
        $jml_belum_bayar = Transaksi::where('id_pelanggan', $id_pelanggan)->whereNotIn('status_bayar', ['Sudah Bayar', 'Lunas'])->count();
        $total_transaksi = Transaksi::where('id_pelanggan', $id_pelanggan)->count();
        $transaksis = Transaksi::with('details.produk')->where('id_pelanggan', $id_pelanggan)->orderByDesc('id_transaksi')->get();
        return view('pelanggan.riwayat_pesanan', compact('pengaturan', 'jml_transfer', 'jml_belum_bayar', 'total_transaksi', 'transaksis'));
    }

    public function batalkanPesanan(Request $request)
    {
        $t = Transaksi::with('details.produk')->findOrFail($request->id_transaksi);
        if ($t->isLunas() || !empty($t->bukti_bayar)) {
            return back()->with('error', 'Pesanan tidak bisa dibatalkan!');
        }
        foreach ($t->details as $d) {
            if ($d->produk) { $d->produk->increment('stok', $d->produk->hitungPenguranganStok($d->jumlah)); }
        }
        $t->details()->delete(); $t->delete();
        return back()->with('success', 'Pesanan berhasil dibatalkan!');
    }

    public function uploadBukti(Request $request)
    {
        $request->validate(['bukti' => 'required|image|max:5120']);
        $t = Transaksi::findOrFail($request->id_transaksi);
        // Hapus file lama jika ada
        if ($t->bukti_bayar && file_exists(public_path('upload/bukti_bayar/' . $t->bukti_bayar))) {
            unlink(public_path('upload/bukti_bayar/' . $t->bukti_bayar));
        }
        $nama = 'BUKTI_' . $t->id_transaksi . '_' . time() . '.' . $request->bukti->extension();
        $request->bukti->move(public_path('upload/bukti_bayar'), $nama);
        $t->update(['bukti_bayar' => $nama]);
        return back()->with('success', 'Bukti berhasil dikirim!');
    }

    public function profil()
    {
        $user = User::leftJoin('pelanggan', 'users.id_pelanggan', '=', 'pelanggan.id_pelanggan')
            ->where('users.id_user', session('id_user'))
            ->select('users.*', 'pelanggan.kode_pelanggan')
            ->first();
        return view('pelanggan.profil', compact('user'));
    }

    public function updateProfil(Request $request)
    {
        $user = User::findOrFail(session('id_user'));
        $foto_nama = $user->foto;
        if ($request->hasFile('foto')) {
            if ($user->foto && file_exists(public_path('upload/user/' . $user->foto))) {
                unlink(public_path('upload/user/' . $user->foto));
            }
            $foto_nama = 'user_' . $user->id_user . '_' . time() . '.' . $request->foto->extension();
            $request->foto->move(public_path('upload/user'), $foto_nama);
        }
        $user->update(['nama' => $request->nama, 'no_telp' => $request->no_telp, 'alamat' => $request->alamat, 'foto' => $foto_nama]);
        if ($user->id_pelanggan) {
            Pelanggan::where('id_pelanggan', $user->id_pelanggan)->update(['nama' => $request->nama, 'no_hp' => $request->no_telp, 'alamat' => $request->alamat]);
        }
        session(['nama' => $request->nama]);
        return back()->with('success', 'Profil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate(['pass_baru' => 'required|min:3', 'konfirmasi' => 'required|same:pass_baru']);
        User::where('id_user', session('id_user'))->update(['password' => $request->pass_baru]);
        return back()->with('success', 'Password berhasil diganti!');
    }
}
