@extends('layouts.pemilik')
@section('title', 'Data Pelanggan')
@section('content')
<div class="content">
    <div class="stat-wrapper mb-3">
        <div class="stat-card"><div class="icon-circle bg-blue-light"><i class="bi bi-people"></i></div><b>{{ $total_pelanggan }}</b><small>Total Pelanggan</small></div>
        <div class="stat-card"><div class="icon-circle bg-green-light"><i class="bi bi-cart-check"></i></div><b>{{ $total_transaksi }}</b><small>Total Transaksi</small></div>
    </div>
    <div class="d-flex justify-content-between align-items-center mt-4">
        <a href="{{ route('pemilik.dashboard') }}" class="btn btn-outline-primary btn-sm shadow-sm px-3" style="border-radius:10px"><i class="bi bi-arrow-left me-1"></i>Kembali Dashboard</a>
    </div>
    <div class="table-box mt-3">
        <div class="table-header"><i class="bi bi-people-fill me-2"></i>Daftar Pelanggan Tirta Kencana</div>
        <div class="p-3 table-responsive">
            <table class="table table-hover align-middle" style="font-size:13px">
                <thead class="table-light text-uppercase" style="font-size:11px"><tr><th width="50">No</th><th>Kode</th><th>Nama</th><th>Username</th><th>No HP</th><th>Alamat</th><th width="150" class="text-center">Aksi</th></tr></thead>
                <tbody>@foreach($pelanggans as $i => $row)
                <tr><td>{{ $i+1 }}</td><td><span class="badge bg-light text-primary border">{{ $row->kode_pelanggan }}</span></td><td>{{ $row->nama }}</td><td><code class="text-dark">{{ $row->username ?? '-' }}</code></td><td>{{ $row->no_hp }}</td><td><small class="text-muted">{{ $row->alamat }}</small></td>
                <td><div class="d-flex gap-1 justify-content-center"><a href="{{ route('pemilik.pelanggan.riwayat', $row->id_pelanggan) }}" class="btn btn-info btn-sm text-white" style="border-radius:8px"><i class="bi bi-eye"></i> Lihat</a></div></td></tr>@endforeach</tbody>
            </table>
        </div>
    </div>
</div>
@endsection
