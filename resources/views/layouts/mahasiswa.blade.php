<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIAM Mahasiswa</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body { background-color: #fcf8f2; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 15px; color: #333; }
        .sidebar { width: 230px; background-color: #3b31b2; min-height: 100vh; padding: 20px 12px; color: white; position: fixed; top: 0; left: 0; z-index: 100; }
        .main-content { margin-left: 230px; padding: 35px 40px; width: calc(100% - 230px); }
        
        .user-profile-badge {
            background-color: #f7bb53; color: #1e1b4b; text-align: center;
            padding: 10px 12px; border-radius: 20px; font-weight: bold; margin-bottom: 20px;
            font-size: 14px; border: none; width: 100%; display: block; text-decoration: none;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .nav-btn {
            display: block; width: 100%; background-color: #f7bb53; color: #1e1b4b; text-align: center;
            padding: 10px 14px; border-radius: 20px; font-weight: bold; margin-bottom: 12px; text-decoration: none;
            font-size: 14px; border: none; transition: all 0.2s ease;
        }
        .nav-btn.active { background-color: #ffffff; color: #3b31b2; box-shadow: 0 2px 8px rgba(0,0,0,0.15); }
        .nav-btn-logout { background-color: #f7bb53; color: #1e1b4b; margin-top: 40px; }
        .nav-btn-logout:hover { background-color: #e2a83e; }
        
        .card-custom { background-color: #ffffff; border-radius: 16px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.03); width: 100%; }
        .form-control, .form-select { border-radius: 10px; border: 1px solid #ced4da; padding: 12px 16px; font-size: 15px; }
        .form-control:focus, .form-select:focus { border-color: #3b31b2; box-shadow: 0 0 0 0.2rem rgba(59, 49, 178, 0.15); }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="text-center mb-4">
        <i class="bi bi-mortarboard-fill fs-2"></i>
        <h5 class="fw-bold m-0">SIAM</h5>
        <small style="font-size: 11px;">MAHASISWA</small>
    </div>

    <!-- NAMA MAHASISWA YANG LOGIN SAMA SEPERTI DESAIN TOMBOL SISI KIRI -->
    <div class="user-profile-badge">
        {{ Auth::user()->mahasiswa->nama_mahasiswa ?? Auth::user()->name }}
    </div>

    <a href="{{ route('mahasiswa.dashboard') }}" class="nav-btn {{ request()->routeIs('mahasiswa.dashboard') ? 'active' : '' }}">Dashboard</a>
    <a href="{{ route('mahasiswa.konsultasi.create') }}" class="nav-btn {{ request()->routeIs('mahasiswa.konsultasi.create') ? 'active' : '' }}">Tambah Log Bimbingan</a>
    <a href="{{ route('mahasiswa.konsultasi.index') }}" class="nav-btn {{ request()->routeIs('mahasiswa.konsultasi.index') ? 'active' : '' }}">Riwayat Bimbingan</a>
    <a href="{{ route('mahasiswa.kartu') }}" class="nav-btn {{ request()->routeIs('mahasiswa.kartu') ? 'active' : '' }}">Kartu Konsultasi</a>
    <!-- Tombol Menu Mata Kuliah Rekognisi -->
    <a href="{{ route('mahasiswa.rekognisi.index') }}" class="nav-btn {{ request()->routeIs('mahasiswa.rekognisi.index') ? 'active' : '' }}">Mata Kuliah Rekognisi</a>
    <a href="{{ route('mahasiswa.dokumen.index') }}" class="nav-btn {{ request()->routeIs('mahasiswa.dokumen.index') ? 'active' : '' }}">Upload Dokumen</a>

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="button" class="nav-btn nav-btn-logout" data-bs-toggle="modal" data-bs-target="#logoutModal">
            LOG OUT
        </button>
    </form>
</div>

<div class="main-content">
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- MODAL KONFIRMASI LOG OUT -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 p-4 text-center border-0 shadow-lg">
            <div class="modal-body p-0">
                <div class="mb-3">
                    <i class="bi bi-box-arrow-right text-warning" style="font-size: 3.5rem;"></i>
                </div>
                <h5 class="fw-bold mb-2" id="logoutModalLabel" style="color: #2b3990;">Konfirmasi Keluar</h5>
                <p class="text-muted mb-4">Apakah Anda yakin ingin keluar dari halaman ini?</p>
                
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-secondary rounded-pill px-4 fw-semibold" data-bs-dismiss="modal">
                        Batal
                    </button>
                    
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger rounded-pill px-4 fw-semibold">
                            Ya, Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>