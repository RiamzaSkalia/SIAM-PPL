@extends('layouts.admin')

@section('content')
<h3 class="fw-bold mb-4" style="color: #2b3990;">
    {{ isset($mahasiswa) ? 'Edit Data Mahasiswa' : 'Data Mahasiswa' }}
</h3>

<div class="form-card p-4" style="background-color: #f7ede2; border-radius: 12px;">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <h5 class="fw-bold m-0" style="color: #2b3990;">
            ➕ {{ isset($mahasiswa) ? 'Edit Data Mahasiswa AM' : 'Tambah Data Mahasiswa AM' }}
        </h5>
        <a href="{{ route('admin.mahasiswa.index') }}" class="text-decoration-none text-dark fs-5 fw-bold">&times;</a>
    </div>

    <form action="{{ isset($mahasiswa) ? route('admin.mahasiswa.update', $mahasiswa->id) : route('admin.mahasiswa.store') }}" method="POST">
        @csrf
        @if(isset($mahasiswa))
            @method('PUT')
        @endif

        {{-- SECTION 1: INFORMASI MAHASISWA --}}
        <div class="mb-4">
            <h6 class="fw-bold text-uppercase mb-3" style="color: #3b31b2;">INFORMASI MAHASISWA</h6>
            <hr class="mt-0 mb-3">

            <div class="mb-3">
                <label class="fw-bold mb-1 fs-6">NIM (Untuk Akun Login)<span class="text-danger">*</span></label>
                <input type="text" name="nim" class="form-control rounded-3 p-2" placeholder="Masukkan NIM Mahasiswa" value="{{ old('nim', $mahasiswa->nim ?? '') }}" required>
            </div>

            <div class="mb-3">
                <label class="fw-bold mb-1 fs-6">Nama Lengkap Mahasiswa <span class="text-danger">*</span></label>
                <input type="text" name="nama_mahasiswa" class="form-control rounded-3 p-2" placeholder="Masukkan Nama Lengkap" value="{{ old('nama_mahasiswa', $mahasiswa->nama_mahasiswa ?? '') }}" required>
            </div>

            <div class="row mb-3">
                <div class="col-md-6 mb-3 mb-md-0">
                    <label class="fw-bold mb-1 fs-6">Nomor HP / WhatsApp</label>
                    <input type="text" name="no_hp" class="form-control form-control-custom mb-3" placeholder="Contoh: 081234567890" value="{{ old('no_hp', $mahasiswa->no_hp ?? '') }}">
                </div>
                <div class="col-md-6">
                    <label class="fw-bold mb-1 fs-6">Periode Akademik Aktif <span class="text-danger">*</span></label>
                    <select name="periode_id" class="form-select rounded-3 p-2" required>
                        <option value="">-- Pilih Periode --</option>
                        @foreach($periode as $p)
                            <option value="{{ $p->id }}" {{ (old('periode_id', $mahasiswa->periode_id ?? '') == $p->id) ? 'selected' : '' }}>
                                {{ $p->nama_periode }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- SECTION 2: AKUN LOGIN MAHASISWA --}}
        <div class="mb-4">
            <h6 class="fw-bold text-uppercase mb-3" style="color: #3b31b2;">AKUN LOGIN MAHASISWA</h6>
            <hr class="mt-0 mb-3">

            <div class="mb-3">
                <label class="fw-bold mb-1 fs-6">Password Akun Login {{ isset($mahasiswa) ? '(Isi Jika Ingin Mengubah)' : 'Default *' }}</label>
                <div class="input-group">
                    <input type="password" name="password" id="passwordInput" class="form-control rounded-start-3 p-2" {{ isset($mahasiswa) ? '' : 'required' }}>
                    <button class="btn btn-outline-secondary rounded-end-3" type="button" onclick="togglePassword()">👁</button>
                </div>
                <small class="d-block text-muted mt-1">
                    {{ isset($mahasiswa) ? 'Kosongkan jika tidak ingin mengganti password.' : '*Password ini akan digunakan mahasiswa untuk login ke sistem.' }}
                </small>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
            <a href="{{ route('admin.mahasiswa.index') }}" class="btn btn-secondary rounded-pill px-4 fw-bold">Batal</a>
            <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold">💾 Simpan Data Mahasiswa</button>
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