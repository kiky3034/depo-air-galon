<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\User;
use App\Models\Pengaturan;
use Illuminate\Http\Request;

class PemilikController extends Controller
{
    public function dashboard()
    {
        $total_pelanggan = Pelanggan::count();
        $total_transaksi = Transaksi::count();
        $total_pegawai = User::where('role', 'pegawai')->count();
        $total_pemasukan = Transaksi::whereIn('status_bayar', ['Sudah Bayar', 'Lunas'])->sum('total_harga');
        $transaksi_terbaru = Transaksi::with('pelanggan')->orderByDesc('id_transaksi')->limit(5)->get();
        return view('pemilik.dashboard', compact('total_pelanggan', 'total_transaksi', 'total_pegawai', 'total_pemasukan', 'transaksi_terbaru'));
    }

    public function dataPelanggan()
    {
        $pelanggans = Pelanggan::leftJoin('users', 'pelanggan.id_pelanggan', '=', 'users.id_pelanggan')
            ->select('pelanggan.*', 'users.username')->orderByDesc('pelanggan.id_pelanggan')->get();
        $total_pelanggan = Pelanggan::count();
        $total_transaksi = Transaksi::count();
        return view('pemilik.data_pelanggan', compact('pelanggans', 'total_pelanggan', 'total_transaksi'));
    }

    public function riwayatPesananPelanggan($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $total_t = Transaksi::where('id_pelanggan', $id)->count();
        $total_belum_bayar = Transaksi::where('id_pelanggan', $id)->where('status_bayar', 'Belum Bayar')->count();
        $transaksis = Transaksi::with('details.produk')->where('id_pelanggan', $id)->orderByDesc('id_transaksi')->get();
        return view('pemilik.riwayat_pesanan_pelanggan', compact('pelanggan', 'total_t', 'total_belum_bayar', 'transaksis'));
    }

    public function dataPegawai()
    {
        $pegawais = User::where('role', 'pegawai')->orderByDesc('id_user')->get();
        $total_pegawai = $pegawais->count();
        return view('pemilik.data_pegawai', compact('pegawais', 'total_pegawai'));
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
        return view('pemilik.data_transaksi', compact('transaksis', 'cari', 'total_t', 'total_belum_bayar', 'nominal_lunas'));
    }

    public function laporan(Request $request)
    {
        $pengaturan = Pengaturan::getSetting();
        $tgl_mulai = $request->get('tgl_mulai', now()->startOfMonth()->toDateString());
        $tgl_selesai = $request->get('tgl_selesai', now()->endOfMonth()->toDateString());
        $query = Transaksi::with('pelanggan')->whereBetween('tanggal', [$tgl_mulai, $tgl_selesai]);
        $jumlah_data = (clone $query)->count();
        $total_pendapatan = (clone $query)->where('status_bayar', 'Sudah Bayar')->sum('total_harga');
        $data_laporan = $query->orderByDesc('tanggal')->paginate(10)->appends(['tgl_mulai' => $tgl_mulai, 'tgl_selesai' => $tgl_selesai]);
        $label_periode = \Carbon\Carbon::parse($tgl_mulai)->format('d M Y') . ' s/d ' . \Carbon\Carbon::parse($tgl_selesai)->format('d M Y');
        return view('pemilik.laporan', compact('pengaturan', 'tgl_mulai', 'tgl_selesai', 'jumlah_data', 'total_pendapatan', 'data_laporan', 'label_periode'));
    }
}
