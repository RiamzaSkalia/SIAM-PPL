<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIAM Admin</title>
    
    {{-- CSS CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body { background-color: #fcf8f2; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; overflow-x: hidden; }
        .sidebar { width: 220px; background-color: #3b31b2; min-height: 100vh; padding: 20px 10px; color: white; position: fixed; top: 0; left: 0; z-index: 1050; transition: transform 0.3s ease; overflow-y: auto; }
        .main-content { margin-left: 230px; padding: 30px; transition: margin-left 0.3s ease, padding 0.3s ease; }
        .nav-btn {
            display: block; width: 100%; background-color: #f7bb53; color: #222; text-align: center;
            padding: 8px 12px; border-radius: 20px; font-weight: bold; margin-bottom: 12px; text-decoration: none;
            font-size: 13px; text-transform: uppercase; border: none; transition: all 0.2s ease;
        }
        .nav-btn.active { background-color: #ffffff; color: #3b31b2; }
        .nav-btn-logout { background-color: #f7bb53; color: #222; margin-top: 40px; }
        .nav-btn-logout:hover { background-color: #e2a83e; }
        
        /* Utility Styles untuk Tabel & Component Admin */
        .table-custom { background-color: #aab2df; border-radius: 12px; overflow: hidden; }
        .table-custom th { background-color: #5560c4; color: white; border: 1px solid #7a86e3; text-align: center; font-size: 14px; }
        .table-custom td { border: 1px solid #9aa5e2; background-color: #cbcfef; font-size: 14px; text-align: center; vertical-align: middle; }
        .btn-action-edit { background-color: #f0c352; color: #111; border-radius: 15px; font-size: 12px; font-weight: bold; padding: 3px 18px; border: none; text-decoration: none; }
        .btn-action-hapus { background-color: #e52828; color: white; border-radius: 15px; font-size: 12px; font-weight: bold; padding: 3px 15px; border: none; }
        .search-input { background-color: #cbcfef; border: none; border-radius: 20px; padding: 6px 20px; font-size: 14px; width: 300px; max-width: 100%; }
        .btn-tambah { background-color: #00c853; color: white; font-weight: bold; border-radius: 20px; padding: 6px 20px; text-decoration: none; font-size: 14px; display: inline-block; }

        /* Topbar & Overlay Mobile (Tersembunyi di Desktop) */
        .mobile-topbar {
            display: none;
            background-color: #3b31b2;
            color: white;
            padding: 12px 18px;
            position: sticky;
            top: 0;
            z-index: 1030;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1040;
        }
        .sidebar-overlay.show { display: block; }

        /* Penyesuaian Responsif Layar Kecil (Tablet & HP < 992px) */
        @media (max-width: 991.98px) {
            .mobile-topbar { display: flex; justify-content: space-between; align-items: center; }
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 20px 15px; }
            .search-input { width: 100%; }
        }
    </style>
</head>
<body>

{{-- Mobile Topbar (Muncul hanya di HP/Tablet) --}}
<div class="mobile-topbar">
    <div class="d-flex align-items-center gap-2">
        <i class="bi bi-mortarboard-fill fs-4"></i>
        <div>
            <span class="fw-bold d-block lh-1" style="font-size: 16px;">SIAM</span>
            <small style="font-size: 10px;">ADMIN</small>
        </div>
    </div>
    <button type="button" class="btn btn-sm fw-bold" style="background-color: #f7bb53; color: #222; border-radius: 10px;" onclick="toggleSidebar()">
        <i class="bi bi-list fs-4"></i>
    </button>
</div>

{{-- Overlay Gelap saat Sidebar Terbuka di Mobile --}}
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

{{-- Sidebar Navigation --}}
<div class="sidebar" id="sidebarMenu">
    <div class="d-flex justify-content-end d-lg-none mb-2">
        <button type="button" class="btn btn-sm text-white fs-4 p-0 lh-1" onclick="toggleSidebar()">&times;</button>
    </div>

    <div class="text-center mb-4">
        <i class="bi bi-mortarboard-fill fs-2"></i>
        <h5 class="fw-bold m-0">SIAM</h5>
        <small style="font-size: 11px;">ADMIN</small>
    </div>

    <a href="{{ route('admin.dashboard') }}" class="nav-btn {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">DASHBOARD</a>
    <a href="{{ route('admin.dosen.index') }}" class="nav-btn {{ request()->routeIs('admin.dosen.*') ? 'active' : '' }}">DATA DOSEN</a>
    <a href="{{ route('admin.mahasiswa.index') }}" class="nav-btn {{ request()->routeIs('admin.mahasiswa.*') ? 'active' : '' }}">DATA MAHASISWA</a>
    <a href="{{ route('admin.sekolah.index') }}" class="nav-btn {{ request()->routeIs('admin.sekolah.*') ? 'active' : '' }}">DATA SEKOLAH</a>
    <a href="{{ route('admin.pemetaan.index') }}" class="nav-btn {{ request()->routeIs('admin.pemetaan.*') ? 'active' : '' }}">PEMETAAN BIMBINGAN</a>
    <a href="{{ route('admin.rekognisi.index') }}" class="nav-btn {{ request()->routeIs('admin.rekognisi.*') ? 'active' : '' }}">MATKUL REKOGNISI</a>
    <a href="{{ route('admin.pengaturan.index') }}" class="nav-btn {{ request()->routeIs('admin.pengaturan.*') ? 'active' : '' }}">PENGATURAN SISTEM</a>

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="button" class="nav-btn nav-btn-logout" data-bs-toggle="modal" data-bs-target="#logoutModal">
            LOG OUT
        </button>
    </form>
</div>

{{-- Content Area --}}
<div class="main-content">
    @yield('content')
</div>

{{-- JS CDN --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function toggleSidebar() {
        document.getElementById('sidebarMenu').classList.toggle('show');
        document.getElementById('sidebarOverlay').classList.toggle('show');
    }
</script>

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

@php
    // Cek apakah database periode_akademik masih kosong sama sekali
    $belumAdaPeriode = \App\Models\PeriodeAkademik::count() === 0;
@endphp

@if($belumAdaPeriode && !request()->routeIs('admin.pengaturan.*'))
    <!-- MODAL PERINGATAN SETUP PERIODE AWAL -->
    <div class="modal fade" id="setupPeriodeModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="setupPeriodeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 p-4 text-center border-0 shadow-lg">
                <div class="modal-body p-0">
                    <div class="mb-3">
                        <i class="bi bi-exclamation-triangle-fill text-warning" style="font-size: 3.8rem;"></i>
                    </div>
                    <h5 class="fw-bold mb-2" id="setupPeriodeModalLabel" style="color: #2b3990;">Pengaturan Awal Diperlukan</h5>
                    <p class="text-muted mb-4" style="font-size: 14.5px;">
                        Belum ada data <strong>Periode Akademik</strong> di dalam sistem. Silakan tambahkan dan aktifkan periode akademik terlebih dahulu di halaman <strong>Pengaturan Sistem</strong> sebelum menambahkan data dosen, mahasiswa, sekolah, maupun pemetaan.
                    </p>
                    
                    <div class="d-flex justify-content-center">
                        <a href="{{ route('admin.pengaturan.index') }}" class="btn text-white rounded-pill px-4 py-2 fw-bold shadow-sm" style="background-color: #3b31b2;">
                            <i class="bi bi-gear-fill me-1"></i> Ke Pengaturan Sistem
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var setupModal = new bootstrap.Modal(document.getElementById('setupPeriodeModal'));
            setupModal.show();
        });
    </script>
@endif
</body>
</html>