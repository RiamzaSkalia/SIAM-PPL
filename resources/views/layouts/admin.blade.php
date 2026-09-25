<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIAM Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #fcf8f2; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .sidebar { width: 220px; background-color: #3b31b2; min-height: 100vh; padding: 20px 10px; color: white; position: fixed; }
        .main-content { margin-left: 230px; padding: 30px; }
        .nav-btn {
            display: block; width: 100%; background-color: #f7bb53; color: #222; text-align: center;
            padding: 8px 12px; border-radius: 20px; font-weight: bold; margin-bottom: 12px; text-decoration: none;
            font-size: 13px; text-transform: uppercase; border: none;
        }
        .nav-btn.active { background-color: #ffffff; color: #3b31b2; }
        .nav-btn-logout { background-color: #f7bb53; color: #222; margin-top: 50px; }
        .form-card { background-color: #b3bade; border-radius: 15px; padding: 25px; color: #111; }
        .form-card-header { background-color: #5560c4; color: white; padding: 10px 15px; border-radius: 10px; font-weight: 600; margin-bottom: 20px; font-size: 14px; }
        .form-control-custom { background-color: #fff9f0; border: none; border-radius: 12px; padding: 10px 15px; margin-bottom: 15px; }
        .btn-simpan { background-color: #00c853; color: white; font-weight: bold; border-radius: 20px; padding: 8px 30px; border: none; }
        .btn-batal { background-color: #888; color: white; font-weight: bold; border-radius: 20px; padding: 8px 30px; border: none; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="text-center mb-4">
        <i class="bi bi-mortarboard-fill fs-2"></i>
        <h5 class="fw-bold m-0">SIAM</h5>
        <small style="font-size: 11px;">ADMIN</small>
    </div>

    <a href="#" class="nav-btn">Dashboard</a>
    <a href="{{ route('admin.dosen.create') }}" class="nav-btn {{ request()->routeIs('admin.dosen.*') ? 'active' : '' }}">Data Dosen</a>
    <a href="{{ route('admin.mahasiswa.create') }}" class="nav-btn {{ request()->routeIs('admin.mahasiswa.*') ? 'active' : '' }}">Data Mahasiswa</a>
    <a href="#" class="nav-btn">Data Sekolah</a>
    <a href="#" class="nav-btn">Pemetaan Bimbingan</a>
    <a href="#" class="nav-btn">Pengaturan Sistem</a>

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="nav-btn nav-btn-logout">LOG OUT</button>
    </form>
</div>

<div class="main-content">
    @yield('content')
</div>

</body>
</html>