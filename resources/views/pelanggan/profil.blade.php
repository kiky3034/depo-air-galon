@extends('layouts.pelanggan')
@section('title', 'Profil Pelanggan')
@section('content')
<div class="content">

    <div class="row g-4">
        <div class="col-md-7">
            <div style="background:#fff;border-radius:15px;padding:25px;box-shadow:0 4px 15px rgba(0,0,0,.08)">
                <h5 class="fw-bold text-primary mb-4"><i class="bi bi-person-gear me-2"></i>Profil Saya</h5>
                <form method="POST" action="{{ route('pelanggan.profil.update') }}" enctype="multipart/form-data">@csrf @method('PUT')
                    <div class="text-center mb-4">
                        <img src="{{ $user->foto ? asset('upload/user/'.$user->foto) : asset('upload/user/default.png') }}" class="rounded-circle border shadow-sm" width="100" height="100" style="object-fit:cover" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->nama) }}&background=1976d2&color=fff&size=100'">
                        <div class="mt-2"><input type="file" name="foto" class="form-control form-control-sm" accept="image/*" style="max-width:250px;margin:0 auto"></div>
                    </div>
                    @if($user->kode_pelanggan)<div class="mb-3"><label class="form-label small fw-bold">Kode Pelanggan</label><input type="text" class="form-control bg-light" value="{{ $user->kode_pelanggan }}" readonly style="border-radius:10px"></div>@endif
                    <div class="mb-3"><label class="form-label small fw-bold">Nama</label><input type="text" name="nama" class="form-control" value="{{ $user->nama }}" required style="border-radius:10px"></div>
                    <div class="mb-3"><label class="form-label small fw-bold">No Telp / WA</label><input type="text" name="no_telp" class="form-control" value="{{ $user->no_telp }}" style="border-radius:10px"></div>
                    <div class="mb-4"><label class="form-label small fw-bold">Alamat</label><textarea name="alamat" class="form-control" rows="3" style="border-radius:10px">{{ $user->alamat }}</textarea></div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm py-2" style="border-radius:10px">Simpan Perubahan</button>
                </form>
            </div>
        </div>
        <div class="col-md-5">
            <div style="background:#fff;border-radius:15px;padding:25px;box-shadow:0 4px 15px rgba(0,0,0,.08)">
                <h5 class="fw-bold text-danger mb-4"><i class="bi bi-shield-lock me-2"></i>Ganti Password</h5>
                <form method="POST" action="{{ route('pelanggan.profil.password') }}">@csrf @method('PUT')
                    <div class="mb-3"><label class="form-label small fw-bold">Password Baru</label><input type="password" name="pass_baru" class="form-control" required style="border-radius:10px"></div>
                    <div class="mb-4"><label class="form-label small fw-bold">Konfirmasi Password</label><input type="password" name="konfirmasi" class="form-control" required style="border-radius:10px"></div>
                    <button type="submit" class="btn btn-danger w-100 fw-bold shadow-sm py-2" style="border-radius:10px">Ganti Password</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


