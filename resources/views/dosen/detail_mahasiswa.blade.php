@extends('layouts.dosen')

@section('title', 'Detail Mahasiswa Bimbingan')
@section('page-title', 'Detail Mahasiswa Bimbingan')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    
    <!-- Informasi Mahasiswa -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-xl font-extrabold text-gray-800">{{ $mahasiswa->nama_mahasiswa ?? $mahasiswa->nama }}</h2>
            <p class="text-sm text-gray-500 mt-1">NIM: <span class="font-bold text-gray-700">{{ $mahasiswa->nim }}</span></p>
            <p class="text-sm text-gray-500 mt-0.5">Total Bimbingan Disetujui: <span class="font-bold text-gray-700">{{ $jumlahBimbingan }}/5</span></p>
        </div>
        <div class="flex items-center gap-3">
            <div>
                @if($statusMemenuhi)
                    <span class="inline-block bg-emerald-100 text-emerald-700 font-extrabold text-xs px-4 py-2 rounded-xl">
                        Status: Memenuhi
                    </span>
                @else
                    <span class="inline-block bg-amber-100 text-amber-800 font-extrabold text-xs px-4 py-2 rounded-xl">
                        Status: Belum Memenuhi
                    </span>
                @endif
            </div>

            <!-- Tombol Cetak Kartu (Aktif jika >= 5) -->
            @if($statusMemenuhi)
                <a href="#" target="_blank" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-5 py-2.5 rounded-xl transition shadow-sm inline-flex items-center gap-2">
                    🖨️ Cetak Kartu
                </a>
            @else
                <button disabled class="bg-gray-200 text-gray-400 font-bold text-xs px-5 py-2.5 rounded-xl cursor-not-allowed">
                    Cetak Kartu (Belum 5/5)
                </button>
            @endif
        </div>
    </div>

    <!-- Riwayat & Log Konsultasi -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
        <h3 class="text-base font-extrabold text-gray-800">Riwayat Konsultasi & Verifikasi</h3>

        <div class="space-y-4">
            @forelse($konsultasis as $log)
                <div class="border border-gray-100 bg-gray-50/50 rounded-2xl p-5 space-y-3">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 border-b border-gray-100 pb-3">
                        <div>
                            <span class="text-xs font-bold text-indigo-600 uppercase tracking-wider">{{ $log->media ?? 'Tatap Muka' }}</span>
                            <h4 class="text-sm font-extrabold text-gray-800 mt-0.5">Topik: {{ $log->topik_dibahas }}</h4>
                        </div>
                        <div>
                            @if($log->status_validasi == 'disetujui')
                                <span class="bg-emerald-100 text-emerald-700 text-xs font-extrabold px-3 py-1 rounded-full">Disetujui</span>
                            @elseif($log->status_validasi == 'ditolak')
                                <span class="bg-rose-100 text-rose-700 text-xs font-extrabold px-3 py-1 rounded-full">Ditolak</span>
                            @else
                                <span class="bg-amber-100 text-amber-800 text-xs font-extrabold px-3 py-1 rounded-full">Menunggu Verifikasi</span>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-xs text-gray-400 font-bold">Tanggal & Waktu:</p>
                            <p class="text-gray-700 font-medium mt-0.5">{{ \Carbon\Carbon::parse($log->tanggal_konsul)->format('d F Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-bold">Hasil / Saran Dosen:</p>
                            <p class="text-gray-700 font-medium mt-0.5">{{ $log->saran_dosen ?? 'Belum ada catatan saran.' }}</p>
                        </div>
                    </div>

                    <!-- Tombol Aksi Verifikasi Dosen -->
                    @if($log->status_validasi == 'pending')
                        <div class="flex items-center gap-2 pt-2 border-t border-gray-100">
                            <form action="{{ route('dosen.konsultasi.setujui', $log->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-4 py-2 rounded-xl transition shadow-sm">
                                    Setujui
                                </button>
                            </form>
                            <form action="{{ route('dosen.konsultasi.tolak', $log->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs px-4 py-2 rounded-xl transition shadow-sm">
                                    Tolak
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @empty
                <div class="text-center py-12 text-gray-400 text-sm">
                    Belum ada riwayat log bimbingan yang diajukan oleh mahasiswa ini.
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection