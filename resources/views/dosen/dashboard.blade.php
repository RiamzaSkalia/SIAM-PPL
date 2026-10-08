@extends('layouts.dosen') {{-- Sesuaikan dengan nama layout utama dosen kamu, misal layouts.app atau layouts.dosen --}}

@section('title', 'Dashboard Dosen Pembimbing')
@section('page-title', 'Dashboard Dosen')

@section('content')
<div class="space-y-6">
    <!-- Kartu Sambutan & Statistik Singkat -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col sm:flex-row justify-between items-center gap-4">
        <div>
            <h2 class="text-xl font-extrabold text-gray-800">Selamat Datang, Dosen Pembimbing</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola dan verifikasi log catatan konsultasi mahasiswa bimbingan Anda dengan mudah di sini.</p>
        </div>
        <div class="flex gap-3">
            <div class="bg-indigo-50 border border-indigo-100 px-5 py-3 rounded-xl text-center">
                <span class="block text-xs text-indigo-600 font-bold uppercase tracking-wider">Menunggu Verifikasi</span>
                <span class="text-2xl font-extrabold text-indigo-700">0</span>
            </div>
        </div>
    </div>

    <!-- Tabel / Daftar Log Bimbingan Mahasiswa yang Perlu Dicek -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Daftar Bimbingan Mahasiswa</h3>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm min-w-[900px]">
                <thead>
                    <tr class="text-gray-700 border-b border-gray-200 bg-gray-50/50">
                        <th class="py-3 px-4">No</th>
                        <th class="py-3 px-4">Mahasiswa</th>
                        <th class="py-3 px-4">Tanggal / Waktu</th>
                        <th class="py-3 px-4">Topik Konsultasi</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="6" class="py-8 text-center text-gray-500">
                            Belum ada data bimbingan mahasiswa yang masuk.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection