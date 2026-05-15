@extends('layouts.pegawai')
@section('title', 'Edit Pelanggan')
@section('content')
<div class="content">
    <div style="background:#fff;border-radius:15px;padding:25px;box-shadow:0 4px 15px rgba(0,0,0,.08);max-width:550px">
        <h5 class="fw-bold text-primary mb-4"><i class="bi bi-pencil-square me-2"></i>Edit Pelanggan</h5>
        <form method="POST" action="{{ route('pegawai.pelanggan.update', $pelanggan->id_pelanggan) }}">@csrf @method('PUT')
            <div class="mb-3"><label class="form-label small fw-bold">Kode Pelanggan</label><input type="text" class="form-control bg-light" value="{{ $pelanggan->kode_pelanggan }}" readonly style="border-radius:10px"></div>
            <div class="mb-3"><label class="form-label small fw-bold">Nama</label><input type="text" name="nama" class="form-control" value="{{ $pelanggan->nama }}" required style="border-radius:10px"></div>
            <div class="mb-3"><label class="form-label small fw-bold">No HP</label><input type="text" name="no_hp" class="form-control" value="{{ $pelanggan->no_hp }}" style="border-radius:10px"></div>
            <div class="mb-4"><label class="form-label small fw-bold">Alamat</label><textarea name="alamat" class="form-control" rows="3" style="border-radius:10px">{{ $pelanggan->alamat }}</textarea></div>
            <div class="d-flex gap-2"><button type="submit" class="btn btn-primary flex-fill fw-bold shadow-sm" style="border-radius:10px">Simpan</button><a href="{{ route('pegawai.pelanggan') }}" class="btn btn-outline-secondary shadow-sm" style="border-radius:10px">Batal</a></div>
        </form>
    </div>
</div>
@endsection
