<!DOCTYPE html>
<html lang="id">
<head>
<title>Login - Tirta Kencana</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
{{-- Preload logo agar tidak ada layout shift --}}
<link rel="preload" href="{{ asset('logo.png') }}" as="image">
{{-- Preload font icon agar icon langsung tampil --}}
<link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/fonts/bootstrap-icons.woff2" as="font" type="font/woff2" crossorigin>
<style>
/* ========== CRITICAL INLINE CSS ========== */
/* Semua style login card di-inline agar render pertama sudah benar */
*,::after,::before{box-sizing:border-box}
body{margin:0;background:linear-gradient(135deg,#1976d2,#1565c0,#0d47a1);min-height:100vh;display:flex;align-items:center;justify-content:center;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;-webkit-font-smoothing:antialiased}

/* Card login */
.login-box{background:#fff;padding:40px;border-radius:20px;box-shadow:0 15px 35px rgba(0,0,0,.2);width:100%;max-width:420px}
.login-header{text-align:center;margin-bottom:30px}
.login-header h4{color:#1976d2;font-weight:bold;margin:0 0 8px;font-size:1.5rem;line-height:1.2}
.login-header p{color:#999;font-size:14px;margin:0}

/* Logo - dimensi fixed agar tidak ada layout shift */
.logo-img{width:80px;height:80px;border-radius:50%;border:3px solid #1976d2;margin-bottom:15px;object-fit:cover}

/* Form controls - inline tanpa perlu Bootstrap */
.form-label{display:block;margin-bottom:6px;font-size:13px;font-weight:600;color:#6c757d}
.input-group{display:flex;width:100%}
.input-group-text{display:flex;align-items:center;padding:8px 14px;font-size:1rem;color:#212529;background:#f8f9fa;border:1px solid #ddd;border-radius:10px 0 0 10px;border-right:0}
.form-control{display:block;width:100%;padding:12px 15px;font-size:1rem;color:#212529;background:#fff;border:1px solid #ddd;border-radius:0 10px 10px 0;outline:none;transition:border-color .15s,box-shadow .15s}
.form-control:focus{border-color:#1976d2;box-shadow:0 0 0 .2rem rgba(25,118,210,.15)}

/* Buttons */
.btn-login{display:block;width:100%;background:linear-gradient(90deg,#1976d2,#42a5f5);border:none;border-radius:10px;padding:12px;font-weight:bold;color:#fff;font-size:1rem;cursor:pointer;transition:.3s;text-align:center}
.btn-login:hover{background:linear-gradient(90deg,#1565c0,#1976d2);transform:translateY(-2px);box-shadow:0 5px 15px rgba(25,118,210,.3)}

/* Alert */
.alert{padding:12px 16px;border-radius:10px;margin-bottom:16px;font-size:14px}
.alert-danger{background:#f8d7da;color:#842029}
.alert-success{background:#d1e7dd;color:#0f5132}

/* Spacing */
.mb-3{margin-bottom:16px}.mb-4{margin-bottom:24px}.mt-4{margin-top:24px}
.me-1{margin-right:4px}.me-2{margin-right:8px}.mx-1{margin:0 4px}
.text-center{text-align:center}
.text-muted{color:#6c757d}
.fw-bold{font-weight:700}
.text-primary{color:#1976d2}.text-success{color:#198754}
.text-decoration-none{text-decoration:none}
small{font-size:13px}
a{color:inherit}
</style>
{{-- Bootstrap di-load ASYNC agar tidak blocking render --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" media="print" onload="this.media='all'">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" media="print" onload="this.media='all'">
<noscript>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</noscript>
</head>
<body>
<div class="login-box">
    <div class="login-header">
        <img src="{{ asset('logo.png') }}" class="logo-img" width="80" height="80" alt="Logo" onerror="this.style.display='none'">
        <h4><i class="bi bi-droplet-fill me-2"></i>Tirta Kencana</h4>
        <p>Sistem Informasi Pemesanan Depot Air Galon</p>
    </div>

    <form method="POST" action="{{ route('login.process') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Username</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person"></i></span>
                <input type="text" name="username" class="form-control" placeholder="Masukkan username" value="{{ old('username') }}" required autofocus>
            </div>
        </div>
        <div class="mb-4">
            <label class="form-label">Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
            </div>
        </div>
        <button type="submit" class="btn-login"><i class="bi bi-box-arrow-in-right me-2"></i>LOGIN</button>
    </form>

    <div class="text-center mt-4">
        <small class="text-muted">Belum punya akun?</small><br>
        <a href="{{ route('register') }}" class="fw-bold text-primary text-decoration-none">Daftar dengan Kode</a>
        <span class="text-muted mx-1">|</span>
        <a href="{{ route('register.mandiri') }}" class="fw-bold text-success text-decoration-none">Daftar Mandiri</a>
    </div>
</div>

@include('partials._toast')
</body>
</html>
