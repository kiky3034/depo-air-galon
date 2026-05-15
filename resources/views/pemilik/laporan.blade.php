@extends('layouts.pemilik')
@section('title', 'Laporan Transaksi')
@push('styles')
<style>
.print-only{display:none}
.header-biru-custom{background:#1565c0!important;color:#fff!important}
.btn-outline-custom{color:#1565c0;border-color:#1565c0;transition:.3s}.btn-outline-custom:hover{background:#1565c0;color:#fff}
.pagination-wrapper{padding:12px 18px;display:flex;justify-content:space-between;align-items:center;background:#f8f9fa;border-top:1px solid #eee}
@media print{
@page{size:landscape;margin:1cm}body{background:#fff!important;color:#000!important}
.sidebar-desktop,.topbar,.btn,.offcanvas,.card-filter,.mt-4,.table-header,#inputCari,.pagination-wrapper{display:none!important}
.content{margin:0!important;padding:0!important;width:100%!important}.table-box{box-shadow:none!important;border:none!important;margin:0!important}
.print-only{display:block!important;text-align:center;margin-bottom:20px}
.table{border:1px solid #000!important;width:100%!important;font-size:10pt}.table thead th{background:#f2f2f2!important;border:1px solid #000!important;color:#000!important}.table tbody td{border:1px solid #000!important;padding:5px!important}
}
</style>
@endpush
@section('content')
<div class="print-only">
    <div style="font-size:22px;font-weight:bold;text-transform:uppercase">{{ $pengaturan->nama_usaha ?? 'Tirta Kencana' }}</div>
    <div style="font-size:14px">{{ $pengaturan->alamat_usaha ?? '' }}</div>
    <div style="font-size:14px">Telp: {{ $pengaturan->no_telp_usaha ?? '' }}</div>
    <hr style="border-top:2px solid #000;margin:10px 0">
    <h4 style="text-decoration:underline">LAPORAN REKAP TRANSAKSI</h4>
    <p>Periode: {{ $label_periode }}</p>
</div>
<div class="content">
    <div class="table-box card-filter mb-4">
        <div class="table-header header-biru-custom"><i class="bi bi-filter-left me-2"></i>Filter Laporan</div>
        <div class="card-body p-4">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-3 col-6"><label class="form-label small fw-bold text-muted">Tanggal Awal</label><input type="date" name="tgl_mulai" class="form-control border-0 bg-light" value="{{ $tgl_mulai }}"></div>
                <div class="col-md-3 col-6"><label class="form-label small fw-bold text-muted">Tanggal Akhir</label><input type="date" name="tgl_selesai" class="form-control border-0 bg-light" value="{{ $tgl_selesai }}"></div>
                <div class="col-md-2 col-12"><label class="form-label small fw-bold text-muted">Cari Pelanggan</label><input type="text" id="inputCari" class="form-control border-0 bg-light" placeholder="Ketik nama..."></div>
                <div class="col-md-4 col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill fw-bold shadow-sm" style="height:42px;background:#1565c0;border:none">Tampilkan</button>
                    <a href="{{ route('pemilik.laporan') }}" class="btn btn-outline-custom fw-bold flex-fill d-flex align-items-center justify-content-center" style="height:42px">Reset</a>
                    <button type="button" onclick="window.print()" class="btn btn-primary fw-bold px-3 shadow-sm" style="height:42px;background:#1565c0;border:none"><i class="bi bi-printer"></i></button>
                </div>
            </form>
        </div>
    </div>
    <div class="table-box">
        <div class="table-header"><i class="bi bi-cash-stack me-2"></i>Ringkasan Laporan: {{ $label_periode }}</div>
        <div class="card-body p-4">
            <div class="p-3 mb-4 rounded-3 border-start border-5 border-success bg-success bg-opacity-10 d-flex justify-content-between align-items-center">
                <div><span class="text-muted small fw-bold text-uppercase">Total Pemasukan (Sudah Bayar)</span><h2 class="mb-0 fw-bold text-success">Rp {{ number_format($total_pendapatan) }}</h2></div>
                <i class="bi bi-graph-up-arrow fs-1 text-success opacity-25"></i>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light"><tr class="text-center"><th style="width:5%">No</th><th class="text-start">Pelanggan</th><th>Tanggal</th><th>Metode</th><th>Status Antar</th><th>Status Bayar</th><th>Total Harga</th></tr></thead>
                    <tbody id="isiTabel">@forelse($data_laporan as $i => $row)
                    <tr class="text-center">
                        <td>{{ $data_laporan->firstItem()+$i }}</td>
                        <td class="text-start"><b>{{ $row->pelanggan->nama ?? '-' }}</b><br><small class="text-muted">{{ $row->pelanggan->kode_pelanggan ?? '' }}</small></td>
                        <td>{{ $row->tanggal->format('d/m/Y') }}</td>
                        <td><span class="badge {{ $row->metode_bayar=='Transfer'?'bg-info-subtle text-info':'bg-light text-dark' }} border px-2">{{ $row->metode_bayar ?: 'Tunai' }}</span></td>
                        <td>@if($row->status_antar=='Sudah'||$row->status_antar=='Selesai')<span class="badge bg-success-subtle text-success border border-success">Sudah Diantar</span>@elseif($row->ongkos_kirim<=0)<span class="badge bg-light text-muted border">Ambil Sendiri</span>@else<span class="badge bg-warning-subtle text-dark border border-warning">Belum Diantar</span>@endif</td>
                        <td><span class="badge {{ $row->isLunas()?'bg-success':'bg-danger' }} px-3">{{ $row->status_bayar }}</span></td>
                        <td class="fw-bold {{ $row->isLunas()?'text-success':'text-danger' }}">Rp {{ number_format($row->total_harga) }}</td>
                    </tr>
                    @empty<tr><td colspan="7" class="text-center py-4 text-muted">Tidak ada data ditemukan.</td></tr>@endforelse</tbody>
                </table>
            </div>
            <div class="pagination-wrapper"><div class="small text-muted fw-bold">Total: {{ $jumlah_data }} Data</div>{{ $data_laporan->links() }}</div>
            <div class="print-only" style="margin-top:50px"><div style="display:flex;justify-content:flex-end"><div style="text-align:center;width:250px">Semarang, {{ now()->format('d F Y') }}<br>Pemilik {{ $pengaturan->nama_usaha ?? 'Tirta Kencana' }}<br><br><br><br><b>( {{ session('nama') }} )</b></div></div></div>
        </div>
    </div>
    <div class="mt-4 mb-5"><a href="{{ route('pemilik.dashboard') }}" class="btn btn-primary px-4 py-2 shadow-sm fw-bold" style="background:#1565c0;border:none;border-radius:10px"><i class="bi bi-arrow-left-circle me-2"></i>Kembali ke Dashboard</a></div>
</div>
@endsection
@push('scripts')
<script>document.getElementById('inputCari')?.addEventListener('input',function(){let f=this.value.toLowerCase();let rows=document.querySelector("#isiTabel").rows;for(let i=0;i<rows.length;i++){let t=rows[i].cells[1].textContent.toLowerCase();rows[i].style.display=t.includes(f)?"":"none"}});</script>
@endpush
