<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Pengaturan;
use App\Models\User;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    public function dashboard()
    {
        $total_pelanggan = Pelanggan::count();
        $total_transaksi = Transaksi::count();
        $total_produk = Produk::count();
        $transaksi_terbaru = Transaksi::with('pelanggan')->orderByDesc('id_transaksi')->limit(10)->get();
        return view('pegawai.dashboard', compact('total_pelanggan', 'total_transaksi', 'total_produk', 'transaksi_terbaru'));
    }

    public function dataPelanggan()
    {
        $pelanggans = Pelanggan::leftJoin('users', 'pelanggan.id_pelanggan', '=', 'users.id_pelanggan')
            ->select('pelanggan.*', 'users.username')->orderByDesc('pelanggan.id_pelanggan')->get();
        $total_pelanggan = Pelanggan::count();
        $total_transaksi = Transaksi::count();
        return view('pegawai.data_pelanggan', compact('pelanggans', 'total_pelanggan', 'total_transaksi'));
    }

    public function tambahPelanggan() { return view('pegawai.tambah_pelanggan'); }

    public function storePelanggan(Request $request)
    {
        $request->validate(['nama' => 'required']);
        $count = Pelanggan::count();
        $id_baru = $count == 0 ? 1 : Pelanggan::max('id_pelanggan') + 1;
        $kode = 'PLG' . $id_baru;
        Pelanggan::create(['kode_pelanggan' => $kode, 'nama' => $request->nama, 'no_hp' => $request->no_hp, 'alamat' => $request->alamat]);
        return redirect()->route('pegawai.pelanggan.tambah')->with('success', "Pelanggan ditambahkan! Kode: $kode")->with('kode', $kode);
    }

    public function editPelanggan($id) { return view('pegawai.edit_pelanggan', ['pelanggan' => Pelanggan::findOrFail($id)]); }

    public function updatePelanggan(Request $request, $id)
    {
        Pelanggan::findOrFail($id)->update($request->only('nama', 'no_hp', 'alamat'));
        return redirect()->route('pegawai.pelanggan')->with('success', 'Data pelanggan diperbarui!');
    }

    public function deletePelanggan($id)
    {
        $p = Pelanggan::findOrFail($id);
        if ($p->transaksis()->count() > 0) return back()->with('error', 'Gagal! Pelanggan punya riwayat transaksi.');
        User::where('id_pelanggan', $id)->delete();
        $p->delete();
        return back()->with('success', 'Pelanggan dan akun login dihapus!');
    }

    public function riwayatPesananPelanggan($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $total_t = Transaksi::where('id_pelanggan', $id)->count();
        $total_belum_bayar = Transaksi::where('id_pelanggan', $id)->where('status_bayar', 'Belum Bayar')->count();
        $transaksis = Transaksi::with('details.produk')->where('id_pelanggan', $id)->orderByDesc('id_transaksi')->get();
        return view('pegawai.riwayat_pesanan_pelanggan', compact('pelanggan', 'total_t', 'total_belum_bayar', 'transaksis'));
    }

    public function dataTransaksi(Request $request)
    {
        $cari = $request->get('cari', '');
        $query = Transaksi::with(['pelanggan', 'details.produk']);
        if ($cari) {
            $query->whereHas('pelanggan', fn($q) => $q->where('nama', 'like', "%$cari%")->orWhere('kode_pelanggan', 'like', "%$cari%"));
        }
        $transaksis = $query->orderByDesc('id_transaksi')->paginate(10)->appends(['cari' => $cari]);
        $total_t = Transaksi::count();
        $total_belum_bayar = Transaksi::where('status_bayar', 'Belum Bayar')->count();
        $nominal_lunas = Transaksi::where('status_bayar', 'Sudah Bayar')->sum('total_harga');
        $pengaturan = Pengaturan::getSetting();
        return view('pegawai.data_transaksi', compact('transaksis', 'cari', 'total_t', 'total_belum_bayar', 'nominal_lunas', 'pengaturan'));
    }

    public function tambahPesanan()
    {
        $pengaturan = Pengaturan::getSetting();
        $produks = Produk::orderBy('jenis_air')->get();
        $pelanggans = Pelanggan::orderBy('nama')->get();
        return view('pegawai.tambah_pesanan', compact('pengaturan', 'produks', 'pelanggans'));
    }

    public function storePesanan(Request $request)
    {
        $ongkir = ($request->metode == 'antar') ? ($request->ongkos_kirim ?? 0) : 0;
        $status_antar = ($request->metode == 'antar') ? 'Belum' : 'Datang Sendiri';
        $transaksi = Transaksi::create([
            'id_pelanggan' => $request->id_pelanggan, 'id_user' => session('id_user'),
            'tanggal' => now()->toDateString(), 'ongkos_kirim' => $ongkir, 'total_harga' => 0,
            'status_antar' => $status_antar, 'status_bayar' => 'Belum Bayar', 'metode_bayar' => $request->metode_bayar ?? 'Cash',
        ]);
        $total_produk = 0; $item_dipilih = false;
        foreach ($request->produk as $i => $id_produk) {
            $jumlah = $request->jumlah[$i] ?? 0;
            if ($jumlah > 0) {
                $item_dipilih = true;
                $produk = Produk::find($id_produk);
                $kurang = $produk->hitungPenguranganStok($jumlah);
                if ($produk->stok < $kurang) { $transaksi->delete(); return back()->with('error', "Stok {$produk->jenis_air} tidak cukup!"); }
                $subtotal = $produk->harga * $jumlah;
                DetailTransaksi::create(['id_transaksi' => $transaksi->id_transaksi, 'id_produk' => $id_produk, 'jumlah' => $jumlah, 'harga_satuan' => $produk->harga, 'subtotal' => $subtotal]);
                $produk->decrement('stok', $kurang);
                $total_produk += $subtotal;
            }
        }
        if (!$item_dipilih) { $transaksi->delete(); return back()->with('error', 'Pilih minimal 1 produk!'); }
        $transaksi->update(['total_harga' => $total_produk + $ongkir]);
        return redirect()->route('pegawai.transaksi')->with('success', 'Transaksi disimpan!');
    }

    public function updateStatusTransaksi(Request $request, $id)
    {
        $t = Transaksi::findOrFail($id);
        if ($request->has('status_bayar')) $t->status_bayar = $request->status_bayar;
        if ($request->has('status_antar')) $t->status_antar = $request->status_antar;
        $t->save();
        return back()->with('success', 'Status diperbarui!');
    }

    public function deleteTransaksi($id)
    {
        $t = Transaksi::with('details.produk')->findOrFail($id);
        foreach ($t->details as $d) { if ($d->produk) { $d->produk->increment('stok', $d->produk->hitungPenguranganStok($d->jumlah)); } }
        $t->details()->delete(); $t->delete();
        return back()->with('success', 'Transaksi dihapus & stok dikembalikan!');
    }

    public function editTransaksi($id)
    {
        $transaksi = Transaksi::with('details')->findOrFail($id);
        $pengaturan = Pengaturan::getSetting(); $produks = Produk::orderBy('jenis_air')->get(); $pelanggans = Pelanggan::orderBy('nama')->get();
        $detail_lama = []; foreach ($transaksi->details as $d) { $detail_lama[$d->id_produk] = $d->jumlah; }
        return view('pegawai.edit_transaksi', compact('transaksi', 'pengaturan', 'produks', 'pelanggans', 'detail_lama'));
    }

    public function updateTransaksi(Request $request, $id)
    {
        $t = Transaksi::with('details.produk')->findOrFail($id);
        foreach ($t->details as $d) { if ($d->produk) { $d->produk->increment('stok', $d->produk->hitungPenguranganStok($d->jumlah)); } }
        $t->details()->delete();
        $ongkir = ($request->metode == 'antar') ? ($request->ongkos_kirim ?? 0) : 0;
        $status_antar = ($request->metode == 'antar') ? ($request->status_antar ?? 'Belum') : '-';
        $total_produk = 0;
        foreach ($request->produk as $i => $id_produk) {
            $jumlah = $request->jumlah[$i] ?? 0;
            if ($jumlah > 0) {
                $produk = Produk::find($id_produk); $subtotal = $produk->harga * $jumlah;
                DetailTransaksi::create(['id_transaksi' => $id, 'id_produk' => $id_produk, 'jumlah' => $jumlah, 'harga_satuan' => $produk->harga, 'subtotal' => $subtotal]);
                $produk->decrement('stok', $produk->hitungPenguranganStok($jumlah)); $total_produk += $subtotal;
            }
        }
        $t->update(['id_pelanggan' => $request->id_pelanggan, 'ongkos_kirim' => $ongkir, 'total_harga' => $total_produk + $ongkir, 'status_antar' => $status_antar, 'metode_bayar' => $request->metode_bayar]);
        return redirect()->route('pegawai.transaksi')->with('success', 'Transaksi diperbarui!');
    }

    public function tampilProduk()
    {
        $produks = Produk::orderByDesc('id_produk')->get(); $total_produk = Produk::count();
        $stok_menipis = Produk::where(fn($q) => $q->where(fn($q2) => $q2->where('satuan_stok', 'pcs')->where('stok', '<', 8))->orWhere(fn($q2) => $q2->where('satuan_stok', 'liter')->where('stok', '<', 40)))->count();
        return view('pegawai.tampil_produk', compact('produks', 'total_produk', 'stok_menipis'));
    }

    public function tambahProduk() { return view('pegawai.tambah_produk'); }

    public function storeProduk(Request $request)
    {
        $request->validate(['jenis_air' => 'required', 'harga' => 'required|numeric|min:0', 'stok' => 'required|numeric|min:0']);
        if (Produk::where('jenis_air', $request->jenis_air)->where('jenis_layanan', $request->jenis_layanan)->exists()) return back()->with('error', 'Produk sudah ada!');
        Produk::create($request->only('jenis_air', 'satuan_stok', 'isi_per_unit', 'harga', 'stok', 'jenis_layanan'));
        return redirect()->route('pegawai.produk')->with('success', 'Produk ditambahkan!');
    }

    public function editProduk($id) { return view('pegawai.edit_produk', ['produk' => Produk::findOrFail($id)]); }

    public function updateProduk(Request $request, $id)
    {
        Produk::findOrFail($id)->update($request->only('jenis_air', 'satuan_stok', 'isi_per_unit', 'harga', 'stok', 'jenis_layanan'));
        return redirect()->route('pegawai.produk')->with('success', 'Produk diperbarui!');
    }

    public function deleteProduk($id)
    {
        $p = Produk::findOrFail($id);
        if ($p->detailTransaksis()->count() > 0) return back()->with('error', 'Gagal! Produk punya riwayat.');
        $p->delete(); return back()->with('success', 'Produk dihapus!');
    }
}
