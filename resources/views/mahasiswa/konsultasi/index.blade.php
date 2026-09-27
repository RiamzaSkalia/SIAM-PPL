@extends('layouts.mahasiswa')

@section('title', 'Riwayat Bimbingan')
@section('page-title', 'Riwayat Bimbingan')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-extrabold text-gray-800">Riwayat Bimbingan</h2>
            <p class="text-sm text-gray-500">Daftar seluruh catatan konsultasi bimbingan Anda</p>
        </div>
        <a href="{{ route('mahasiswa.konsultasi.create') }}" 
           class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-full text-sm font-bold shadow transition">
            + Tambah Log Baru
        </a>
    </div>

    {{-- Tabel Riwayat --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="text-gray-700 border-b border-gray-200">
                    <th class="py-3 px-4">Tanggal</th>
                    <th class="py-3 px-4">Waktu</th>
                    <th class="py-3 px-4">Media</th>
                    <th class="py-3 px-4">Topik</th>
                    <th class="py-3 px-4">Status</th>
                </tr>
            </thead>
            <tbody>
                {{-- Asumsi data dikirim dari method index di KonsultasiController --}}
                @forelse ($riwayatKonsultasi ?? [] as $log)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-3 px-4">{{ \Carbon\Carbon::parse($log->tanggal_konsul)->format('d M Y') }}</td>
                        <td class="py-3 px-4">{{ $log->waktu_konsul }}</td>
                        <td class="py-3 px-4">{{ $log->media_konsul }}</td>
                        <td class="py-3 px-4">{{ $log->topik_dibahas }}</td>
                        <td class="py-3 px-4">
                            @if ($log->status_validasi === 'disetujui')
                                <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">Disetujui</span>
                            @elseif ($log->status_validasi === 'ditolak')
                                <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold">Ditolak</span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-800 text-xs font-bold">Menunggu Verifikasi</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-gray-500">Belum ada riwayat bimbingan yang tercatat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection