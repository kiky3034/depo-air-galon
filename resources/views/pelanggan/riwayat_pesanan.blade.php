@extends('layouts.pelanggan')
@section('title', 'Riwayat Pesanan')
@push('styles')
<style>
.card-stat{border:none;border-radius:12px;padding:15px;background:#fff;box-shadow:0 2px 8px rgba(0,0,0,.05);display:flex;align-items:center;gap:12px;height:100%}
.stat-icon-sm{width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0}
.table-box{border-radius:15px;overflow:hidden;box-shadow:0 4px 10px rgba(0,0,0,.08);background:#fff;margin-bottom:20px}
.table-header{background:#1565c0;color:#fff;padding:12px 15px;font-weight:600}
.badge-custom{font-size:10px!important;padding:4px 10px;border-radius:5px;font-weight:600}
.modal-backdrop.show{opacity:.18!important}
</style>
@endpush
@section('content')
<div class="content">

    <div class="row g-2 mb-3">
        <div class="col-4"><div class="card-stat"><div class="stat-icon-sm bg-blue-light"><i class="bi bi-receipt"></i></div><div><small class="text-muted fw-bold" style="font-size:10px">TOTAL</small><h5 class="mb-0 fw-bold">{{ $total_transaksi }}</h5></div></div></div>
        <div class="col-4"><div class="card-stat"><div class="stat-icon-sm" style="background:rgba(220,53,69,.1);color:#dc3545"><i class="bi bi-exclamation-circle"></i></div><div><small class="text-muted fw-bold" style="font-size:10px">BELUM BAYAR</small><h5 class="mb-0 fw-bold text-danger">{{ $jml_belum_bayar }}</h5></div></div></div>
        <div class="col-4"><div class="card-stat"><div class="stat-icon-sm" style="background:rgba(13,110,253,.1);color:#0d6efd"><i class="bi bi-credit-card"></i></div><div><small class="text-muted fw-bold" style="font-size:10px">TRANSFER</small><h5 class="mb-0 fw-bold">{{ $jml_transfer }}</h5></div></div></div>
    </div>

    <div class="d-flex gap-2 mb-3">
        <a href="{{ route('pelanggan.pesanan.buat') }}" class="btn btn-primary btn-sm shadow-sm px-3" style="border-radius:10px"><i class="bi bi-cart-plus me-1"></i>Buat Pesanan</a>
        <a href="{{ route('pelanggan.dashboard') }}" class="btn btn-outline-primary btn-sm shadow-sm px-3" style="border-radius:10px"><i class="bi bi-arrow-left me-1"></i>Dashboard</a>
    </div>

    @forelse($transaksis as $t)
    <div class="table-box">
        <div class="table-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-receipt me-2"></i>#{{ $t->id_transaksi }} — {{ $t->tanggal->format('d/m/Y') }}</span>
            <span class="badge {{ $t->isLunas()?'bg-success':'bg-danger' }} badge-custom">{{ strtoupper($t->status_bayar) }}</span>
        </div>
        <div class="p-3">
            <table class="table table-sm align-middle mb-2" style="font-size:12px">
                <thead class="table-light"><tr><th>Produk</th><th class="text-center">Qty</th><th class="text-end">Harga</th><th class="text-end">Subtotal</th></tr></thead>
                <tbody>@foreach($t->details as $d)
                <tr><td>{{ $d->produk->jenis_air ?? '-' }}</td><td class="text-center">{{ $d->jumlah }}</td><td class="text-end">Rp {{ number_format($d->harga_satuan) }}</td><td class="text-end fw-bold">Rp {{ number_format($d->subtotal) }}</td></tr>
                @endforeach</tbody>
            </table>
            <div class="d-flex justify-content-between border-top pt-2 small">
                <div>
                    @if($t->ongkos_kirim > 0)<span class="text-muted">Ongkir: Rp {{ number_format($t->ongkos_kirim) }}</span> |
                        @if(in_array($t->status_antar,['Sudah','Selesai']))<span class="badge bg-success-subtle text-success border badge-custom">Sudah Diantar</span>
                        @else<span class="badge bg-warning text-dark badge-custom">Belum Diantar</span>@endif
                    @else<span class="badge bg-light text-muted border badge-custom">Ambil Sendiri</span>@endif
                    <span class="ms-2 badge {{ $t->metode_bayar=='Transfer'?'bg-info-subtle text-info':'bg-light text-dark' }} border badge-custom">{{ $t->metode_bayar ?? 'Cash' }}</span>
                </div>
                <b class="{{ $t->isLunas()?'text-success':'text-danger' }}">Total: Rp {{ number_format($t->total_harga) }}</b>
            </div>
            <div class="d-flex gap-2 mt-3 flex-wrap">
                @if($t->metode_bayar == 'Transfer')
                    @if($t->bukti_bayar)
                        <button class="btn btn-sm btn-info text-white shadow-sm" style="border-radius:8px" data-bs-toggle="modal" data-bs-target="#bukti{{ $t->id_transaksi }}"><i class="bi bi-image me-1"></i>Lihat Bukti</button>
                    @endif
                    @if(!$t->isLunas())
                        <button class="btn btn-sm btn-primary shadow-sm" style="border-radius:8px" data-bs-toggle="modal" data-bs-target="#upload{{ $t->id_transaksi }}"><i class="bi bi-upload me-1"></i>Upload Bukti</button>
                    @endif
                @endif
                @if(!$t->isLunas() && empty($t->bukti_bayar))
                <form method="POST" action="{{ route('pelanggan.pesanan.batal') }}" onsubmit="return confirm('Yakin batalkan pesanan ini?')">@csrf<input type="hidden" name="id_transaksi" value="{{ $t->id_transaksi }}"><button class="btn btn-sm btn-outline-danger shadow-sm" style="border-radius:8px"><i class="bi bi-x-circle me-1"></i>Batalkan</button></form>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-5 text-muted"><i class="bi bi-inbox fs-1 d-block mb-2"></i>Belum ada pesanan.</div>
    @endforelse
</div>

@foreach($transaksis as $t)
@if($t->bukti_bayar)
<div class="modal fade" id="bukti{{ $t->id_transaksi }}" tabindex="-1"><div class="modal-dialog modal-dialog-centered"><div class="modal-content border-0 shadow-lg" style="border-radius:15px"><div class="modal-header border-0 pb-0"><h6 class="fw-bold">Bukti #{{ $t->id_transaksi }}</h6><button class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body text-center p-3"><img src="{{ asset('upload/bukti_bayar/'.$t->bukti_bayar) }}" class="img-fluid rounded shadow-sm border"></div></div></div></div>
@endif
@if(!$t->isLunas())
<div class="modal fade" id="upload{{ $t->id_transaksi }}" tabindex="-1"><div class="modal-dialog modal-dialog-centered"><div class="modal-content border-0 shadow-lg" style="border-radius:15px"><div class="modal-header border-0" style="background:linear-gradient(90deg,#1976d2,#42a5f5);color:#fff;border-radius:15px 15px 0 0"><h6 class="mb-0 fw-bold">Upload Bukti</h6><button class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
<form method="POST" action="{{ route('pelanggan.pesanan.bukti') }}" enctype="multipart/form-data"><div class="modal-body p-4">@csrf<input type="hidden" name="id_transaksi" value="{{ $t->id_transaksi }}">
<p class="small text-muted">Rekening: <b>{{ $pengaturan->nama_bank ?? '' }}: {{ $pengaturan->no_rekening ?? '' }}</b> a/n {{ $pengaturan->atas_nama ?? '' }}</p>
<input type="file" name="bukti" class="form-control" accept="image/*" required></div><div class="modal-footer border-0"><button type="submit" class="btn btn-primary w-100 fw-bold" style="border-radius:10px">Kirim Bukti</button></div></form>
</div></div></div>
@endif
@endforeach
@endsection


