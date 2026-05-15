@extends('layouts.admin')
@section('title', 'Data User')
@push('styles')
<style>
.foto-user{width:42px;height:42px;border-radius:50%;object-fit:cover;border:2px solid #dee2e6}
</style>
@endpush
@section('content')
<div class="content">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex gap-2">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary btn-sm shadow-sm px-3" style="border-radius:10px"><i class="bi bi-arrow-left me-1"></i>Dashboard</a>
            <button class="btn btn-primary btn-sm shadow-sm px-3" style="border-radius:10px" data-bs-toggle="modal" data-bs-target="#modalTambah"><i class="bi bi-plus-lg me-1"></i>Tambah User</button>
        </div>
        <form method="GET" class="d-flex gap-2">
            <div class="input-group shadow-sm" style="max-width:250px">
                <span class="input-group-text bg-white border-end-0" style="border-radius:8px 0 0 8px"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="cari" class="form-control border-start-0" placeholder="Cari user..." value="{{ $cari }}" style="border-radius:0 8px 8px 0">
            </div>
        </form>
    </div>

    {{-- ========== TABEL 1: PEGAWAI & PEMILIK ========== --}}
    <div class="table-box mb-4">
        <div class="table-header"><i class="bi bi-person-badge me-2"></i>Data User Pegawai & Pemilik</div>
        <div class="p-3 table-responsive">
            <table class="table table-hover align-middle text-center" style="font-size:13px">
                <thead class="table-light"><tr style="text-transform:uppercase;font-size:11px;color:#666"><th>No</th><th>Foto</th><th>Nama</th><th>Username</th><th>Password</th><th>Role</th><th>Aksi</th></tr></thead>
                <tbody>
                @forelse($staff_users as $i => $u)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td><img src="{{ $u->foto ? asset('upload/user/'.$u->foto) : 'https://ui-avatars.com/api/?name='.urlencode($u->nama).'&background=1976d2&color=fff&size=42' }}" class="foto-user shadow-sm"></td>
                    <td>{{ $u->nama }}</td>
                    <td><code class="text-dark">{{ $u->username }}</code></td>
                    <td><span class="badge bg-light text-muted border" style="font-family:monospace">{{ $u->password }}</span></td>
                    <td><span class="badge {{ $u->role=='pemilik'?'bg-warning text-dark':'bg-info text-dark' }}">{{ ucfirst($u->role) }}</span></td>
                    <td>
                        <button class="btn btn-warning btn-sm text-white shadow-sm" style="border-radius:8px" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $u->id_user }}"><i class="bi bi-pencil-square"></i></button>
                        <form method="POST" action="{{ route('admin.user.delete', $u->id_user) }}" class="d-inline" onsubmit="return confirm('Yakin hapus user ini?')">@csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm shadow-sm" style="border-radius:8px"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-muted py-3">Belum ada user pegawai/pemilik.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ========== TABEL 2: ADMIN ========== --}}
    <div class="table-box">
        <div class="table-header" style="background:linear-gradient(90deg,#c62828,#e53935)"><i class="bi bi-shield-lock me-2"></i>Akun Administrator</div>
        <div class="p-3 table-responsive">
            <table class="table table-hover align-middle text-center" style="font-size:13px">
                <thead class="table-light"><tr style="text-transform:uppercase;font-size:11px;color:#666"><th>No</th><th>Foto</th><th>Nama</th><th>Username</th><th>Password</th><th>Role</th><th>Aksi</th></tr></thead>
                <tbody>
                @foreach($admin_users as $i => $u)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td><img src="{{ $u->foto ? asset('upload/user/'.$u->foto) : 'https://ui-avatars.com/api/?name='.urlencode($u->nama).'&background=c62828&color=fff&size=42' }}" class="foto-user shadow-sm"></td>
                    <td>{{ $u->nama }}</td>
                    <td><code class="text-dark">{{ $u->username }}</code></td>
                    <td><span class="badge bg-light text-muted border" style="font-family:monospace">{{ $u->password }}</span></td>
                    <td><span class="badge bg-danger">{{ ucfirst($u->role) }}</span></td>
                    <td>
                        <button class="btn btn-warning btn-sm text-white shadow-sm" style="border-radius:8px" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $u->id_user }}"><i class="bi bi-pencil-square"></i></button>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ========== MODAL EDIT (untuk semua user) ========== --}}
