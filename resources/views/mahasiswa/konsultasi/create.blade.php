@extends('layouts.mahasiswa')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h3 class="fw-bold m-0" style="color: #2b3990;">Tambah Log Bimbingan</h3>
        <p class="text-muted fs-6 m-0">Catat sesi konsultasi Anda bersama Dosen Pembimbing Lapangan</p>
    </div>
    <a href="{{ route('mahasiswa.konsultasi.index') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2 fw-bold" style="font-size: 14px;">
        <i class="bi bi-arrow-left me-1"></i> Lihat Riwayat
    </a>
</div>

@if ($errors->any())
    <div class="alert alert-danger rounded-4 p-3 mb-4 shadow-sm border-0">
        <ul class="m-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card card-custom p-4 shadow-sm">
    <form action="{{ route('mahasiswa.konsultasi.store') }}" method="POST">
        @csrf

        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <label class="form-label fw-bold">Tanggal Konsultasi <span class="text-danger">*</span></label>
                <input type="date" name="tanggal_konsul" class="form-control" value="{{ old('tanggal_konsul', date('Y-m-d')) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Waktu Konsultasi <span class="text-danger">*</span></label>
                <input type="time" name="waktu_konsul" class="form-control" value="{{ old('waktu_konsul', date('H:i')) }}" required>
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label fw-bold">Media / Metode Konsultasi <span class="text-danger">*</span></label>
            <select name="media_konsul" class="form-select" required>
                <option value="">-- Pilih media --</option>
                <option value="Tatap Muka" {{ old('media_konsul') == 'Tatap Muka' ? 'selected' : '' }}>Tatap Muka</option>
                <option value="WhatsApp" {{ old('media_konsul') == 'WhatsApp' ? 'selected' : '' }}>WhatsApp</option>
                <option value="Web Meeting (Zoom/GMeet)" {{ old('media_konsul') == 'Web Meeting (Zoom/GMeet)' ? 'selected' : '' }}>Web Meeting (Zoom/GMeet)</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="form-label fw-bold">Topik yang Dibahas <span class="text-danger">*</span></label>
            <textarea name="topik_dibahas" class="form-control" rows="3" placeholder="Contoh: Konsultasi penyusunan RPP dan modul pembelajaran" required>{{ old('topik_dibahas') }}</textarea>
        </div>

        <div class="mb-4">
            <label class="form-label fw-bold">Refleksi Mahasiswa <span class="text-danger">*</span></label>
            <small class="text-muted d-block mb-2 fs-6">Ceritakan pemahaman, kendala, atau refleksi Anda setelah mengikuti bimbingan...</small>
            <textarea name="refleksi_mahasiswa" class="form-control" rows="3" placeholder="Contoh: Memahami bahwa modul perlu disesuaikan dengan kurikulum sekolah mitra" required>{{ old('refleksi_mahasiswa') }}</textarea>
        </div>

        <div class="mb-4">
            <label class="form-label fw-bold">Tindak Lanjut Mahasiswa</label>
            <small class="text-muted d-block mb-2 fs-6">Langkah apa yang akan Anda lakukan setelah sesi bimbingan ini?</small>
            <textarea name="tindak_lanjut" class="form-control" rows="2" placeholder="Contoh: Melakukan revisi struktur modul sesuai saran dosen">{{ old('tindak_lanjut') }}</textarea>
        </div>

        <div class="mb-4">
            <label class="form-label fw-bold">Saran / Umpan Balik Dosen (Opsional)</label>
            <small class="text-muted d-block mb-2 fs-6">Dapat diisi jika Dosen sudah memberikan catatan lisan saat sesi konsultasi</small>
            <textarea name="saran_dosen" class="form-control" rows="2" placeholder="Contoh: Perjelas indikator capaian pembelajaran pada bab 2">{{ old('saran_dosen') }}</textarea>
        </div>

        <div class="d-flex justify-content-end gap-3 mt-4 pt-3 border-top">
            <a href="{{ route('mahasiswa.konsultasi.index') }}" class="btn btn-light rounded-pill px-4 py-2 fw-bold" style="font-size: 15px;">Batal</a>
            <button type="submit" class="btn text-white rounded-pill px-5 py-2 fw-bold" style="background-color: #3b31b2; font-size: 15px;">
                💾 Simpan Log Bimbingan
            </button>
        </div>
    </form>
</div>
@endsection