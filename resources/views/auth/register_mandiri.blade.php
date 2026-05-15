<!DOCTYPE html>
<html lang="id">
<head>
<title>Register Mandiri - Tirta Kencana</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<style>
body{background:linear-gradient(135deg,#2e7d32,#43a047);min-height:100vh;display:flex;align-items:center;justify-content:center;font-family:'Segoe UI',sans-serif}
.register-box{background:#fff;padding:35px;border-radius:20px;box-shadow:0 15px 35px rgba(0,0,0,.2);width:100%;max-width:500px}
.form-control{border-radius:10px;padding:12px 15px}
.form-control:focus{border-color:#2e7d32;box-shadow:0 0 0 .2rem rgba(46,125,50,.15)}
.btn-reg{background:linear-gradient(90deg,#2e7d32,#66bb6a);border:none;border-radius:10px;padding:12px;font-weight:bold;width:100%;color:#fff}
.btn-reg:hover{background:#1b5e20;color:#fff}
</style>
</head>
<body>
<div class="register-box">
    <h5 class="fw-bold text-success text-center mb-1"><i class="bi bi-person-fill-add me-2"></i>Registrasi Mandiri</h5>
    <p class="text-muted text-center small mb-4">Daftar tanpa kode pelanggan</p>

    <form method="POST" action="{{ route('register.mandiri.process') }}">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3"><label class="form-label small fw-bold">Nama Lengkap</label><input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required></div>
            <div class="col-md-6 mb-3"><label class="form-label small fw-bold">No HP / WA</label><input type="text" name="no_hp" class="form-control" value="{{ old('no_hp') }}" required></div>
        </div>
        <div class="mb-3"><label class="form-label small fw-bold">Alamat</label><textarea name="alamat" class="form-control" rows="2" required>{{ old('alamat') }}</textarea></div>
        <div class="row">
            <div class="col-md-6 mb-3"><label class="form-label small fw-bold">Username</label><input type="text" name="username" class="form-control" value="{{ old('username') }}" required></div>
            <div class="col-md-6 mb-4"><label class="form-label small fw-bold">Password</label><input type="password" name="password" class="form-control" required></div>
        </div>
        <button type="submit" class="btn btn-reg">DAFTAR SEKARANG</button>
    </form>
    <div class="text-center mt-3"><a href="{{ route('login') }}" class="text-success fw-bold text-decoration-none"><i class="bi bi-arrow-left me-1"></i>Kembali ke Login</a></div>
</div>
@include('partials._toast')
</body>
</html>
