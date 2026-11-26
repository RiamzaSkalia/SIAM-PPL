<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIAM Dosen')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- DataTables Bootstrap 5 CSS -->
    <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #fbf3e8;
            margin: 0;
        }
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background-color: #2b3990;
        }
        .btn-siam-orange {
            background-color: #fbbF51;
            border: none;
            color: #1f2937;
            font-weight: 700;
            letter-spacing: 0.3px;
        }
        .btn-siam-orange:hover {
            background-color: #e5ab3c;
            color: #1f2937;
        }
        .btn-siam-active {
            background-color: #ffffff;
            border: none;
            color: #2b3990;
            font-weight: 700;
            letter-spacing: 0.3px;
        }
        .btn-siam-active:hover {
            background-color: #f8f9fa;
            color: #2b3990;
        }
    </style>
</head>
<body>
    <div class="d-flex min-vh-100">

        {{-- SIDEBAR KHUSUS DOSEN --}}
        <aside class="sidebar d-flex flex-column align-items-center py-4 px-3 text-white flex-shrink-0 shadow">
            {{-- Logo & Judul SIAM (Vertikal di Tengah seperti Admin) --}}
            <div class="text-center mb-4">
                <i class="bi bi-mortarboard-fill fs-1 text-white d-block mb-1"></i>
                <h4 class="fw-bold mb-0 text-white">SIAM</h4>
                <small class="text-uppercase text-white-50 fw-semibold" style="font-size: 11px; letter-spacing: 0.8px;">
                    DOSEN PEMBIMBING
                </small>
            </div>

            @php
                $menuDosen = [
                    ['label' => 'DASHBOARD', 'route' => 'dosen.dashboard'],
                    ['label' => 'MAHASISWA BIMBINGAN', 'route' => 'dosen.mahasiswa.bimbingan'],
                    ['label' => 'REKOGNISI MAHASISWA', 'route' => 'dosen.rekognisi.index'],
                ];
            @endphp

            {{-- Daftar Menu Utama --}}
            <nav class="w-100 d-flex flex-column gap-3">
                @foreach ($menuDosen as $item)
                    @php $active = request()->routeIs($item['route']); @endphp
                    <a href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}"
                       class="btn w-100 rounded-pill py-2 px-3 shadow-sm {{ $active ? 'btn-siam-active' : 'btn-siam-orange' }}"
                       style="font-size: 13px;">
                        {{ $item['label'] }}
                    </a>
                @endforeach

                {{-- Tombol LOG OUT dengan jarak seperti sidebar admin --}}
                <form method="POST" action="{{ Route::has('logout') ? route('logout') : '#' }}" class="mt-2">
                    @csrf
                    <button type="submit"
                            class="btn btn-siam-orange w-100 rounded-pill py-2 px-3 shadow-sm"
                            style="font-size: 13px;">
                        LOG OUT
                    </button>
                </form>
            </nav>
        </aside>

        {{-- KONTEN UTAMA --}}
        <div class="flex-grow-1 d-flex flex-column min-w-0">
            @hasSection('page-title')
                <div class="bg-white px-4 py-3 border-bottom shadow-sm">
                    <h4 class="fw-bold mb-0 text-dark">@yield('page-title')</h4>
                </div>
            @endif

            <div class="p-3 p-md-4 flex-grow-1">
                @if (session('success'))
                    <div class="alert alert-success rounded-4 border-0 shadow-sm mb-4 fw-semibold">
                        {{ session('success') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <!-- jQuery & Bootstrap 5 JS Bundle -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables Bootstrap 5 JS -->
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    @stack('scripts')
</body>
</html>