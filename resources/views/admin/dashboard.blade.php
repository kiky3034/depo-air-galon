@extends('layouts.admin')
@section('title', 'Dashboard Admin')
@section('content')
<div class="content">
    <div class="stat-wrapper mb-4">
        <div class="stat-card"><div class="icon-circle bg-blue-light"><i class="bi bi-people"></i></div><b>{{ $total_user }}</b><small>Total User</small></div>
        <div class="stat-card"><div class="icon-circle bg-purple-light"><i class="bi bi-person-check"></i></div><b>{{ $total_pelanggan }}</b><small>Pelanggan</small></div>
        <div class="stat-card"><div class="icon-circle bg-green-light"><i class="bi bi-cart-check"></i></div><b>{{ $total_transaksi }}</b><small>Transaksi</small></div>
        <div class="stat-card"><div class="icon-circle bg-orange-light"><i class="bi bi-box-seam"></i></div><b>{{ $total_produk }}</b><small>Produk</small></div>
    </div>
    <div class="row mt-4">
        <div class="col-md-3 mb-3"><div class="card-custom"><div class="icon-circle bg-blue-light"><i class="bi bi-people-fill"></i></div><h5>Data User</h5><p class="text-muted small">Kelola semua akun pengguna</p><a href="{{ route('admin.user') }}" class="btn btn-primary shadow-sm">Buka Data</a></div></div>
        <div class="col-md-3 mb-3"><div class="card-custom"><div class="icon-circle bg-green-light"><i class="bi bi-receipt-cutoff"></i></div><h5>Transaksi</h5><p class="text-muted small">Kelola data pesanan</p><a href="{{ route('admin.transaksi') }}" class="btn btn-success text-white shadow-sm">Buka Transaksi</a></div></div>
        <div class="col-md-3 mb-3"><div class="card-custom"><div class="icon-circle bg-orange-light"><i class="bi bi-boxes"></i></div><h5>Data Produk</h5><p class="text-muted small">Stok & Harga Air Galon</p><a href="{{ route('admin.produk') }}" class="btn btn-warning text-white shadow-sm">Lihat Produk</a></div></div>
        <div class="col-md-3 mb-3"><div class="card-custom"><div class="icon-circle bg-purple-light"><i class="bi bi-gear-fill"></i></div><h5>Pengaturan</h5><p class="text-muted small">Identitas & Rekening</p><a href="{{ route('admin.pengaturan') }}" class="btn btn-secondary shadow-sm">Pengaturan</a></div></div>
    </div>
    <div class="table-box mt-3">
        <div class="table-header"><i class="bi bi-clock-history me-2"></i>5 Transaksi Terakhir</div>
        <div class="p-3 table-responsive">
            <table class="table table-hover align-middle text-center mb-0" style="font-size:13px">
                <thead class="table-light"><tr><th width="80">ID</th><th class="text-start">Pelanggan</th><th>Tanggal</th><th>Total</th><th>Status</th></tr></thead>
                <tbody>
                @foreach($transaksi_terbaru as $t)
                    @php $lunas = in_array($t->status_bayar, ['Sudah Bayar','Lunas']); @endphp
                    <tr>
                        <td><span class="badge bg-secondary-subtle text-secondary border">{{ $t->id_transaksi }}</span></td>
                        <td class="text-start">{{ $t->pelanggan->nama ?? '-' }}</td>
                        <td>{{ $t->tanggal->format('d/m/Y') }}</td>
                        <td><span class="{{ $lunas?'text-success':'text-danger' }} fw-bold">Rp {{ number_format($t->total_harga) }}</span></td>
                        <td><span class="badge {{ $lunas?'bg-success':'bg-danger' }} px-2 py-1" style="font-size:10px">{{ strtoupper($t->status_bayar) }}</span></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
