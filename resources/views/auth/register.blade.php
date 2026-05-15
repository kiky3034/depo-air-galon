<!DOCTYPE html>
<html lang="id">
<head>
<title>Register - Tirta Kencana</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<style>
body{background:linear-gradient(135deg,#1976d2,#1565c0);min-height:100vh;display:flex;align-items:center;justify-content:center;font-family:'Segoe UI',sans-serif}
.register-box{background:#fff;padding:35px;border-radius:20px;box-shadow:0 15px 35px rgba(0,0,0,.2);width:100%;max-width:450px}
.form-control{border-radius:10px;padding:12px 15px}
.form-control:focus{border-color:#1976d2;box-shadow:0 0 0 .2rem rgba(25,118,210,.15)}
.btn-reg{background:linear-gradient(90deg,#1976d2,#42a5f5);border:none;border-radius:10px;padding:12px;font-weight:bold;width:100%;color:#fff}
.btn-reg:hover{background:#1565c0;color:#fff}
</style>
</head>
<body>
<div class="register-box">
    <h5 class="fw-bold text-primary text-center mb-1"><i class="bi bi-person-plus-fill me-2"></i>Daftar Akun Pelanggan</h5>
    <p class="text-muted text-center small mb-4">Masukkan kode pelanggan dari petugas</p>

    <form method="POST" action="{{ route('register.process') }}">
        @csrf
        <div class="mb-3"><label class="form-label small fw-bold">Kode Pelanggan</label><input type="text" name="kode_pelanggan" class="form-control" placeholder="Contoh: PLG1" value="{{ old('kode_pelanggan') }}" required></div>
        <div class="mb-3"><label class="form-label small fw-bold">Nama Lengkap</label><input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required></div>
        <div class="mb-3"><label class="form-label small fw-bold">Username</label><input type="text" name="username" class="form-control" value="{{ old('username') }}" required></div>
        <div class="mb-4"><label class="form-label small fw-bold">Password</label><input type="password" name="password" class="form-control" required></div>
        <button type="submit" class="btn btn-reg">DAFTAR</button>
    </form>
    <div class="text-center mt-3"><a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none"><i class="bi bi-arrow-left me-1"></i>Kembali ke Login</a></div>
</div>
@include('partials._toast')
</body>
</html>
