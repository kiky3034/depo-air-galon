@extends('layouts.admin')
@section('title', 'Data Produk')
@push('styles')
<style>
.card-stat{border:none;border-radius:15px;padding:15px;background:#fff;box-shadow:0 4px 10px rgba(0,0,0,.05);display:flex;align-items:center;gap:12px;height:100%}
.stat-icon-box{width:45px;height:45px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0}
.bg-trans-primary{background:rgba(25,118,210,.1);color:#1976d2}.bg-trans-warning{background:rgba(255,193,7,.1);color:#ffc107}
.text-danger-custom{color:#dc3545!important;font-weight:bold}.blink{animation:blinker 1.5s linear infinite}@keyframes blinker{50%{opacity:.5}}
</style>
@endpush
@section('content')
<div class="content">

    <div class="row g-3 mb-4">
        <div class="col-md-6 col-6"><div class="card-stat"><div class="stat-icon-box bg-trans-primary"><i class="bi bi-box-seam"></i></div><div><small class="text-muted fw-bold">Total Jenis Produk</small><h4 class="mb-0 fw-bold">{{ $total_produk }}</h4></div></div></div>
        <div class="col-md-6 col-6"><div class="card-stat"><div class="stat-icon-box bg-trans-warning"><i class="bi bi-exclamation-triangle"></i></div><div><small class="text-muted fw-bold">Stok Menipis</small><h4 class="mb-0 fw-bold {{ $stok_menipis > 0 ? 'text-danger blink' : '' }}">{{ $stok_menipis }}</h4></div></div></div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary btn-sm shadow-sm px-3" style="border-radius:10px"><i class="bi bi-arrow-left me-1"></i>Dashboard</a>
        <a href="{{ route('admin.produk.tambah') }}" class="btn btn-primary btn-sm shadow-sm px-3" style="border-radius:10px"><i class="bi bi-plus-lg me-1"></i>Tambah Produk</a>
    </div>

    <div class="table-box">
        <div class="table-header"><i class="bi bi-box-seam-fill me-2"></i>Daftar Persediaan Produk</div>
        <div class="p-3 table-responsive">
            <table class="table table-hover align-middle text-center" style="font-size:13px">
                <thead class="table-light"><tr style="text-transform:uppercase;font-size:11px;color:#666"><th>No</th><th style="text-align:center">Jenis Air</th><th>Layanan</th><th>Harga</th><th>Stok</th><th>Detail</th><th>Aksi</th></tr></thead>
                <tbody>
                @foreach($produks as $i => $row)
                @php $menipis = $row->isStokMenipis(); @endphp
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td style="text-align:center">{{ $row->jenis_air }}</td>
                    <td><span class="badge {{ $row->jenis_layanan=='isi_ulang'?'bg-info-subtle text-info':'bg-primary-subtle text-primary' }} border" style="font-size:10px">{{ $row->jenis_layanan=='isi_ulang'?'ISI ULANG':'GALON BARU' }}</span></td>
                    <td><span class="badge bg-white border text-dark p-1 px-2 fw-bold">Rp {{ number_format($row->harga) }}</span></td>
                    <td><div class="{{ $menipis?'text-danger-custom':'' }}">@if($menipis)<i class="bi bi-exclamation-circle-fill me-1"></i>@endif<span style="font-size:15px">{{ $row->stok }}</span><small class="text-muted d-block" style="font-size:10px">{{ $row->satuan_stok }}</small></div></td>
                    <td><small class="text-muted">{{ $row->isi_per_unit }} L</small></td>
                    <td><div class="d-flex gap-1 justify-content-center">
                        <a href="{{ route('admin.produk.edit', $row->id_produk) }}" class="btn btn-warning btn-sm text-white shadow-sm" style="border-radius:8px"><i class="bi bi-pencil-square"></i></a>
                        <form method="POST" action="{{ route('admin.produk.delete', $row->id_produk) }}" onsubmit="return confirm('Yakin hapus?')">@csrf @method('DELETE')<button class="btn btn-danger btn-sm shadow-sm" style="border-radius:8px"><i class="bi bi-trash"></i></button></form>
                    </div></td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


