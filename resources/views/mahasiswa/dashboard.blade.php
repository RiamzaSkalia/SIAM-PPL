@extends('layouts.mahasiswa')

@section('title', 'Dashboard Mahasiswa')
@section('page-title', 'Dashboard Mahasiswa')

@section('content')
<div class="space-y-6">

    {{-- Kartu selamat datang --}}
    <div class="bg-siam-navy rounded-2xl p-6 text-white">
        <p class="text-sm text-white/80">Selamat datang</p>
        <h2 class="text-2xl font-extrabold">{{ $mahasiswa->nama_mahasiswa }}</h2>
        <p class="text-sm text-white/80 mt-1">
            {{ $mahasiswa->nim }} &middot; Semester {{ $mahasiswa->semester }}
        </p>
    </div>

    @if ($plotting)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- KOLOM KIRI: statistik + status --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                        <p class="text-sm text-gray-500">Total Bimbingan</p>
                        <p class="text-3xl font-extrabold text-siam-navy">{{ $totalKonsultasi }}</p>
                    </div>
                    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                        <p class="text-sm text-gray-500">Terverifikasi</p>
                        <p class="text-3xl font-extrabold text-siam-navy">{{ $totalDisetujui }}</p>
                    </div>
                    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                        <p class="text-sm text-gray-500">Menunggu</p>
                        <p class="text-3xl font-extrabold text-siam-navy">{{ $totalPending }}</p>
                    </div>
                    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                        <p class="text-sm text-gray-500">Minimal</p>
                        <p class="text-3xl font-extrabold text-gray-700">{{ $minimalKonsultasi }}</p>
                        <p class="text-xs text-gray-400">kali konsultasi</p>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                    <p class="font-bold text-gray-800 mb-2">Status Konsultasi</p>
                    @if ($syaratTerpenuhi)
                        <p class="text-sm text-siam-green">
                            Syarat konsultasi terpenuhi. Kamu sudah memenuhi minimal {{ $minimalKonsultasi }} kali bimbingan. Kartu konsultasi dapat dicetak.
                        </p>
                    @else
                        <p class="text-sm text-siam-orange">
                            Kamu masih perlu {{ $sisaMenujuSyarat }} kali bimbingan yang disetujui lagi (minimal {{ $minimalKonsultasi }} kali) sebelum kartu konsultasi bisa dicetak.
                        </p>
                    @endif
                </div>

                <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                    <h3 class="font-bold text-gray-800 mb-3">Riwayat Bimbingan</h3>
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="text-gray-700 border-b border-gray-300">
                                <th class="py-2">Tanggal</th>
                                <th class="py-2">Media</th>
                                <th class="py-2">Topik</th>
                                <th class="py-2">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($riwayatKonsultasi as $log)
                                <tr class="border-b border-gray-100">
                                    <td class="py-2">{{ $log->tanggal_konsul->format('d M Y') }}</td>
                                    <td class="py-2">{{ $log->media_konsul }}</td>
                                    <td class="py-2">{{ $log->topik_dibahas }}</td>
                                    <td class="py-2">
                                        @if ($log->status_validasi === 'disetujui')
                                            <span class="px-3 py-1 rounded-full bg-siam-green/15 text-siam-green text-xs font-bold">Disetujui</span>
                                        @elseif ($log->status_validasi === 'ditolak')
                                            <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold">Ditolak</span>
                                        @else
                                            <span class="px-3 py-1 rounded-full bg-siam-orange/15 text-siam-orange text-xs font-bold">Menunggu</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="py-4 text-center text-gray-500">Belum ada riwayat.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- KOLOM KANAN: info dosen + status kartu --}}
            <div class="space-y-6">
                <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                    <p class="font-bold text-gray-800 mb-1">Dosen Pembimbing</p>
                    <p class="text-sm text-gray-700">{{ $plotting->dosen->nama_dosen }}</p>
                    <p class="text-xs text-gray-400">{{ $plotting->dosen->nip }}</p>
                </div>

                <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                    <p class="font-bold text-gray-800 mb-1">Status Kartu Konsultasi</p>
                    @if ($syaratTerpenuhi)
                        <p class="text-siam-green font-semibold text-sm mb-1">Siap Dicetak</p>
                        <p class="text-xs text-gray-400 mb-3">{{ $totalDisetujui }} bimbingan tercatat</p>
                        <a href="{{ Route::has('mahasiswa.kartu.cetak') ? route('mahasiswa.kartu.cetak') : '#' }}"
                           class="block text-center bg-siam-navy hover:bg-siam-navy-dark text-white font-bold py-2.5 rounded-full transition">
                            Cetak Kartu
                        </a>
                    @else
                        <p class="text-siam-orange font-semibold text-sm mb-1">Belum Terpenuhi</p>
                        <p class="text-xs text-gray-400">Kurang {{ $sisaMenujuSyarat }} bimbingan lagi</p>
                    @endif
                </div>
            </div>
        </div>
    @else
        <div class="bg-siam-orange/10 border border-siam-orange rounded-xl p-6 text-siam-orange font-semibold">
            Kamu belum dipetakan ke dosen pembimbing. Hubungi admin untuk plotting bimbingan.
        </div>
    @endif
</div>
@endsection
