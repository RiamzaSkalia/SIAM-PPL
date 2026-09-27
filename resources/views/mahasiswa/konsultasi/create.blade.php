@extends('layouts.mahasiswa')

@section('title', 'Tambah Log Bimbingan')
@section('page-title', 'Tambah Log Bimbingan')

@section('content')
<div class="mb-6">
    <p class="text-sm text-gray-500 mt-1">Catat kegiatan bimbingan terbaru Anda</p>
</div>
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Kotak Informasi --}}
    <div class="bg-blue-50 border border-blue-200 rounded-2xl p-5 text-blue-900 shadow-sm flex items-start gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <div class="text-sm">
            <p class="font-bold mb-1">Informasi Pengisian Log</p>
            Data yang Anda simpan akan otomatis masuk ke riwayat konsultasi dan menambah jumlah bimbingan Anda. Status awal adalah <span class="font-semibold text-blue-700">Menunggu Verifikasi</span> hingga dosen pembimbing melakukan verifikasi.
        </div>
    </div>

    {{-- Notifikasi Error Validasi --}}
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-2xl p-4 text-red-700 text-sm">
            <p class="font-bold mb-1">Mohon periksa kembali formulir Anda:</p>
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Tambah Log Bimbingan --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <div class="mb-6">
            <h2 class="text-xl font-extrabold text-gray-800">Tambah Log Bimbingan</h2>
            <p class="text-sm text-gray-500 mt-1">Catat kegiatan bimbingan terbaru Anda</p>
        </div>

        <form action="{{ route('mahasiswa.konsultasi.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Baris 1: Tanggal & Waktu Bimbingan --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Bimbingan <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_konsul" value="{{ old('tanggal_konsul', date('Y-m-d')) }}" required
                           class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Waktu Bimbingan <span class="text-red-500">*</span></label>
                    <input type="time" name="waktu_konsul" value="{{ old('waktu_konsul', date('H:i')) }}" required
                           class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>

            {{-- Media Bimbingan --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Media Bimbingan <span class="text-red-500">*</span></label>
                <select name="media_konsul" required
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="" disabled selected>Pilih media bimbingan...</option>
                    <option value="Tatap Muka" {{ old('media_konsul') == 'Tatap Muka' ? 'selected' : '' }}>Tatap Muka</option>
                    <option value="Daring" {{ old('media_konsul') == 'Daring' ? 'selected' : '' }}>Daring</option>
                </select>
            </div>

            {{-- Topik / Isi Konsultasi --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Topik / Isi Konsultasi <span class="text-red-500">*</span></label>
                <textarea name="topik_dibahas" rows="4" required
                          placeholder="Jelaskan topik atau isi konsultasi yang dibahas ada sesi bimbingan ini..."
                          class="w-full rounded-xl border border-gray-300 p-4 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('topik_dibahas') }}</textarea>
            </div>

            {{-- Saran / Hasil Bimbingan dari Dosen --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Saran / Hasil Bimbingan dari Dosen <span class="text-red-500">*</span></label>
                <textarea name="saran_dosen" rows="4" required
                          placeholder="Tuliskan saran, masukan, atau hasil bimbingan yang diberikan oleh dosen pembimbing..."
                          class="w-full rounded-xl border border-gray-300 p-4 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('saran_dosen') }}</textarea>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100">
                <button type="submit"
                        class="px-8 py-3 rounded-full bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold shadow-md transition">
                    Simpan Bimbingan
                </button>
                <a href="{{ route('mahasiswa.dashboard') }}"
                   class="px-6 py-3 rounded-full border border-indigo-600 text-indigo-600 hover:bg-indigo-50 text-sm font-bold transition">
                    Batal
                </a>
                
            </div>
        </form>
    </div>
</div>
@endsection