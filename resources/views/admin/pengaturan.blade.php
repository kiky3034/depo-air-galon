@extends('layouts.admin')
@section('title', 'Pengaturan Sistem')
@section('content')
<div class="content">
    <div style="background:#fff;border-radius:15px;padding:25px;box-shadow:0 4px 15px rgba(0,0,0,.08)">
        <h5 class="fw-bold text-primary mb-4"><i class="bi bi-gear me-2"></i>Pengaturan Sistem</h5>
        <form method="POST" action="{{ route('admin.pengaturan.update') }}">@csrf @method('PUT')
            <h6 class="fw-bold text-secondary mb-3 border-bottom pb-2"><i class="bi bi-building me-1"></i> Identitas Usaha</h6>
            <div class="mb-3"><label class="form-label small fw-bold">Nama Usaha</label><input type="text" name="nama_usaha" class="form-control" value="{{ $data->nama_usaha ?? '' }}" style="border-radius:10px"></div>
            <div class="mb-3"><label class="form-label small fw-bold">Alamat Usaha</label><textarea name="alamat_usaha" class="form-control" rows="2" style="border-radius:10px">{{ $data->alamat_usaha ?? '' }}</textarea></div>
            <div class="mb-3"><label class="form-label small fw-bold">No Telepon Usaha</label><input type="text" name="no_telp_usaha" class="form-control" value="{{ $data->no_telp_usaha ?? '' }}" style="border-radius:10px"></div>
            <h6 class="fw-bold text-secondary mb-3 border-bottom pb-2 mt-4"><i class="bi bi-bank me-1"></i> Rekening Bank</h6>
            <div class="row">
                <div class="col-md-4 mb-3"><label class="form-label small fw-bold">Nama Bank</label><input type="text" name="nama_bank" class="form-control" value="{{ $data->nama_bank ?? '' }}" style="border-radius:10px"></div>
                <div class="col-md-4 mb-3"><label class="form-label small fw-bold">No Rekening</label><input type="text" name="no_rekening" class="form-control" value="{{ $data->no_rekening ?? '' }}" style="border-radius:10px"></div>
                <div class="col-md-4 mb-3"><label class="form-label small fw-bold">Atas Nama</label><input type="text" name="atas_nama" class="form-control" value="{{ $data->atas_nama ?? '' }}" style="border-radius:10px"></div>
            </div>
            <h6 class="fw-bold text-secondary mb-3 border-bottom pb-2 mt-4"><i class="bi bi-truck me-1"></i> Pengiriman</h6>
            <div class="mb-4"><label class="form-label small fw-bold">Ongkir Default (Rp)</label><input type="number" name="ongkir_default" class="form-control" value="{{ $data->ongkir_default ?? 5000 }}" style="border-radius:10px"></div>
            <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm py-2" style="border-radius:10px">Simpan Pengaturan</button>
        </form>
    </div>
</div>
@endsection


