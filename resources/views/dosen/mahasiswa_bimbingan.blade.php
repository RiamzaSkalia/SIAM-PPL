@extends('layouts.dosen')

@section('title', 'Mahasiswa Bimbingan — Dosen Pembimbing')
@section('page-title', 'Mahasiswa Bimbingan')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    
    <!-- Card Utama -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-base font-extrabold text-gray-800 mb-6">Daftar Mahasiswa Bimbingan</h3>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-gray-600 border-b border-gray-100 bg-[#FFF8EE]/50 font-bold">
                        <th class="py-4 px-6 rounded-l-xl">Nama</th>
                        <th class="py-4 px-6">NIM</th>
                        <th class="py-4 px-6">Bimbingan</th>
                        <th class="py-4 px-6">Status</th>
                        <th class="py-4 px-6 rounded-r-xl text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($mahasiswas ?? [] as $mhs)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-4 px-6 font-extrabold text-gray-800">
                                {{ $mhs->nama_mahasiswa ?? $mhs->nama }}
                            </td>
                            <td class="py-4 px-6 text-gray-600 font-medium">
                                {{ $mhs->nim }}
                            </td>
                            <td class="py-4 px-6 text-gray-700 font-bold">
                                {{ $mhs->jumlah_bimbingan ?? 0 }}/5
                            </td>
                            <td class="py-4 px-6">
                                @if($mhs->status_memenuhi)
                                    <span class="inline-block bg-emerald-100 text-emerald-700 font-extrabold text-xs px-3 py-1 rounded-full">
                                        Memenuhi
                                    </span>
                                @else
                                    <span class="inline-block bg-amber-100 text-amber-800 font-extrabold text-xs px-3 py-1 rounded-full">
                                        Belum Memenuhi
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center">
                                <a href="{{ route('dosen.mahasiswa.detail', $mhs->id) }}" 
                                   class="inline-block bg-[#363E78] hover:bg-[#282f5c] text-white font-bold text-xs px-4 py-2 rounded-xl transition shadow-sm">
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-gray-400">
                                Belum ada mahasiswa yang masuk dalam plotting bimbingan Anda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection