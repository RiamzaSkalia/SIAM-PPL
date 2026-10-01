@extends('layouts.admin')

@section('content')
<h3 class="fw-bold mb-4" style="color: #2b3990;">
    {{ isset($sekolah) ? 'Edit Sekolah Mitra & Guru Pamong' : 'Data Sekolah' }}
</h3>

<div class="form-card p-4" style="background-color: #f7ede2; border-radius: 12px;">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <h5 class="fw-bold m-0" style="color: #2b3990;">
            ➕ {{ isset($sekolah) ? 'Edit Sekolah Mitra & Guru Pamong' : 'Tambah Sekolah Mitra & Guru Pamong' }}
        </h5>
        <a href="{{ route('admin.sekolah.index') }}" class="text-decoration-none text-dark fs-5 fw-bold">&times;</a>
    </div>

    <form action="{{ isset($sekolah) ? route('admin.sekolah.update', $sekolah->id) : route('admin.sekolah.store') }}" method="POST">
        @csrf
        @if(isset($sekolah))
            @method('PUT')
        @endif

        {{-- SECTION 1: INFORMASI SEKOLAH MITRA --}}
        <div class="mb-4">
            <h6 class="fw-bold text-uppercase mb-3" style="color: #3b31b2;">🏫 INFORMASI SEKOLAH MITRA</h6>
            <hr class="mt-0 mb-3">

            <div class="mb-3">
                <label class="fw-bold mb-1 fs-6">Nama Sekolah Mitra <span class="text-danger">*</span></label>
                <input type="text" name="nama_sekolah" class="form-control rounded-3 p-2" placeholder="SMPN 21 Banjarmasin" value="{{ old('nama_sekolah', $sekolah->nama_sekolah ?? '') }}" required>
            </div>

            <div class="row mb-3">
                <div class="col-md-6 mb-3 mb-md-0">
                    <label class="fw-bold mb-1 fs-6">Jenjang Pendidikan <span class="text-danger">*</span></label>
                    <select name="jenjang" class="form-select rounded-3 p-2" required>
                        <option value="">-- Pilih Jenjang --</option>
                        <option value="SD/MI" {{ (old('jenjang', $sekolah->jenjang ?? '') == 'SD/MI') ? 'selected' : '' }}>SD / MI</option>
                        <option value="SMP/MTS" {{ (old('jenjang', $sekolah->jenjang ?? '') == 'SMP/MTS') ? 'selected' : '' }}>SMP / MTS</option>
                        <option value="SMA/MA" {{ (old('jenjang', $sekolah->jenjang ?? '') == 'SMA/MA') ? 'selected' : '' }}>SMA / MA</option>
                        <option value="SMK" {{ (old('jenjang', $sekolah->jenjang ?? '') == 'SMK') ? 'selected' : '' }}>SMK</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="fw-bold mb-1 fs-6">Kuota Mahasiswa <span class="text-danger">*</span></label>
                    <input type="number" name="kuota" class="form-control rounded-3 p-2" placeholder="2" min="1" value="{{ old('kuota', $sekolah->kuota ?? '2') }}" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="fw-bold mb-1 fs-6">Alamat Sekolah</label>
                <input type="text" name="alamat" class="form-control rounded-3 p-2" placeholder="Jl. Raden Cakra, Banjarmasin" value="{{ old('alamat', $sekolah->alamat ?? '') }}">
            </div>
        </div>

        {{-- SECTION 2: AKUN & DATA GURU PAMONG --}}
        <div class="mb-4">
            <h6 class="fw-bold text-uppercase mb-3" style="color: #3b31b2;">👩‍🏫 AKUN & DATA GURU PAMONG (UNTUK LOGIN PAMONG)</h6>
            <hr class="mt-0 mb-3">

            <div class="mb-3">
                <label class="fw-bold mb-1 fs-6">NIP / NIK Guru Pamong <span class="text-danger">*</span></label>
                <input type="text" name="nip_nik" class="form-control rounded-3 p-2" placeholder="198805122015032001" value="{{ old('nip_nik', $sekolah->guruPamong->nip_nik ?? '') }}" required>
            </div>

            <div class="mb-3">
                <label class="fw-bold mb-1 fs-6">Nama Lengkap Guru Pamong <span class="text-danger">*</span></label>
                <input type="text" name="nama_guru_pamong" class="form-control rounded-3 p-2" placeholder="Putri Ayu S.B., S.Pd" value="{{ old('nama_guru_pamong', $sekolah->guruPamong->nama_guru_pamong ?? '') }}" required>
            </div>

            <div class="mb-3">
                <label class="fw-bold mb-1 fs-6">Nomor Telepon / WhatsApp Guru Pamong</label>
                <input type="text" name="no_hp" class="form-control rounded-3 p-2" placeholder="085389588049" value="{{ old('no_hp', $sekolah->guruPamong->no_hp ?? '') }}">
            </div>

            <div class="mb-3">
                <label class="fw-bold mb-1 fs-6">Password Login {{ isset($sekolah) ? '(Isi Jika Ingin Mengubah)' : 'Default *' }}</label>
                <div class="input-group">
                    <input type="password" name="password" id="passwordInput" class="form-control rounded-start-3 p-2" {{ isset($sekolah) ? '' : 'required' }}>
                    <button class="btn btn-outline-secondary rounded-end-3" type="button" onclick="togglePassword()">👁️️</button>
                </div>
                <small class="d-block text-muted mt-1">
                    {{ isset($sekolah) ? 'Kosongkan jika tidak ingin mengganti password.' : '*Password ini akan digunakan Guru Pamong untuk login ke sistem SIAM.' }}
                </small>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
            <a href="{{ route('admin.sekolah.index') }}" class="btn btn-secondary rounded-pill px-4 fw-bold">Batal</a>
            <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold">💾 Simpan Data Mitra</button>
        </div>
    </form>
</div>

<script>
    function togglePassword() {
        const passInput = document.getElementById('passwordInput');
        if (passInput.type === 'password') {
            passInput.type = 'text';
        } else {
            passInput.type = 'password';
        }
    }
</script>
@endsection