<!DOCTYPE html>
<html lang="id">
<head>
<title>@yield('title', 'Pemilik') - Tirta Kencana</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<style>
body{background:#eef3f9;font-family:'Segoe UI',sans-serif;margin:0;padding:0}
.sidebar-desktop{position:fixed;top:0;left:0;width:240px;height:100vh;background:#1565c0;padding:20px;color:#fff;z-index:1000}
.sidebar-desktop a{display:flex;gap:10px;align-items:center;color:#e3f2fd;padding:10px;border-radius:10px;text-decoration:none;margin-bottom:8px;transition:.3s}
.sidebar-desktop a:hover,.sidebar-desktop a.active{background:rgba(255,255,255,.15);color:#fff}
.logo{width:45px;height:45px;border-radius:50%;border:2px solid #fff}
.topbar{position:fixed;top:0;left:255px;right:0;background:#1565c0;color:#fff;padding:12px 25px;z-index:999;box-shadow:0 2px 8px rgba(0,0,0,.1)}
.icon-user{font-size:34px}
.content{margin-left:240px;padding:85px 25px 25px;position:relative;z-index:1}
.icon-circle{width:50px;height:50px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:24px;margin-bottom:10px}
.bg-blue-light{background:#e3f2fd;color:#1565c0}.bg-purple-light{background:#f3e5f5;color:#7b1fa2}
.bg-orange-light{background:#fff3e0;color:#ef6c00}.bg-green-light{background:#e8f5e9;color:#2e7d32}.bg-red-light{background:#ffebee;color:#c62828}
.stat-wrapper{display:flex;gap:15px}
.stat-card{flex:1;background:#fff;border-radius:15px;padding:15px;display:flex;flex-direction:column;align-items:center;box-shadow:0 4px 10px rgba(0,0,0,.05)}
.stat-card b{font-size:22px}.stat-card small{font-size:13px;color:#666;font-weight:500}
.table-box{border-radius:15px;overflow:hidden;box-shadow:0 4px 10px rgba(0,0,0,.08);background:#fff}
.table-header{background:#1565c0;color:#fff;padding:15px;font-weight:600}
.modal-backdrop.show{opacity:.18!important}
.offcanvas{width:70%!important;background-color:#1565c0!important;border:none!important}
.sidebar-mobile{background:#1565c0!important;height:100%;padding:20px;color:#fff}
.sidebar-mobile a{display:flex;gap:10px;padding:12px;color:#e3f2fd;text-decoration:none;border-radius:10px;margin-bottom:5px}
.sidebar-mobile a.active{background:rgba(255,255,255,.2);color:#fff}
@media(max-width:768px){.sidebar-desktop{display:none}.topbar{left:0;padding:10px 15px}.content{margin-left:0;padding-top:80px}.stat-wrapper{display:grid;grid-template-columns:repeat(2,1fr);gap:8px}}
</style>
@stack('styles')
</head>
<body>
@php $cr = Route::currentRouteName(); @endphp
<div class="sidebar-desktop d-none d-md-block">
    <div class="d-flex align-items-center gap-2 mb-3"><img src="{{ asset('logo.png') }}" class="logo" onerror="this.style.display='none'"><div><b>Tirta Kencana</b><br><small>Pemilik Panel</small></div></div><hr>
    <a href="{{ route('pemilik.dashboard') }}" class="{{ str_contains($cr,'pemilik.dashboard')?'active':'' }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="{{ route('pemilik.pelanggan') }}" class="{{ str_contains($cr,'pemilik.pelanggan')?'active':'' }}"><i class="bi bi-people"></i> Pelanggan</a>
    <a href="{{ route('pemilik.pegawai') }}" class="{{ str_contains($cr,'pemilik.pegawai')?'active':'' }}"><i class="bi bi-person-badge"></i> Data Pegawai</a>
    <a href="{{ route('pemilik.transaksi') }}" class="{{ str_contains($cr,'pemilik.transaksi')?'active':'' }}"><i class="bi bi-receipt"></i> Riwayat Transaksi</a>
    <a href="{{ route('pemilik.laporan') }}" class="{{ str_contains($cr,'pemilik.laporan')?'active':'' }}"><i class="bi bi-file-earmark-bar-graph"></i> Laporan Transaksi</a><hr>
    <a href="{{ route('logout') }}"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>
<div class="topbar d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center gap-2">
        <button class="btn btn-light d-md-none" data-bs-toggle="offcanvas" data-bs-target="#mm"><i class="bi bi-list"></i></button>
        <i class="bi bi-person-circle icon-user"></i><div style="line-height:1.1"><b>Pemilik</b><br><small>{{ session('nama') }}</small></div>
    </div>
    <a href="{{ route('logout') }}" class="text-white"><i class="bi bi-box-arrow-right fs-4"></i></a>
</div>
<div class="offcanvas offcanvas-start" id="mm" tabindex="-1"><div class="sidebar-mobile">
    <div class="d-flex justify-content-between align-items-center mb-4 text-white"><div class="d-flex align-items-center gap-2"><img src="{{ asset('logo.png') }}" class="logo" onerror="this.style.display='none'"><div><b>Tirta Kencana</b><br><small>Pemilik</small></div></div><button class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button></div><hr class="text-white opacity-25">
    <a href="{{ route('pemilik.dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="{{ route('pemilik.pelanggan') }}"><i class="bi bi-people"></i> Pelanggan</a>
    <a href="{{ route('pemilik.pegawai') }}"><i class="bi bi-person-badge"></i> Data Pegawai</a>
    <a href="{{ route('pemilik.transaksi') }}"><i class="bi bi-receipt"></i> Riwayat Transaksi</a>
    <a href="{{ route('pemilik.laporan') }}"><i class="bi bi-file-earmark-bar-graph"></i> Laporan</a><hr class="text-white opacity-25">
    <a href="{{ route('logout') }}"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div></div>
@yield('content')
@include('partials._toast')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>

