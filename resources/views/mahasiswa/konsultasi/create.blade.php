@extends('layouts.mahasiswa')

@section('title', 'Tambah Log Bimbingan')
@section('page-title', 'Tambah Log Bimbingan')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 max-w-3xl">

    @if ($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('mahasiswa.konsultasi.store') }}" class="space-y-5">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label class="block font-bold text-gray-800 mb-2">Tanggal Konsultasi <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal_konsul" value="{{ old('tanggal_konsul', date('Y-m-d')) }}" required
                       class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <label class="block font-bold text-gray-800 mb-2">Waktu <span class="text-red-500">*</span></label>
                <input type="time" name="waktu_konsul" value="{{ old('waktu_konsul', date('H:i')) }}" required
                       class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>
        </div>

        <div>
            <label class="block font-bold text-gray-800 mb-2">Media / Metode Konsultasi <span class="text-red-500">*</span></label>
            <select name="media_konsul" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">-- Pilih media --</option>
                <option value="Tatap Muka" {{ old('media_konsul') === 'Tatap Muka' ? 'selected' : '' }}>Tatap Muka</option>
                <option value="WhatsApp" {{ old('media_konsul') === 'WhatsApp' ? 'selected' : '' }}>WhatsApp</option>
                <option value="Web Meeting" {{ old('media_konsul') === 'Web Meeting' ? 'selected' : '' }}>Web Meeting</option>
                <option value="Daring" {{ old('media_konsul') === 'Daring' ? 'selected' : '' }}>Daring (lainnya)</option>
            </select>
        </div>

        <div>
            <label class="block font-bold text-gray-800 mb-2">Topik yang Dibahas <span class="text-red-500">*</span></label>
            <textarea name="topik_dibahas" rows="2" required
                      class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                      placeholder="Contoh: Konsultasi modul pembelajaran">{{ old('topik_dibahas') }}</textarea>
        </div>

        <div>
            <label class="block font-bold text-gray-800 mb-2">Refleksi Mahasiswa <span class="text-red-500">*</span></label>
            <p class="text-xs text-gray-500 mb-2">Ceritakan hal yang Anda pahami, kendala yang dibahas, atau refleksi Anda setelah mengikuti bimbingan...</p>
            <textarea name="refleksi_mahasiswa" rows="3" required
                      class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                      placeholder="Contoh: Memahami bahwa modul perlu disusun secara sistematis dan disesuaikan dengan tujuan pembelajaran">{{ old('refleksi_mahasiswa') }}</textarea>
        </div>

        <div>
            <label class="block font-bold text-gray-800 mb-2">Tindak Lanjut Mahasiswa</label>
            <p class="text-xs text-gray-500 mb-2">Langkah apa yang akan kamu lakukan setelah bimbingan ini?</p>
            <textarea name="tindak_lanjut_mahasiswa" rows="3"
                      class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                      placeholder="Contoh: Melakukan revisi struktur dan isi modul sesuai arahan dosen pembimbing">{{ old('tindak_lanjut_mahasiswa') }}</textarea>
        </div>

        <div>
            <label class="block font-bold text-gray-800 mb-2">Saran Dosen</label>
            <p class="text-xs text-gray-500 mb-2">Opsional, bisa diisi jika dosen sudah memberi saran langsung saat sesi ini</p>
            <textarea name="saran_dosen" rows="2"
                      class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                      placeholder="Contoh: Modul perlu diperbaiki pada bagian penyajian materi">{{ old('saran_dosen') }}</textarea>
        </div>

        <div class="sm:w-1/2">
            <label class="block font-bold text-gray-800 mb-2">Paraf Mahasiswa</label>
            <p class="text-xs text-gray-500 mb-2">Ketik nama/inisial sebagai tanda tangan digital</p>
            <input type="text" name="paraf_mahasiswa" value="{{ old('paraf_mahasiswa') }}"
                   class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                   placeholder="Contoh: Ersa M.">
        </div>

        <div class="flex justify-end gap-3 pt-2">
            <a href="{{ route('mahasiswa.konsultasi.index') }}"
               class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold px-8 py-2.5 rounded-full transition">
                Batal
            </a>
            <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-8 py-2.5 rounded-full transition">
                Simpan Log Bimbingan
            </button>
        </div>
    </form>
</div>
@endsection