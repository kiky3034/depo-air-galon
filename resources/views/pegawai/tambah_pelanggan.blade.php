@extends('layouts.pegawai')
@section('title', 'Tambah Pelanggan')
@section('content')
<div class="content">
    <div style="background:#fff;border-radius:15px;padding:25px;box-shadow:0 4px 15px rgba(0,0,0,.08);max-width:550px">
        <h5 class="fw-bold text-primary mb-4"><i class="bi bi-person-plus me-2"></i>Tambah Pelanggan Baru</h5>
        @if($errors->any())
            <div class="alert alert-danger" style="border-radius:10px">
                <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif
        @if(session('success'))
            <div class="alert alert-success" style="border-radius:10px">{{ session('success') }}</div>
        @endif
        <form method="POST" action="{{ route('pegawai.pelanggan.store') }}">@csrf
            <div class="mb-3"><label class="form-label small fw-bold">Nama Lengkap</label><input type="text" name="nama" class="form-control" required style="border-radius:10px"></div>
            <div class="mb-3"><label class="form-label small fw-bold">No HP / WA</label><input type="text" name="no_hp" class="form-control" style="border-radius:10px"></div>
            <div class="mb-4"><label class="form-label small fw-bold">Alamat</label><textarea name="alamat" class="form-control" rows="3" style="border-radius:10px"></textarea></div>
            <div class="d-flex gap-2"><button type="submit" class="btn btn-primary flex-fill fw-bold shadow-sm" style="border-radius:10px">Simpan</button><a href="{{ route('pegawai.pelanggan') }}" class="btn btn-outline-secondary shadow-sm" style="border-radius:10px">Kembali</a></div>
        </form>
    </div>
</div>
@endsection

