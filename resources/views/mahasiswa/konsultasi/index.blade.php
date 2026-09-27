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
        <table class="w-full text-left text-sm min-w-[900px]">
            <thead>
                <tr class="text-gray-700 border-b border-gray-200">
                    <th class="py-3 px-4">No</th>
                    <th class="py-3 px-4">Tanggal</th>
                    <th class="py-3 px-4">Waktu</th>
                    <th class="py-3 px-4">Media</th>
                    <th class="py-3 px-4">Topik Konsultasi</th>
                    <th class="py-3 px-4">Status</th>
                    <th class="py-3 px-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($riwayatKonsultasi ?? [] as $log)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-3 px-4 text-gray-500">{{ $loop->iteration }}</td>
                        <td class="py-3 px-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($log->tanggal_konsul)->format('d M Y') }}</td>
                        <td class="py-3 px-4 whitespace-nowrap">
                            {{ $log->waktu_konsul ? \Carbon\Carbon::parse($log->waktu_konsul)->format('H.i') : '-' }}
                        </td>
                        <td class="py-3 px-4 whitespace-nowrap">{{ $log->media_konsul }}</td>
                        <td class="py-3 px-4 max-w-xs">{{ $log->topik_dibahas }}</td>
                        <td class="py-3 px-4 whitespace-nowrap">
                            @if ($log->status_validasi === 'disetujui')
                                <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">Terverifikasi</span>
                            @elseif ($log->status_validasi === 'ditolak')
                                <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold">Ditolak</span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-800 text-xs font-bold">Menunggu</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <button type="button"
                                    onclick="document.getElementById('detail-{{ $log->id }}').showModal()"
                                    class="border border-indigo-600 text-indigo-600 text-xs font-bold px-4 py-1.5 rounded-full hover:bg-indigo-600 hover:text-white transition">
                                Lihat
                            </button>

                            <dialog id="detail-{{ $log->id }}" class="rounded-2xl p-0 w-full max-w-md backdrop:bg-black/40">
                                <div class="p-6">
                                    <div class="flex items-start justify-between mb-4">
                                        <h3 class="text-lg font-extrabold text-gray-800">Detail Bimbingan</h3>
                                        <button type="button"
                                                onclick="document.getElementById('detail-{{ $log->id }}').close()"
                                                class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
                                    </div>

                                    <dl class="space-y-3 text-sm">
                                        <div>
                                            <dt class="text-gray-500">Tanggal</dt>
                                            <dd class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($log->tanggal_konsul)->format('d M Y') }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-gray-500">Waktu</dt>
                                            <dd class="font-semibold text-gray-800">
                                                {{ $log->waktu_konsul ? \Carbon\Carbon::parse($log->waktu_konsul)->format('H.i') : '-' }}
                                            </dd>
                                        </div>
                                        <div>
                                            <dt class="text-gray-500">Media</dt>
                                            <dd class="font-semibold text-gray-800">{{ $log->media_konsul }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-gray-500">Topik / Isi Konsultasi</dt>
                                            <dd class="text-gray-800">{{ $log->topik_dibahas }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-gray-500">Saran Dosen</dt>
                                            <dd class="text-gray-800">{{ $log->saran_dosen ?? '-' }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-gray-500">Status Verifikasi</dt>
                                            <dd>
                                                @if ($log->status_validasi === 'disetujui')
                                                    <span class="text-emerald-700 font-bold">Terverifikasi</span>
                                                @elseif ($log->status_validasi === 'ditolak')
                                                    <span class="text-red-700 font-bold">Ditolak</span>
                                                @else
                                                    <span class="text-amber-800 font-bold">Menunggu</span>
                                                @endif
                                            </dd>
                                        </div>
                                    </dl>

                                    <button type="button"
                                            onclick="document.getElementById('detail-{{ $log->id }}').close()"
                                            class="mt-6 w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 rounded-full transition">
                                        Tutup
                                    </button>
                                </div>
                            </dialog>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-gray-500">Belum ada riwayat bimbingan yang tercatat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
    dialog::backdrop { background: rgba(0,0,0,0.4); }
    dialog { border: none; }
</style>
@endsection