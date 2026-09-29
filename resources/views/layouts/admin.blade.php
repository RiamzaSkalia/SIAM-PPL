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
        .table-custom { background-color: #aab2df; border-radius: 12px; overflow: hidden; }
        .table-custom th { background-color: #5560c4; color: white; border: 1px solid #7a86e3; text-align: center; font-size: 14px; }
        .table-custom td { border: 1px solid #9aa5e2; background-color: #cbcfef; font-size: 14px; text-align: center; vertical-align: middle; }
        .btn-action-edit { background-color: #f0c352; color: #111; border-radius: 15px; font-size: 12px; font-weight: bold; padding: 3px 18px; border: none; text-decoration: none; }
        .btn-action-hapus { background-color: #e52828; color: white; border-radius: 15px; font-size: 12px; font-weight: bold; padding: 3px 15px; border: none; }
        .search-input { background-color: #cbcfef; border: none; border-radius: 20px; padding: 6px 20px; font-size: 14px; width: 300px; }
        .btn-tambah { background-color: #00c853; color: white; font-weight: bold; border-radius: 20px; padding: 6px 20px; text-decoration: none; font-size: 14px; }
    </style>
</head>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<body>

<div class="sidebar">
    <div class="text-center mb-4">
        <i class="bi bi-mortarboard-fill fs-2"></i>
        <h5 class="fw-bold m-0">SIAM</h5>
        <small style="font-size: 11px;">ADMIN</small>
    </div>

    <a href="#" class="nav-btn">DASHBOARD</a>
    <a href="{{ route('admin.dosen.index') }}" class="nav-btn {{ request()->routeIs('admin.dosen.*') ? 'active' : '' }}">DATA DOSEN</a>
    <a href="{{ route('admin.mahasiswa.index') }}" class="nav-btn {{ request()->routeIs('admin.mahasiswa.*') ? 'active' : '' }}">DATA MAHASISWA</a>
    <a href="#" class="nav-btn">DATA SEKOLAH</a>
    <a href="#" class="nav-btn">PEMETAAN BIMBINGAN</a>
    <a href="#" class="nav-btn">PENGATURAN SISTEM</a>

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