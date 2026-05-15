@extends('layouts.pegawai')
@section('title', 'Data Transaksi')
@push('styles')
<style>
.card-stat{border:none;border-radius:12px;padding:18px;background:#fff;box-shadow:0 2px 10px rgba(0,0,0,.05);display:flex;align-items:center;gap:15px;height:100%}
.stat-icon-box{width:50px;height:50px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:24px}
.bg-trans-primary{background:rgba(25,118,210,.1);color:#1976d2}.bg-trans-danger{background:rgba(220,53,69,.1);color:#dc3545}.bg-trans-success{background:rgba(25,135,84,.1);color:#198754}
.table-custom{font-size:12.5px!important}.table-custom th{background:#f1f3f5;color:#444;padding:10px 8px!important;font-size:11px;text-transform:uppercase;white-space:nowrap}.table-custom td{padding:8px!important;vertical-align:middle}
.badge-custom{font-size:10px!important;padding:4px 10px;border-radius:5px;font-weight:600}
.produk-container{display:flex;flex-direction:column;gap:4px}.produk-row{border-left:3px solid #1976d2;background:#f8fbff;padding:4px 8px;border-radius:4px}.produk-nama{font-weight:700;font-size:12px}.produk-harga{font-size:10.5px;color:#6c757d}
.pagination-wrapper{padding:12px 18px;display:flex;justify-content:space-between;align-items:center;background:#f8f9fa;border-top:1px solid #eee}
</style>
@endpush
@section('content')
<div class="content">
    <div class="row g-3 mb-4">
        <div class="col-md-4"><div class="card-stat"><div class="stat-icon-box bg-trans-primary"><i class="bi bi-cart"></i></div><div><small class="text-muted fw-bold">TOTAL TRANSAKSI</small><h4 class="mb-0 fw-bold">{{ $total_t }}</h4></div></div></div>
        <div class="col-md-4"><div class="card-stat"><div class="stat-icon-box bg-trans-danger"><i class="bi bi-person-exclamation"></i></div><div><small class="text-muted fw-bold text-danger">BELUM BAYAR</small><h4 class="mb-0 fw-bold text-danger">{{ $total_belum_bayar }}</h4></div></div></div>
        <div class="col-md-4"><div class="card-stat"><div class="stat-icon-box bg-trans-success"><i class="bi bi-cash-stack"></i></div><div><small class="text-muted fw-bold text-success">PENDAPATAN</small><h4 class="mb-0 fw-bold text-success">Rp {{ number_format($nominal_lunas) }}</h4></div></div></div>
    </div>
    <div class="row g-2 mb-3 align-items-center">
        <div class="col-auto"><a href="{{ route('pegawai.dashboard') }}" class="btn btn-outline-primary px-3 shadow-sm" style="border-radius:8px"><i class="bi bi-arrow-left me-1"></i>Dashboard</a></div>
        <div class="col-auto"><a href="{{ route('pegawai.transaksi.tambah') }}" class="btn btn-primary px-3 shadow-sm" style="border-radius:8px"><i class="bi bi-plus-lg me-1"></i>Tambah</a></div>
        <div class="col-md-3 ms-auto">
            <form method="GET" id="searchForm"><div class="input-group shadow-sm"><span class="input-group-text bg-white border-end-0" style="border-radius:8px 0 0 8px"><i class="bi bi-search text-muted"></i></span><input type="text" name="cari" id="searchInput" class="form-control border-start-0" placeholder="Cari..." value="{{ $cari }}" style="border-radius:0 8px 8px 0"></div></form>
        </div>
    </div>
    <div class="table-box">
        <div class="table-header" style="background:#1565c0"><i class="bi bi-receipt me-2"></i>Riwayat Transaksi</div>
        <div class="table-responsive">
            <table class="table table-hover align-middle text-center table-custom mb-0">
                <thead><tr><th>No</th><th class="text-start">Pelanggan</th><th>Tanggal</th><th class="text-start">Produk</th><th>Ongkir</th><th>Total</th><th>Bayar</th><th>Antar</th><th>Aksi</th></tr></thead>
                <tbody>@foreach($transaksis as $i => $t)
                <tr>
                    <td>{{ $transaksis->firstItem()+$i }}</td>
                    <td class="text-start"><b>{{ $t->pelanggan->nama ?? '-' }}</b><br><small class="text-muted" style="font-size:10px">{{ $t->pelanggan->kode_pelanggan ?? '' }}</small></td>
                    <td><small class="fw-bold">{{ $t->tanggal->format('d/m/Y') }}</small></td>
                    <td class="text-start" style="min-width:180px"><div class="produk-container">@foreach($t->details as $d)<div class="produk-row"><span class="produk-nama">{{ $d->produk->jenis_air ?? '-' }} ({{ $d->jumlah }})</span><span class="produk-harga">Rp {{ number_format($d->harga_satuan) }}</span></div>@endforeach</div></td>
                    <td class="fw-bold">{{ $t->ongkos_kirim>0?'Rp '.number_format($t->ongkos_kirim):'-' }}</td>
                    <td><b class="{{ $t->isLunas()?'text-success':'text-danger' }}">Rp {{ number_format($t->total_harga) }}</b></td>
                    <td><form method="POST" action="{{ route('pegawai.transaksi.status', $t->id_transaksi) }}">@csrf @method('PUT')<select name="status_bayar" class="form-select form-select-sm" style="font-size:10px;min-width:100px" onchange="this.form.submit()"><option value="Belum Bayar" {{ $t->status_bayar=='Belum Bayar'?'selected':'' }}>Belum</option><option value="Sudah Bayar" {{ $t->status_bayar=='Sudah Bayar'?'selected':'' }}>Sudah</option></select></form></td>
                    <td>@if($t->ongkos_kirim<=0)<span class="badge bg-light text-muted border badge-custom">Ambil</span>@else<form method="POST" action="{{ route('pegawai.transaksi.status', $t->id_transaksi) }}">@csrf @method('PUT')<select name="status_antar" class="form-select form-select-sm" style="font-size:10px;min-width:90px" onchange="this.form.submit()"><option value="Belum" {{ $t->status_antar=='Belum'?'selected':'' }}>Belum</option><option value="Selesai" {{ in_array($t->status_antar,['Selesai','Sudah'])?'selected':'' }}>Selesai</option></select></form>@endif</td>
                    <td><div class="d-flex gap-1"><a href="{{ route('pegawai.transaksi.edit', $t->id_transaksi) }}" class="btn btn-warning btn-sm text-white" style="border-radius:6px"><i class="bi bi-pencil"></i></a><form method="POST" action="{{ route('pegawai.transaksi.delete', $t->id_transaksi) }}" onsubmit="return confirm('Yakin?')">@csrf @method('DELETE')<button class="btn btn-danger btn-sm" style="border-radius:6px"><i class="bi bi-trash"></i></button></form></div></td>
                </tr>@endforeach</tbody>
            </table>
        </div>
        <div class="pagination-wrapper"><div class="small text-muted fw-bold">Total: {{ $transaksis->total() }}</div>{{ $transaksis->links() }}</div>
    </div>
</div>
@endsection


