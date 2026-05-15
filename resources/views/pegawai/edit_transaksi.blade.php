@extends('layouts.pegawai')
@section('title', 'Edit Transaksi')
@push('styles')
<style>
.form-box{background:#fff;border-radius:15px;padding:25px;box-shadow:0 4px 15px rgba(0,0,0,.08);width:100%}
.item-produk{border:1px solid #eee;padding:15px;border-radius:12px;margin-bottom:12px;background:#fdfdfd;display:flex;align-items:center;justify-content:space-between;gap:10px;transition:.3s}
.item-produk:hover{border-color:#1976d2}
.qty-input-group{display:flex;align-items:center;gap:5px;flex-shrink:0}
.qty-input-group input{width:50px;text-align:center;font-weight:bold;border:1px solid #ddd;border-radius:8px;background:#f9f9f9;height:35px}
.summary-box{background:#f0f7ff;padding:15px;border-radius:12px;border:1px dashed #1976d2;margin-top:15px}
</style>
@endpush
@section('content')
<div class="content">
    <div class="form-box shadow">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold text-primary mb-0"><i class="bi bi-pencil-square me-2"></i>Edit Transaksi #{{ $transaksi->id_transaksi }}</h5>
            <a href="{{ route('pegawai.transaksi') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>
        <form method="POST" action="{{ route('pegawai.transaksi.update', $transaksi->id_transaksi) }}">
            @csrf @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3"><label class="fw-bold small mb-1">Pilih Pelanggan</label>
                    <select name="id_pelanggan" class="form-select shadow-sm" required>
                        @foreach($pelanggans as $p)<option value="{{ $p->id_pelanggan }}" {{ $p->id_pelanggan==$transaksi->id_pelanggan?'selected':'' }}>{{ $p->nama }} ({{ $p->kode_pelanggan }})</option>@endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3"><label class="fw-bold small mb-1">Metode Pembayaran</label>
                    <select name="metode_bayar" id="metode_bayar" class="form-select shadow-sm" onchange="toggleRekening()">
                        <option value="Cash" {{ $transaksi->metode_bayar=='Cash'?'selected':'' }}>Tunai / Cash</option>
                        <option value="Transfer" {{ $transaksi->metode_bayar=='Transfer'?'selected':'' }}>Transfer Bank</option>
                    </select>
                </div>
            </div>
            @php $info_rek = $pengaturan ? $pengaturan->nama_bank.': '.$pengaturan->no_rekening.' a/n '.$pengaturan->atas_nama : 'Belum diatur'; @endphp
            <div id="info-rekening" class="alert alert-info mb-3" style="display:{{ $transaksi->metode_bayar=='Transfer'?'block':'none' }}"><small class="fw-bold"><i class="bi bi-info-circle me-1"></i>Rekening:</small><br><span class="badge bg-white text-dark mt-1 shadow-sm">{{ $info_rek }}</span></div>
            @php $status_kirim = $transaksi->status_antar=='-'?'ambil':'antar'; @endphp
            <div class="row">
                <div class="col-md-4 mb-3"><label class="fw-bold small mb-1">Metode Pengiriman</label>
                    <select name="metode" id="metode" class="form-select shadow-sm" onchange="toggleOngkir()"><option value="ambil" {{ $status_kirim=='ambil'?'selected':'' }}>Ambil Sendiri</option><option value="antar" {{ $status_kirim=='antar'?'selected':'' }}>Antar ke Rumah</option></select>
                </div>
                <div class="col-md-4 mb-3" id="box-ongkir" style="display:{{ $status_kirim=='antar'?'block':'none' }}"><label class="fw-bold small mb-1">Ongkos Kirim (Rp)</label><input type="number" name="ongkos_kirim" id="ongkos_kirim" class="form-control shadow-sm" value="{{ $transaksi->ongkos_kirim }}" oninput="hitungTotal()"></div>
                <div class="col-md-4 mb-3" id="box-status" style="display:{{ $status_kirim=='antar'?'block':'none' }}"><label class="fw-bold small mb-1">Status Antar</label>
                    <select name="status_antar" class="form-select shadow-sm"><option value="Belum" {{ $transaksi->status_antar=='Belum'?'selected':'' }}>Belum Dikirim</option><option value="Selesai" {{ in_array($transaksi->status_antar,['Selesai','Sudah'])?'selected':'' }}>Selesai Dikirim</option></select>
                </div>
            </div>
            <label class="fw-bold small mb-2">Pilih Produk & Jumlah</label>
            <div class="row">
                @foreach($produks as $no => $row)
                @php $jml = $detail_lama[$row->id_produk] ?? 0; @endphp
                <div class="col-md-6"><div class="item-produk shadow-sm">
                    <div class="produk-info">
                        <span class="fw-bold d-block text-truncate">{{ $row->jenis_air }}</span>
                        <div class="mt-1">@if($row->jenis_layanan=='isi_ulang')<span class="badge bg-success" style="font-size:10px">ISI ULANG</span>@else<span class="badge bg-primary" style="font-size:10px">GALON BARU</span>@endif</div>
                        <div class="mt-1" style="font-size:11px"><span class="text-primary fw-bold">Rp {{ number_format($row->harga) }}</span><span class="text-muted ms-2">Stok: {{ $row->stok }}</span></div>
                    </div>
                    <div class="qty-input-group">
                        <input type="hidden" name="produk[]" value="{{ $row->id_produk }}">
                        <input type="hidden" class="harga-satuan" value="{{ $row->harga }}">
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="ubahQty({{ $no }},-1)">-</button>
                        <input type="number" name="jumlah[]" id="qty_{{ $no }}" class="jml-input" value="{{ $jml }}" readonly>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="ubahQty({{ $no }},1)">+</button>
                    </div>
                </div></div>
                @endforeach
            </div>
            <div class="summary-box shadow-sm">
                <div class="d-flex justify-content-between small mb-1"><span>Subtotal Produk:</span><span id="text-subtotal">Rp 0</span></div>
                <div class="d-flex justify-content-between small mb-2"><span>Biaya Ongkir:</span><span id="text-ongkir">Rp 0</span></div>
                <div class="d-flex justify-content-between border-top pt-2"><b class="text-dark">Grand Total:</b><b class="text-primary fs-5" id="text-grandtotal">Rp 0</b></div>
            </div>
            <div class="mt-4"><button type="submit" class="btn btn-primary w-100 py-3 fw-bold shadow-sm" style="border-radius:12px">Simpan Perubahan</button></div>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script>
function toggleRekening(){document.getElementById('info-rekening').style.display=document.getElementById('metode_bayar').value==='Transfer'?'block':'none'}
function toggleOngkir(){let m=document.getElementById('metode').value;document.getElementById('box-ongkir').style.display=m==='antar'?'block':'none';document.getElementById('box-status').style.display=m==='antar'?'block':'none';hitungTotal()}
function ubahQty(i,d){let n=document.getElementById('qty_'+i);let v=parseInt(n.value)+d;if(v<0)v=0;n.value=v;hitungTotal()}
function hitungTotal(){let s=0;let h=document.querySelectorAll('.harga-satuan');let j=document.querySelectorAll('.jml-input');j.forEach((x,i)=>{s+=parseInt(x.value||0)*parseInt(h[i].value)});let m=document.getElementById('metode').value;let o=m==='antar'?(parseInt(document.getElementById('ongkos_kirim').value)||0):0;document.getElementById('text-subtotal').innerText='Rp '+s.toLocaleString('id-ID');document.getElementById('text-ongkir').innerText='Rp '+o.toLocaleString('id-ID');document.getElementById('text-grandtotal').innerText='Rp '+(s+o).toLocaleString('id-ID')}
window.onload=function(){hitungTotal();toggleRekening()}
</script>
@endpush