@foreach($staff_users->merge($admin_users) as $u)
<div class="modal fade" id="modalEdit{{ $u->id_user }}" tabindex="-1"><div class="modal-dialog"><div class="modal-content border-0 shadow-lg" style="border-radius:15px">
    <div class="modal-header border-0" style="background:linear-gradient(90deg,#1976d2,#42a5f5);color:#fff;border-radius:15px 15px 0 0"><h6 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i>Edit User</h6><button class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
    <form method="POST" action="{{ route('admin.user.update', $u->id_user) }}" enctype="multipart/form-data"><div class="modal-body p-4">@csrf @method('PUT')
        <div class="text-center mb-3">
            <img src="{{ $u->foto ? asset('upload/user/'.$u->foto) : 'https://ui-avatars.com/api/?name='.urlencode($u->nama).'&background=1976d2&color=fff&size=80' }}" class="rounded-circle border shadow-sm" width="80" height="80" style="object-fit:cover">
            <div class="mt-2"><input type="file" name="foto" class="form-control form-control-sm" accept="image/*" style="max-width:220px;margin:0 auto;border-radius:8px"></div>
        </div>
        <div class="mb-3"><label class="form-label small fw-bold">Nama</label><input type="text" name="nama" class="form-control" value="{{ $u->nama }}" required style="border-radius:10px"></div>
        <div class="mb-3"><label class="form-label small fw-bold">Username</label><input type="text" name="username" class="form-control" value="{{ $u->username }}" required style="border-radius:10px"></div>
        <div class="mb-3"><label class="form-label small fw-bold">Password (kosongkan jika tidak diganti)</label><input type="text" name="password" class="form-control" style="border-radius:10px"></div>
        @if($u->role != 'admin')
        <div class="mb-3"><label class="form-label small fw-bold">Role</label><select name="role" class="form-select" style="border-radius:10px"><option value="pemilik" {{ $u->role=='pemilik'?'selected':'' }}>Pemilik</option><option value="pegawai" {{ $u->role=='pegawai'?'selected':'' }}>Pegawai</option></select></div>
        @endif
    </div><div class="modal-footer border-0"><button type="submit" class="btn btn-primary w-100 fw-bold" style="border-radius:10px">Simpan</button></div></form>
</div></div></div>
@endforeach

{{-- ========== MODAL TAMBAH (hanya pegawai/pemilik) ========== --}}
<div class="modal fade" id="modalTambah" tabindex="-1"><div class="modal-dialog"><div class="modal-content border-0 shadow-lg" style="border-radius:15px">
    <div class="modal-header border-0" style="background:linear-gradient(90deg,#1976d2,#42a5f5);color:#fff;border-radius:15px 15px 0 0"><h6 class="mb-0 fw-bold"><i class="bi bi-person-plus me-2"></i>Tambah User</h6><button class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
    <form method="POST" action="{{ route('admin.user.store') }}" enctype="multipart/form-data"><div class="modal-body p-4">@csrf
        <div class="text-center mb-3">
            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto border shadow-sm" style="width:80px;height:80px"><i class="bi bi-camera text-muted fs-3"></i></div>
            <div class="mt-2"><input type="file" name="foto" class="form-control form-control-sm" accept="image/*" style="max-width:220px;margin:0 auto;border-radius:8px"></div>
        </div>
        <div class="mb-3"><label class="form-label small fw-bold">Nama</label><input type="text" name="nama" class="form-control" required style="border-radius:10px"></div>
        <div class="mb-3"><label class="form-label small fw-bold">Username</label><input type="text" name="username" class="form-control" required style="border-radius:10px"></div>
        <div class="mb-3"><label class="form-label small fw-bold">Password</label><input type="text" name="password" class="form-control" required style="border-radius:10px"></div>
        <div class="mb-3"><label class="form-label small fw-bold">Role</label><select name="role" class="form-select" style="border-radius:10px"><option value="pegawai" selected>Pegawai</option><option value="pemilik">Pemilik</option></select></div>
    </div><div class="modal-footer border-0"><button type="submit" class="btn btn-primary w-100 fw-bold" style="border-radius:10px">Simpan</button></div></form>
</div></div></div>
@endsection


