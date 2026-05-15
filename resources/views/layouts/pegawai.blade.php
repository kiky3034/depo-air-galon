<!DOCTYPE html>
<html lang="id">
<head>
<title>@yield('title', 'Dashboard Pegawai') - Tirta Kencana</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<style>
body{background:#eef3f9;font-family:'Segoe UI',sans-serif;margin:0;padding:0;overflow-x:hidden}
.modal{z-index:2000!important}.modal-backdrop{z-index:1500!important}
.sidebar-desktop{position:fixed;top:0;left:0;width:240px;height:100vh;background:#1565c0;padding:20px;color:#fff;z-index:1100}
.sidebar-desktop a{display:flex;gap:10px;align-items:center;color:#e3f2fd;padding:10px;border-radius:10px;text-decoration:none;margin-bottom:8px;transition:.3s}
.sidebar-desktop a:hover,.sidebar-desktop a.active{background:rgba(255,255,255,.15);color:#fff}
.logo{width:45px;height:45px;border-radius:50%;border:2px solid #fff}
.topbar{position:fixed;top:0;left:255px;right:0;background:#1565c0;color:#fff;padding:12px 25px;z-index:1000;box-shadow:0 2px 8px rgba(0,0,0,.1)}
.icon-user{font-size:34px}
.content{margin-left:240px;padding:85px 25px 25px;position:relative;z-index:1}
.icon-circle{width:50px;height:50px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:24px;margin-bottom:10px}
.bg-blue-light{background:#e3f2fd;color:#0d47a1}.bg-green-light{background:#e8f5e9;color:#2e7d32}.bg-orange-light{background:#fff3e0;color:#ef6c00}
.stat-wrapper{display:flex;gap:15px}
.stat-card{flex:1;background:#fff;border-radius:15px;padding:15px;display:flex;flex-direction:column;align-items:center;box-shadow:0 4px 10px rgba(0,0,0,.05)}
.stat-card b{font-size:22px}.stat-card small{font-size:13px;color:#666;font-weight:500}
.card-custom{border:none;border-radius:18px;padding:25px 20px;text-align:center;transition:.3s;background:#fff;box-shadow:0 4px 10px rgba(0,0,0,.05)}
.card-custom:hover{transform:translateY(-5px)}
.card-custom .icon-circle{margin:0 auto 15px;width:60px;height:60px;font-size:30px}
.card-custom h5{font-weight:bold;color:#333;margin-bottom:5px}
.card-custom .btn{border-radius:10px;width:100%;padding:8px;font-weight:600;margin-top:15px;border:none}
.table-box{border-radius:15px;overflow:hidden;box-shadow:0 4px 10px rgba(0,0,0,.08);background:#fff}
.table-header{background:#1565c0;color:#fff;padding:15px;font-weight:600}
.table-box-elegant{border:1px solid #dee2e6;border-radius:10px;overflow:hidden;background:#fff;box-shadow:0 2px 8px rgba(0,0,0,.05)}
.table-header-blue{background:linear-gradient(90deg,#0d6efd,#0a58ca);color:#fff;padding:15px 20px;font-weight:600}
.offcanvas{width:70%!important;background-color:#1565c0!important;border:none!important}
.sidebar-mobile{background:#1565c0!important;height:100%;padding:20px;color:#fff}
.sidebar-mobile a{display:flex;gap:10px;padding:12px;color:#e3f2fd;text-decoration:none;border-radius:10px;margin-bottom:5px}
.sidebar-mobile a.active{background:rgba(255,255,255,.2);color:#fff}
@media(max-width:768px){.sidebar-desktop{display:none}.topbar{left:0;padding:10px 15px}.content{margin-left:0;padding-top:80px}.stat-wrapper{display:grid;grid-template-columns:repeat(2,1fr);gap:8px}}
</style>
@stack('styles')
</head>
<body>
@php $currentRoute = Route::currentRouteName(); @endphp
<div class="sidebar-desktop d-none d-md-block">
    <div class="d-flex align-items-center gap-2 mb-3"><img src="{{ asset('logo.png') }}" class="logo" onerror="this.style.display='none'"><div><b>Tirta Kencana</b><br><small>Pegawai Panel</small></div></div>
    <hr>
    <a href="{{ route('pegawai.dashboard') }}" class="{{ str_contains($currentRoute, 'pegawai.dashboard') ? 'active' : '' }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="{{ route('pegawai.pelanggan') }}" class="{{ str_contains($currentRoute, 'pegawai.pelanggan') ? 'active' : '' }}"><i class="bi bi-people"></i> Pelanggan</a>
    <a href="{{ route('pegawai.transaksi') }}" class="{{ str_contains($currentRoute, 'pegawai.transaksi') ? 'active' : '' }}"><i class="bi bi-receipt"></i> Riwayat Transaksi</a>
    <a href="{{ route('pegawai.produk') }}" class="{{ str_contains($currentRoute, 'pegawai.produk') ? 'active' : '' }}"><i class="bi bi-box-seam"></i> Produk</a>
    <hr>
    <a href="{{ route('logout') }}"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>
<div class="topbar d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center gap-2">
        <button class="btn btn-light d-md-none" data-bs-toggle="offcanvas" data-bs-target="#menuMobile"><i class="bi bi-list"></i></button>
        <i class="bi bi-person-circle icon-user"></i>
        <div style="line-height:1.1"><b>Pegawai</b><br><small>{{ session('nama') }}</small></div>
    </div>
    <a href="{{ route('logout') }}" class="text-white"><i class="bi bi-box-arrow-right fs-4"></i></a>
</div>
<div class="offcanvas offcanvas-start" id="menuMobile" tabindex="-1">
    <div class="sidebar-mobile">
        <div class="d-flex justify-content-between align-items-center mb-4 text-white">
            <div class="d-flex align-items-center gap-2"><img src="{{ asset('logo.png') }}" class="logo" onerror="this.style.display='none'"><div><b>Tirta Kencana</b><br><small>Pegawai Panel</small></div></div>
            <button class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <hr class="text-white opacity-25">
        <a href="{{ route('pegawai.dashboard') }}" class="{{ str_contains($currentRoute, 'pegawai.dashboard') ? 'active' : '' }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="{{ route('pegawai.pelanggan') }}" class="{{ str_contains($currentRoute, 'pegawai.pelanggan') ? 'active' : '' }}"><i class="bi bi-people"></i> Pelanggan</a>
        <a href="{{ route('pegawai.transaksi') }}" class="{{ str_contains($currentRoute, 'pegawai.transaksi') ? 'active' : '' }}"><i class="bi bi-receipt"></i> Riwayat Transaksi</a>
        <a href="{{ route('pegawai.produk') }}" class="{{ str_contains($currentRoute, 'pegawai.produk') ? 'active' : '' }}"><i class="bi bi-box-seam"></i> Produk</a>
        <hr class="text-white opacity-25">
        <a href="{{ route('logout') }}"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>
</div>
@yield('content')
@include('partials._toast')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>

