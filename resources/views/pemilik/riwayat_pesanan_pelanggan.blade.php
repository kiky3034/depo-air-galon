@extends('layouts.pemilik')
@section('title', 'Riwayat Pesanan Pelanggan')
@section('content')
<div class="content">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold text-primary mb-0"><i class="bi bi-clock-history me-2"></i>Riwayat: {{ $pelanggan->nama }}</h5>
        <a href="{{ route('pemilik.pelanggan') }}" class="btn btn-outline-primary btn-sm px-3" style="border-radius:10px"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
    </div>
    <div class="stat-wrapper mb-3">
        <div class="stat-card"><div class="icon-circle bg-blue-light"><i class="bi bi-receipt"></i></div><b>{{ $total_t }}</b><small>Total Transaksi</small></div>
        <div class="stat-card"><div class="icon-circle bg-orange-light"><i class="bi bi-exclamation-circle"></i></div><b class="{{ $total_belum_bayar>0?'text-danger':'' }}">{{ $total_belum_bayar }}</b><small>Belum Bayar</small></div>
    </div>
    <div class="table-box">
        <div class="table-header"><i class="bi bi-list-check me-2"></i>Detail Pesanan {{ $pelanggan->kode_pelanggan }}</div>
        <div class="p-3 table-responsive">
            <table class="table table-hover align-middle text-center" style="font-size:13px">
                <thead class="table-light"><tr><th>No</th><th>Tanggal</th><th class="text-start">Produk</th><th>Ongkir</th><th>Total</th><th>Status Bayar</th><th>Status Antar</th></tr></thead>
                <tbody>@forelse($transaksis as $i => $t)
                <tr><td>{{ $i+1 }}</td><td>{{ $t->tanggal->format('d/m/Y') }}</td>
                <td class="text-start">@foreach($t->details as $d)<div class="mb-1"><span class="fw-bold" style="font-size:12px">{{ $d->produk->jenis_air ?? '-' }}</span> x{{ $d->jumlah }} <small class="text-muted">@ Rp {{ number_format($d->harga_satuan) }}</small></div>@endforeach</td>
                <td>{{ $t->ongkos_kirim>0?'Rp '.number_format($t->ongkos_kirim):'-' }}</td>
                <td class="fw-bold {{ $t->isLunas()?'text-success':'text-danger' }}">Rp {{ number_format($t->total_harga) }}</td>
                <td><span class="badge {{ $t->isLunas()?'bg-success':'bg-danger' }}">{{ $t->status_bayar }}</span></td>
                <td>@if($t->status_antar=='Sudah'||$t->status_antar=='Selesai')<span class="badge bg-success-subtle text-success border">Sudah</span>@elseif($t->ongkos_kirim<=0)<span class="badge bg-light text-muted border">Ambil Sendiri</span>@else<span class="badge bg-warning text-dark">Belum</span>@endif</td></tr>
                @empty<tr><td colspan="7" class="text-muted py-4">Belum ada transaksi.</td></tr>@endforelse</tbody>
            </table>
        </div>
    </div>
</div>
@endsection
