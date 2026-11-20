<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIAM Mahasiswa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @stack('styles')

    <style>
        body {
            background-color: #fcf8f2;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 15px;
            color: #333;
            overflow-x: hidden;
        }

        /* Topbar khusus HP & tablet (< 992px) */
        .mobile-topbar {
            background-color: #3b31b2;
            color: #ffffff;
            padding: 12px 16px;
            position: sticky;
            top: 0;
            z-index: 1020;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        /* Sidebar offcanvas-lg: drawer di HP, sidebar tetap di desktop */
     .sidebar.offcanvas-lg,
.offcanvas-lg.sidebar {
    width: 230px;
    background-color: #3b31b2 !important;
    color: #ffffff !important;
    padding: 20px 12px;
}

@media (min-width: 992px) {
    .sidebar.offcanvas-lg,
    .offcanvas-lg.sidebar {
        position: fixed;
        top: 0;
        left: 0;
        min-height: 100vh;
        z-index: 100;
        visibility: visible !important;
        transform: none !important;
    }

    .main-content {
        margin-left: 230px;
        padding: 35px 40px;
        width: calc(100% - 230px);
    }
}

        .user-profile-badge {
            background-color: #f7bb53;
            color: #1e1b4b;
            text-align: center;
            padding: 10px 12px;
            border-radius: 20px;
            font-weight: bold;
            margin-bottom: 20px;
            font-size: 14px;
            border: none;
            width: 100%;
            display: block;
            text-decoration: none;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            word-break: break-word;
        }

        .nav-btn {
            display: block;
            width: 100%;
            background-color: #f7bb53;
            color: #1e1b4b;
            text-align: center;
            padding: 10px 14px;
            border-radius: 20px;
            font-weight: bold;
            margin-bottom: 12px;
            text-decoration: none;
            font-size: 14px;
            border: none;
            transition: all 0.2s ease;
        }

        .nav-btn:hover {
            background-color: #ffca6b;
            color: #1e1b4b;
        }

        .nav-btn.active {
            background-color: #ffffff;
            color: #3b31b2;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .nav-btn-logout {
            background-color: #f7bb53;
            color: #1e1b4b;
            margin-top: 28px;
        }

        .nav-btn-logout:hover {
            background-color: #e2a83e;
        }

        .card-custom {
            background-color: #ffffff;
            border-radius: 16px;
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            width: 100%;
        }

        .form-control, .form-select {
            border-radius: 10px;
            border: 1px solid #ced4da;
            padding: 12px 16px;
            font-size: 15px;
        }

        .form-control:focus, .form-select:focus {
            border-color: #3b31b2;
            box-shadow: 0 0 0 0.2rem rgba(59, 49, 178, 0.15);
        }
    </style>
</head>
<body>

<!-- TOPBAR KHUSUS LAYAR HP / TABLET -->
<header class="mobile-topbar d-flex d-lg-none align-items-center justify-content-between">
    <div class="d-flex align-items-center gap-2">
        <i class="bi bi-mortarboard-fill fs-4 text-warning"></i>
        <span class="fw-bold">SIAM</span>
        <small class="text-light opacity-75" style="font-size: 11px;">MAHASISWA</small>
    </div>
    <button class="btn btn-warning btn-sm rounded-3 fw-bold text-dark px-2 py-1"
            type="button"
            data-bs-toggle="offcanvas"
            data-bs-target="#mahasiswaSidebar"
            aria-controls="mahasiswaSidebar"
            aria-label="Buka menu navigasi">
        <i class="bi bi-list fs-5"></i>
    </button>
</header>

<!-- SIDEBAR / OFFCANVAS -->
<aside class="sidebar offcanvas-lg offcanvas-start" tabindex="-1" id="mahasiswaSidebar" aria-labelledby="mahasiswaSidebarLabel">
    <div class="offcanvas-header d-lg-none border-bottom border-light border-opacity-25 pb-3 mb-3">
        <div class="d-flex align-items-center gap-2" id="mahasiswaSidebarLabel">
            <i class="bi bi-mortarboard-fill fs-4 text-warning"></i>
            <div>
                <h6 class="fw-bold m-0 text-white">SIAM</h6>
                <small class="text-light opacity-75" style="font-size: 10px;">MAHASISWA</small>
            </div>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" data-bs-target="#mahasiswaSidebar" aria-label="Tutup"></button>
    </div>

    <div class="text-center mb-4 d-none d-lg-block">
        <i class="bi bi-mortarboard-fill fs-2"></i>
        <h5 class="fw-bold m-0">SIAM</h5>
        <small style="font-size: 11px;">MAHASISWA</small>
    </div>

    <div class="offcanvas-body d-flex flex-column p-0">
        <!-- NAMA MAHASISWA YANG LOGIN SAMA SEPERTI DESAIN TOMBOL SISI KIRI -->
        <div class="user-profile-badge">
            {{ Auth::user()->mahasiswa->nama_mahasiswa ?? Auth::user()->name }}
        </div>

        <a href="{{ route('mahasiswa.dashboard') }}" class="nav-btn {{ request()->routeIs('mahasiswa.dashboard') ? 'active' : '' }}">Dashboard</a>
        <a href="{{ route('mahasiswa.konsultasi.create') }}" class="nav-btn {{ request()->routeIs('mahasiswa.konsultasi.create') ? 'active' : '' }}">Tambah Log Bimbingan</a>
        <a href="{{ route('mahasiswa.konsultasi.index') }}" class="nav-btn {{ request()->routeIs('mahasiswa.konsultasi.index') ? 'active' : '' }}">Riwayat Bimbingan</a>
        <a href="{{ route('mahasiswa.kartu') }}" class="nav-btn {{ request()->routeIs('mahasiswa.kartu') ? 'active' : '' }}">Kartu Konsultasi</a>
        <a href="{{ route('mahasiswa.rekognisi.index') }}" class="nav-btn {{ request()->routeIs('mahasiswa.rekognisi.index') ? 'active' : '' }}">Mata Kuliah Rekognisi</a>
        <a href="{{ route('mahasiswa.dokumen.index') }}" class="nav-btn {{ request()->routeIs('mahasiswa.dokumen.index') ? 'active' : '' }}">Upload Dokumen</a>

        <button type="button" class="nav-btn nav-btn-logout" data-bs-toggle="modal" data-bs-target="#logoutModal">
            LOG OUT
        </button>
    </div>
</aside>

<!-- KONTEN UTAMA -->
<main class="main-content">
    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')

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