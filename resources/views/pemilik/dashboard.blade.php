@extends('layouts.pemilik')
@section('title', 'Dashboard Pemilik')
@section('content')
<div class="content">
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3"><div class="stat-card"><div class="icon-circle bg-blue-light"><i class="bi bi-people"></i></div><b>{{ $total_pelanggan }}</b><small>Pelanggan</small><a href="{{ route('pemilik.pelanggan') }}" class="btn btn-sm btn-primary mt-2 w-100 shadow-sm">Lihat</a></div></div>
        <div class="col-6 col-md-3"><div class="stat-card"><div class="icon-circle bg-orange-light"><i class="bi bi-person-badge"></i></div><b>{{ $total_pegawai }}</b><small>Pegawai</small><a href="{{ route('pemilik.pegawai') }}" class="btn btn-sm btn-warning text-white mt-2 w-100 shadow-sm">Lihat</a></div></div>
        <div class="col-6 col-md-3"><div class="stat-card"><div class="icon-circle bg-green-light"><i class="bi bi-cart-check"></i></div><b>{{ $total_transaksi }}</b><small>Transaksi</small><a href="{{ route('pemilik.transaksi') }}" class="btn btn-sm btn-success text-white mt-2 w-100 shadow-sm">Lihat</a></div></div>
        <div class="col-6 col-md-3"><div class="stat-card"><div class="icon-circle bg-red-light"><i class="bi bi-cash-stack"></i></div><b>Rp {{ number_format($total_pemasukan) }}</b><small>Pemasukan</small><a href="{{ route('pemilik.laporan') }}" class="btn btn-sm btn-danger text-white mt-2 w-100 shadow-sm">Laporan</a></div></div>
    </div>
    <div class="table-box">
        <div class="table-header"><i class="bi bi-clock-history me-2"></i>5 Transaksi Terakhir</div>
        <div class="p-3 table-responsive">
            <table class="table table-hover align-middle text-center mb-0" style="font-size:13px">
                <thead class="table-light"><tr><th width="80">ID</th><th class="text-start">Nama Pelanggan</th><th>Tanggal</th><th>Total Bayar</th><th>Status</th></tr></thead>
                <tbody>@foreach($transaksi_terbaru as $t)
                @php $lunas = in_array($t->status_bayar, ['Sudah Bayar','Lunas']); @endphp
                <tr><td><span class="badge bg-secondary-subtle text-secondary border">{{ $t->id_transaksi }}</span></td><td class="text-start">{{ $t->pelanggan->nama ?? '-' }}</td><td>{{ $t->tanggal->format('d/m/Y') }}</td><td><span class="{{ $lunas?'text-success':'text-danger' }} fw-bold">Rp {{ number_format($t->total_harga) }}</span></td><td><span class="badge {{ $lunas?'bg-success':'bg-danger' }} px-2 py-1" style="font-size:10px">{{ strtoupper($t->status_bayar) }}</span></td></tr>
                @endforeach</tbody>
            </table>
        </div>
    </div>
</div>
@endsection
