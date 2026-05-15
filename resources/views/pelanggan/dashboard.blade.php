@extends('layouts.pelanggan')
@section('title', 'Dashboard Pelanggan')
@section('content')
<div class="content">
    <div class="row g-3 mb-4">
        <div class="col-6"><div class="stat-card"><div class="icon-circle bg-blue-light"><i class="bi bi-cart-check"></i></div><b>{{ $total_pesanan }}</b><small>Total Pesanan</small></div></div>
        <div class="col-6"><div class="stat-card"><div class="icon-circle bg-orange-light"><i class="bi bi-truck"></i></div><b>{{ $sedang_diantar }}</b><small>Sedang Diantar</small></div></div>
    </div>
    <div class="row g-3 mb-4">
        <div class="col-md-6"><div class="card-custom" style="border-radius:18px;padding:25px;background:#fff;box-shadow:0 4px 10px rgba(0,0,0,.05)"><div class="icon-circle bg-green-light" style="margin:0 auto 15px;width:60px;height:60px;font-size:30px"><i class="bi bi-cart-plus"></i></div><h5 class="fw-bold">Buat Pesanan Baru</h5><p class="text-muted small">Pesan air galon langsung</p><a href="{{ route('pelanggan.pesanan.buat') }}" class="btn btn-primary w-100 fw-bold mt-2 shadow-sm" style="border-radius:10px">Pesan Sekarang</a></div></div>
        <div class="col-md-6"><div class="card-custom" style="border-radius:18px;padding:25px;background:#fff;box-shadow:0 4px 10px rgba(0,0,0,.05)"><div class="icon-circle bg-blue-light" style="margin:0 auto 15px;width:60px;height:60px;font-size:30px"><i class="bi bi-clock-history"></i></div><h5 class="fw-bold">Riwayat Pesanan</h5><p class="text-muted small">Lihat semua pesanan Anda</p><a href="{{ route('pelanggan.riwayat') }}" class="btn btn-info text-white w-100 fw-bold mt-2 shadow-sm" style="border-radius:10px">Lihat Riwayat</a></div></div>
    </div>
    @if($produks->count() > 0)
    <div class="table-box">
        <div class="table-header"><i class="bi bi-box-seam me-2"></i>Produk Tersedia</div>
        <div class="p-3 table-responsive">
            <table class="table table-hover align-middle text-center" style="font-size:13px">
                <thead class="table-light"><tr><th>No</th><th>Jenis Air</th><th>Layanan</th><th>Harga</th><th>Stok</th></tr></thead>
                <tbody>@foreach($produks as $i => $p)
                <tr><td>{{ $i+1 }}</td><td>{{ $p->jenis_air }}</td><td><span class="badge {{ $p->jenis_layanan=='isi_ulang'?'bg-success':'bg-primary' }}" style="font-size:10px">{{ $p->jenis_layanan=='isi_ulang'?'Isi Ulang':'Galon Baru' }}</span></td><td class="fw-bold text-primary">Rp {{ number_format($p->harga) }}</td><td>{{ $p->stok }} {{ $p->satuan_stok }}</td></tr>
                @endforeach</tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
