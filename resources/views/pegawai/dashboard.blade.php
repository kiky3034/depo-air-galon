@extends('layouts.pegawai')
@section('title', 'Dashboard Pegawai')
@section('content')
<div class="content">
    <div class="stat-wrapper mb-4">
        <div class="stat-card"><div class="icon-circle bg-blue-light"><i class="bi bi-people"></i></div><b>{{ $total_pelanggan }}</b><small>Pelanggan</small><a href="{{ route('pegawai.pelanggan') }}" class="btn btn-sm btn-primary mt-2 w-100 shadow-sm">Lihat</a></div>
        <div class="stat-card"><div class="icon-circle bg-green-light"><i class="bi bi-cart-check"></i></div><b>{{ $total_transaksi }}</b><small>Transaksi</small><a href="{{ route('pegawai.transaksi') }}" class="btn btn-sm btn-success text-white mt-2 w-100 shadow-sm">Lihat</a></div>
        <div class="stat-card"><div class="icon-circle bg-orange-light"><i class="bi bi-box-seam"></i></div><b>{{ $total_produk }}</b><small>Produk</small><a href="{{ route('pegawai.produk') }}" class="btn btn-sm btn-warning text-white mt-2 w-100 shadow-sm">Lihat</a></div>
    </div>
    <div class="row mt-3 g-3">
        <div class="col-md-4"><div class="card-custom"><div class="icon-circle bg-blue-light"><i class="bi bi-person-plus-fill"></i></div><h5>Tambah Pelanggan</h5><p class="text-muted small">Daftarkan pelanggan baru</p><a href="{{ route('pegawai.pelanggan.tambah') }}" class="btn btn-primary shadow-sm">Input Data</a></div></div>
        <div class="col-md-4"><div class="card-custom"><div class="icon-circle bg-green-light"><i class="bi bi-receipt-cutoff"></i></div><h5>Input Transaksi</h5><p class="text-muted small">Buat pesanan baru</p><a href="{{ route('pegawai.transaksi.tambah') }}" class="btn btn-success text-white shadow-sm">Buat Pesanan</a></div></div>
        <div class="col-md-4"><div class="card-custom"><div class="icon-circle bg-orange-light"><i class="bi bi-boxes"></i></div><h5>Kelola Produk</h5><p class="text-muted small">Stok dan harga air galon</p><a href="{{ route('pegawai.produk') }}" class="btn btn-warning text-white shadow-sm">Lihat Produk</a></div></div>
    </div>
    <div class="table-box mt-4">
        <div class="table-header"><i class="bi bi-clock-history me-2"></i>10 Transaksi Terakhir</div>
        <div class="p-3 table-responsive">
            <table class="table table-hover align-middle text-center mb-0" style="font-size:13px">
                <thead class="table-light"><tr><th width="80">ID</th><th class="text-start">Pelanggan</th><th>Tanggal</th><th>Total</th><th>Status</th></tr></thead>
                <tbody>@foreach($transaksi_terbaru as $t)
                @php $lunas = in_array($t->status_bayar, ['Sudah Bayar','Lunas']); @endphp
                <tr><td><span class="badge bg-secondary-subtle text-secondary border">{{ $t->id_transaksi }}</span></td><td class="text-start">{{ $t->pelanggan->nama ?? '-' }}</td><td>{{ $t->tanggal->format('d/m/Y') }}</td><td><span class="{{ $lunas?'text-success':'text-danger' }} fw-bold">Rp {{ number_format($t->total_harga) }}</span></td><td><span class="badge {{ $lunas?'bg-success':'bg-danger' }} px-2 py-1" style="font-size:10px">{{ strtoupper($t->status_bayar) }}</span></td></tr>
                @endforeach</tbody>
            </table>
        </div>
    </div>
</div>
@endsection
