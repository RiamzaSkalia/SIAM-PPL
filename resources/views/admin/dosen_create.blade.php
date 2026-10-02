@extends('layouts.admin')

@section('content')
<h3 class="fw-bold mb-4" style="color: #2b3990;">
    {{ isset($dosen) ? 'Edit Data Dosen' : 'Data Dosen' }}
</h3>

<div class="form-card p-4" style="background-color: #f7ede2; border-radius: 12px;">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <h5 class="fw-bold m-0" style="color: #2b3990;">
            ➕ {{ isset($dosen) ? 'Edit Data Dosen Pembimbing' : 'Tambah Dosen Pembimbing Baru' }}
        </h5>
        <a href="{{ route('admin.dosen.index') }}" class="text-decoration-none text-dark fs-5 fw-bold">&times;</a>
    </div>

    <form action="{{ isset($dosen) ? route('admin.dosen.update', $dosen->id) : route('admin.dosen.store') }}" method="POST">
        @csrf
        @if(isset($dosen))
            @method('PUT')
        @endif

        {{-- SECTION 1: INFORMASI DATA DOSEN --}}
        <div class="mb-4">
            <h6 class="fw-bold text-uppercase mb-3" style="color: #3b31b2;">INFORMASI DOSEN PEMBIMBING</h6>
            <hr class="mt-0 mb-3">

            <div class="mb-3">
                <label class="fw-bold mb-1 fs-6">NIP / NIDN (Untuk Akun Login)<span class="text-danger">*</span></label>
                <input type="text" name="nip" class="form-control rounded-3 p-2" placeholder="198501152010121002" value="{{ old('nip', $dosen->nip ?? '') }}" required>
            </div>

            <div class="mb-3">
                <label class="fw-bold mb-1 fs-6">Nama Lengkap Dosen <span class="text-danger">*</span></label>
                <input type="text" name="nama_dosen" class="form-control rounded-3 p-2" placeholder="Dr. Haris Pratama, M.Kom." value="{{ old('nama_dosen', $dosen->nama_dosen ?? '') }}" required>
            </div>

            <div class="mb-3">
                <label class="fw-bold mb-1 fs-6">Email Instansi <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control rounded-3 p-2" placeholder="dosen@ulm.ac.id" value="{{ old('email', $dosen->email ?? '') }}" required>
            </div>
        </div>

        {{-- SECTION 2: AKUN LOGIN DOSEN --}}
        <div class="mb-4">
            <h6 class="fw-bold text-uppercase mb-3" style="color: #3b31b2;">AKUN LOGIN DOSEN</h6>
            <hr class="mt-0 mb-3">

            <div class="mb-3">
                <label class="fw-bold mb-1 fs-6">Password Login {{ isset($dosen) ? '(Isi Jika Ingin Mengubah)' : 'Default *' }}</label>
                <div class="input-group">
                    <input type="password" name="password" id="passwordInput" class="form-control rounded-start-3 p-2" {{ isset($dosen) ? '' : 'required' }}>
                    <button class="btn btn-outline-secondary rounded-end-3" type="button" onclick="togglePassword()">👁</button>
                </div>
                <small class="d-block text-muted mt-1">
                    {{ isset($dosen) ? 'Kosongkan jika tidak ingin mengganti password.' : '*Password ini akan digunakan dosen untuk login ke sistem.' }}
                </small>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
            <a href="{{ route('admin.dosen.index') }}" class="btn btn-secondary rounded-pill px-4 fw-bold">Batal</a>
            <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold">💾 Simpan Data Dosen</button>
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