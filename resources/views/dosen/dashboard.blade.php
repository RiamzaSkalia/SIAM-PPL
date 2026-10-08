@extends('layouts.dosen')

@section('title', 'Dashboard Dosen Pembimbing')
@section('page-title', 'Dashboard Dosen')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    
    <!-- Banner Sambutan Utama (Biru Tua) -->
    <div class="bg-[#333B85] text-white rounded-2xl p-6 shadow-sm">
        <h2 class="text-xs opacity-80 font-medium">Selamat datang</h2>
        <h1 class="text-xl font-extrabold mt-0.5">{{ $dosen->nama_dosen ?? Auth::user()->name }}</h1>
        <p class="text-xs opacity-75 mt-1">{{ $dosen->nidn ?? '-' }} • Dosen Pembimbing</p>
    </div>

    <!-- Grid Kartu Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Kolom Kiri: 4 Kotak Statistik Kecil -->
        <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="bg-white rounded-2xl p-5 shadow-xs border border-gray-100">
                <p class="text-xs text-gray-500 font-medium">Mahasiswa Bimbingan</p>
                <h3 class="text-3xl font-extrabold text-gray-800 mt-2">{{ $jumlahMahasiswa ?? 0 }}</h3>
            </div>
            <div class="bg-white rounded-2xl p-5 shadow-xs border border-gray-100">
                <p class="text-xs text-gray-500 font-medium">Total Log Bimbingan</p>
                <h3 class="text-3xl font-extrabold text-gray-800 mt-2">{{ $totalLog ?? 0 }}</h3>
            </div>
            <div class="bg-white rounded-2xl p-5 shadow-xs border border-gray-100">
                <p class="text-xs text-gray-500 font-medium">Menunggu Verifikasi</p>
                <h3 class="text-3xl font-extrabold text-gray-800 mt-2">{{ $menungguVerifikasi ?? 0 }}</h3>
            </div>
            <div class="bg-white rounded-2xl p-5 shadow-xs border border-gray-100">
                <p class="text-xs text-gray-500 font-medium">Sudah Memenuhi Syarat</p>
                <h3 class="text-3xl font-extrabold text-gray-800 mt-2">1</h3>
            </div>
        </div>

        <!-- Kolom Kanan: Kotak Informasi Tambahan -->
        <div class="bg-white rounded-2xl p-6 shadow-xs border border-gray-100 flex flex-col justify-between">
            <div>
                <h4 class="text-xs text-gray-400 font-bold uppercase tracking-wider">Status Dosen</h4>
                <h3 class="text-base font-extrabold text-gray-800 mt-1">Pembimbing Aktif</h3>
                <p class="text-xs text-gray-500 mt-2">Pastikan untuk memeriksa log bimbingan mahasiswa secara berkala.</p>
            </div>
        </div>
    </div>

    <!-- Kotak Daftar Log Menunggu Verifikasi -->
    <div class="bg-white rounded-2xl shadow-xs border border-gray-100 p-6 space-y-4">
        <div>
            <h3 class="text-base font-extrabold text-gray-800">Log Menunggu Verifikasi</h3>
            <p class="text-xs text-gray-500 mt-0.5">Terdapat {{ $menungguVerifikasi ?? 0 }} log bimbingan yang belum diverifikasi</p>
        </div>

        <div class="space-y-3">
            @forelse ($logMenunggu ?? [] as $log)
                <div class="bg-amber-50/40 border border-amber-100/80 rounded-xl p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                    <div>
                        <h4 class="font-extrabold text-gray-900 text-sm">{{ $log->mahasiswa->nama_mahasiswa ?? 'Mahasiswa' }}</h4>
                        <p class="text-xs text-gray-500 mt-0.5">
                            {{ \Carbon\Carbon::parse($log->tanggal_konsul)->format('d F Y') }} • {{ $log->topik_dibahas }}
                        </p>
                    </div>
                    <div>
                        <a href="#" class="bg-[#333B85] hover:bg-indigo-900 text-white text-xs font-bold px-4 py-2 rounded-xl shadow-xs transition inline-block">
                            Verifikasi
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-400 text-sm">
                    Tidak ada log bimbingan yang menunggu verifikasi saat ini.
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection