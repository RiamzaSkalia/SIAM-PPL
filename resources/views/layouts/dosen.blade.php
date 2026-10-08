<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIAM Dosen')</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'siam-cream': '#F9F8F6',
                        'siam-navy': '#363E78',
                        'siam-orange': '#FBBF51',
                        'siam-orange-dark': '#E5AB3C',
                        'siam-green': '#10B981',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-siam-cream min-h-screen">
    <div class="flex min-h-screen">

        {{-- SIDEBAR KHUSUS DOSEN --}}
        <aside class="w-72 bg-siam-navy flex flex-col justify-between py-6 px-5 shrink-0 shadow-lg">
            <div>
                {{-- Logo & Judul SIAM --}}
                <div class="flex items-center gap-3 text-white mb-8 px-1">
                    <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center shrink-0 border border-white/20 shadow-inner">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 3 1 9l11 6 9-4.91V17h2V9L12 3zM5 13.18v4.34L12 21l7-3.48v-4.34l-7 3.82-7-3.82z"/>
                        </svg>
                    </div>
                    <div class="leading-tight">
                        <p class="font-extrabold text-xl tracking-wide text-white">SIAM</p>
                        <p class="text-xs text-white/80 font-medium">Dosen Pembimbing</p>
                    </div>
                </div>

                <nav class="flex flex-col gap-3">
                    {{-- Nama Dosen / Identitas di Sidebar --}}
                    <div class="text-sm font-bold text-center py-3 px-4 rounded-full bg-siam-orange text-gray-900 shadow-sm">
                        {{ $dosen->nama_dosen ?? auth()->user()->name }}
                    </div>

                    @php
                        $menuDosen = [
                            ['label' => 'Dashboard', 'route' => 'dosen.dashboard'],
                            ['label' => 'Mahasiswa Bimbingan', 'route' => 'dosen.mahasiswa.bimbingan'],
                        ];
                    @endphp

                    @foreach ($menuDosen as $item)
                        @php $active = request()->routeIs($item['route']); @endphp
                        <a href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}"
                           class="text-sm font-bold text-center py-3 px-4 rounded-full transition shadow-sm
                                {{ $active
                                    ? 'bg-white text-gray-900 shadow-md'
                                    : 'bg-siam-orange text-gray-900 hover:bg-siam-orange-dark' }}">
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </nav>
            </div>

            {{-- TOMBOL LOGOUT --}}
            <form method="POST" action="{{ Route::has('logout') ? route('logout') : '#' }}">
                @csrf
                <button type="submit"
                        class="w-full bg-siam-orange hover:bg-siam-orange-dark text-gray-900 text-sm font-bold py-3 px-4 rounded-full transition shadow-sm">
                    LOG OUT
                </button>
            </form>
        </aside>

        {{-- KONTEN UTAMA --}}
        <main class="flex-1 flex flex-col">
            <div class="bg-white px-8 py-5 border-b border-gray-100 shadow-sm">
                <h1 class="text-xl font-bold text-gray-800">@yield('page-title')</h1>
            </div>

            <div class="p-8 flex-1">
                @if (session('success'))
                    <div class="mb-4 rounded-lg bg-siam-green/10 border border-siam-green text-siam-green px-4 py-3 text-sm font-medium">
                        {{ session('success') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>