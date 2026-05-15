@extends('layouts.pegawai')
@section('title', 'Tambah Produk')
@section('content')
<div class="content">
    <div style="background:#fff;border-radius:15px;padding:25px;box-shadow:0 4px 15px rgba(0,0,0,.08);max-width:600px">
        <h5 class="fw-bold text-primary mb-4"><i class="bi bi-plus-circle me-2"></i>Tambah Produk Baru</h5>
        <form method="POST" action="{{ route('pegawai.produk.store') }}">@csrf
            <div class="mb-3"><label class="form-label small fw-bold">Jenis Air</label><input type="text" name="jenis_air" class="form-control" required style="border-radius:10px" placeholder="Contoh: AQUA 19L"></div>
            <div class="row">
                <div class="col-md-6 mb-3"><label class="form-label small fw-bold">Satuan Stok</label><select name="satuan_stok" class="form-select" style="border-radius:10px"><option value="pcs">PCS / Galon</option><option value="liter">Liter</option></select></div>
                <div class="col-md-6 mb-3"><label class="form-label small fw-bold">Isi per Unit (liter)</label><input type="number" name="isi_per_unit" class="form-control" value="19" style="border-radius:10px"></div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3"><label class="form-label small fw-bold">Harga (Rp)</label><input type="number" name="harga" class="form-control" required style="border-radius:10px"></div>
                <div class="col-md-6 mb-3"><label class="form-label small fw-bold">Stok Awal</label><input type="number" name="stok" class="form-control" required style="border-radius:10px"></div>
            </div>
            <div class="mb-4"><label class="form-label small fw-bold">Jenis Layanan</label><select name="jenis_layanan" class="form-select" style="border-radius:10px"><option value="isi_ulang">Isi Ulang</option><option value="galon_baru_segel">Galon Baru / Segel</option></select></div>
            <div class="d-flex gap-2"><button type="submit" class="btn btn-primary flex-fill shadow-sm fw-bold" style="border-radius:10px">Simpan</button><a href="{{ route('pegawai.produk') }}" class="btn btn-outline-secondary shadow-sm" style="border-radius:10px">Batal</a></div>
        </form>
    </div>
</div>
@endsection

