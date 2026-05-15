@extends('layouts.pemilik')
@section('title', 'Data Pegawai')
@push('styles')
<style>
.stat-card-simple{background:#fff;border-radius:15px;padding:20px;display:flex;align-items:center;gap:15px;box-shadow:0 4px 10px rgba(0,0,0,.05);max-width:300px}
.foto{width:45px;height:45px;border-radius:50%;object-fit:cover;border:1px solid #ddd}
.badge-user{background:#e3f2fd;color:#1976d2;padding:5px 10px;border-radius:8px;font-size:13px}
.badge-pass{background:#f8f9fa;color:#666;padding:5px 10px;border-radius:8px;border:1px dashed #ddd;font-family:monospace}
</style>
@endpush
@section('content')
<div class="content">
    <div class="stat-card-simple mb-4"><div class="icon-circle bg-orange-light mb-0"><i class="bi bi-person-badge"></i></div><div><small class="text-muted d-block">Total Pegawai</small><b class="fs-4">{{ $total_pegawai }}</b></div></div>
    <div class="table-box">
        <div class="table-header" style="background:linear-gradient(90deg,#1976d2,#42a5f5)"><i class="bi bi-person-lines-fill me-2"></i>Data Informasi Pegawai</div>
        <div class="p-3 table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light"><tr><th width="50">No</th><th>Foto</th><th>Nama</th><th>Username</th><th>Password</th><th>No Telp</th><th>Alamat</th></tr></thead>
                <tbody>@foreach($pegawais as $i => $row)
                <tr><td>{{ $i+1 }}</td><td><img src="{{ $row->foto ? asset('upload/user/'.$row->foto) : 'https://ui-avatars.com/api/?name='.urlencode($row->nama).'&background=1976d2&color=fff&size=45' }}" class="foto shadow-sm"></td><td>{{ $row->nama }}</td><td><span class="badge-user">{{ $row->username }}</span></td><td><span class="badge-pass">{{ $row->password }}</span></td><td class="text-success">{{ $row->no_telp ?: '-' }}</td><td class="text-muted small">{{ $row->alamat ?: '-' }}</td></tr>
                @endforeach</tbody>
            </table>
        </div>
    </div>
    <div class="mt-4"><a href="{{ route('pemilik.dashboard') }}" class="btn btn-primary shadow-sm" style="border-radius:10px;padding:10px 25px;background:#1976d2;border:none"><i class="bi bi-arrow-left-circle me-1"></i>Kembali ke Dashboard</a></div>
</div>
@endsection
