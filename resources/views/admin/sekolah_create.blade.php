@extends('layouts.admin')

@section('content')
<h3 class="fw-bold mb-4" style="color: #2b3990;">
    {{ isset($sekolah) ? 'Edit Sekolah Mitra' : 'Data Sekolah' }}
</h3>

<div class="form-card p-4" style="background-color: #f7ede2; border-radius: 12px;">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <h5 class="fw-bold m-0" style="color: #2b3990;">
            🏫 {{ isset($sekolah) ? 'Edit Data Sekolah Mitra' : 'Tambah Sekolah Mitra Baru' }}
        </h5>
        <a href="{{ route('admin.sekolah.index') }}" class="text-decoration-none text-dark fs-5 fw-bold">&times;</a>
    </div>

    <form action="{{ isset($sekolah) ? route('admin.sekolah.update', $sekolah->id) : route('admin.sekolah.store') }}" method="POST">
        @csrf
        @if(isset($sekolah))
            @method('PUT')
        @endif

        <div class="mb-3">
            <label class="fw-bold mb-1 fs-6">NPSN Sekolah <span class="text-danger">*</span></label>
            <input type="text" name="npsn" class="form-control rounded-3 p-2" placeholder="Contoh: 30304561" value="{{ old('npsn', $sekolah->npsn ?? '') }}" required>
            <small class="text-muted">*NPSN ini akan digunakan Guru Pamong untuk mendaftar/masuk ke sistem.</small>
        </div>

        <div class="mb-3">
            <label class="fw-bold mb-1 fs-6">Nama Sekolah Mitra <span class="text-danger">*</span></label>
            <input type="text" name="nama_sekolah" class="form-control rounded-3 p-2" placeholder="SMPN 21 Banjarmasin" value="{{ old('nama_sekolah', $sekolah->nama_sekolah ?? '') }}" required>
        </div>

        <div class="row mb-3">
            <div class="col-md-6 mb-3 mb-md-0">
                <label class="fw-bold mb-1 fs-6">Jenjang Pendidikan <span class="text-danger">*</span></label>
                <select name="jenjang" class="form-select rounded-3 p-2" required>
                    <option value="">-- Pilih Jenjang --</option>
                    <option value="SMP/MTS" {{ (old('jenjang', $sekolah->jenjang ?? '') == 'SMP/MTS') ? 'selected' : '' }}>SMP / MTS</option>
                    <option value="SMA/MA" {{ (old('jenjang', $sekolah->jenjang ?? '') == 'SMA/MA') ? 'selected' : '' }}>SMA / MA / SMK</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="fw-bold mb-1 fs-6">Kuota Mahasiswa <span class="text-danger">*</span></label>
                <input type="number" name="kuota" class="form-control rounded-3 p-2" placeholder="2" min="1" value="{{ old('kuota', $sekolah->kuota ?? '2') }}" required>
            </div>
        </div>

        <div class="mb-4">
            <label class="fw-bold mb-1 fs-6">Alamat Sekolah</label>
            <input type="text" name="alamat" class="form-control rounded-3 p-2" placeholder="Jl. Raden Cakra, Banjarmasin" value="{{ old('alamat', $sekolah->alamat ?? '') }}">
        </div>

        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
            <a href="{{ route('admin.sekolah.index') }}" class="btn btn-secondary rounded-pill px-4 fw-bold">Batal</a>
            <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold">💾 Simpan Data Sekolah</button>
        </div>
    </form>
</div>
@endsection