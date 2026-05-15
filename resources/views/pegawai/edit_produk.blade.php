@extends('layouts.pegawai')
@section('title', 'Edit Produk')
@section('content')
<div class="content">
    <div style="background:#fff;border-radius:15px;padding:25px;box-shadow:0 4px 15px rgba(0,0,0,.08);max-width:600px">
        <h5 class="fw-bold text-primary mb-4"><i class="bi bi-pencil-square me-2"></i>Edit Produk</h5>
        <form method="POST" action="{{ route('pegawai.produk.update', $produk->id_produk) }}">@csrf @method('PUT')
            <div class="mb-3"><label class="form-label small fw-bold">Jenis Air</label><input type="text" name="jenis_air" class="form-control" value="{{ $produk->jenis_air }}" required style="border-radius:10px"></div>
            <div class="row">
                <div class="col-md-6 mb-3"><label class="form-label small fw-bold">Satuan Stok</label><select name="satuan_stok" class="form-select" style="border-radius:10px"><option value="pcs" {{ $produk->satuan_stok=='pcs'?'selected':'' }}>PCS</option><option value="liter" {{ $produk->satuan_stok=='liter'?'selected':'' }}>Liter</option></select></div>
                <div class="col-md-6 mb-3"><label class="form-label small fw-bold">Isi per Unit (liter)</label><input type="number" name="isi_per_unit" class="form-control" value="{{ $produk->isi_per_unit }}" style="border-radius:10px"></div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3"><label class="form-label small fw-bold">Harga (Rp)</label><input type="number" name="harga" class="form-control" value="{{ $produk->harga }}" required style="border-radius:10px"></div>
                <div class="col-md-6 mb-3"><label class="form-label small fw-bold">Stok</label><input type="number" name="stok" class="form-control" value="{{ $produk->stok }}" required style="border-radius:10px"></div>
            </div>
            <div class="mb-4"><label class="form-label small fw-bold">Jenis Layanan</label><select name="jenis_layanan" class="form-select" style="border-radius:10px"><option value="isi_ulang" {{ $produk->jenis_layanan=='isi_ulang'?'selected':'' }}>Isi Ulang</option><option value="galon_baru_segel" {{ $produk->jenis_layanan=='galon_baru_segel'?'selected':'' }}>Galon Baru / Segel</option></select></div>
            <div class="d-flex gap-2"><button type="submit" class="btn btn-primary flex-fill shadow-sm fw-bold" style="border-radius:10px">Simpan</button><a href="{{ route('pegawai.produk') }}" class="btn btn-outline-secondary shadow-sm" style="border-radius:10px">Batal</a></div>
        </form>
    </div>
</div>
@endsection
