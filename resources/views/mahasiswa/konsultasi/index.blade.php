@extends('layouts.mahasiswa')

@section('title', 'Riwayat Bimbingan')
@section('page-title', 'Riwayat Bimbingan')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-extrabold text-gray-800">Daftar Bimbingan</h2>
            <p class="text-sm text-gray-500">Daftar seluruh catatan konsultasi bimbingan Anda</p>
        </div>
        <a href="{{ route('mahasiswa.konsultasi.create') }}"
           class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-full text-sm font-bold shadow transition">
            + Tambah Log Baru
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm min-w-[1200px]">
            <thead>
                <tr class="text-gray-700 border-b border-gray-200 bg-gray-50/50">
                    <th class="py-3 px-3">No</th>
                    <th class="py-3 px-3">Tanggal / Waktu</th>
                    <th class="py-3 px-3">Media/Metode Konsultasi</th>
                    <th class="py-3 px-3">Topik Konsultasi</th>
                    <th class="py-3 px-3">Refleksi Mahasiswa</th>
                    <th class="py-3 px-3">Saran / Umpan Balik Dosen</th>
                    <th class="py-3 px-3">Tindak Lanjut Mahasiswa</th>
                    <th class="py-3 px-3">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($riwayatKonsultasi ?? [] as $log)
                    <tr class="border-b border-gray-100 hover:bg-gray-50 align-top">
                        <td class="py-4 px-3 text-gray-500 font-medium">{{ $loop->iteration }}</td>
                        <td class="py-4 px-3 whitespace-nowrap">
                            <div class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($log->tanggal_konsul)->format('d M Y') }}</div>
                            <div class="text-xs text-gray-500 mt-0.5">
                                {{ $log->waktu_konsul ? \Carbon\Carbon::parse($log->waktu_konsul)->format('H.i') : '-' }} WIB
                            </div>
                        </td>
                        <td class="py-4 px-3 whitespace-nowrap">
                            <span class="inline-block bg-gray-100 text-gray-700 px-2.5 py-1 rounded-full text-xs font-semibold">
                                {{ $log->media_konsul }}
                            </span>
                        </td>
                        <td class="py-4 px-3 max-w-[200px]">
                            <p class="text-gray-800">{{ $log->topik_dibahas }}</p>
                        </td>
                        <td class="py-4 px-3 max-w-[200px]">
                            <p class="text-gray-700">{{ $log->refleksi_mahasiswa ?? '-' }}</p>
                        </td>
                        <td class="py-4 px-3 max-w-[200px]">
                            <p class="text-gray-700">{{ $log->saran_dosen ?? 'Belum diisi oleh dosen.' }}</p>
                        </td>
                        <td class="py-4 px-3 max-w-[200px]">
                            <p class="text-gray-700">{{ $log->tindak_lanjut_mahasiswa ?? 'Belum diisi.' }}</p>
                        </td>
                        <td class="py-4 px-3 whitespace-nowrap">
                            @if ($log->status_validasi === 'disetujui')
                                <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">Terverifikasi</span>
                            @elseif ($log->status_validasi === 'ditolak')
                                <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold">Ditolak</span>
                                @if ($log->alasan_penolakan)
                                    <p class="text-xs text-red-500 mt-1">{{ $log->alasan_penolakan }}</p>
                                @endif
                            @else
                                <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-800 text-xs font-bold">Menunggu</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="py-8 text-center text-gray-500">Belum ada riwayat bimbingan yang tercatat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection