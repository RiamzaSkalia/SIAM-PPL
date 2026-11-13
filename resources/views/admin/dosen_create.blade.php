@extends('layouts.admin')

@section('content')
<h3 class="fw-bold mb-4" style="color: #2b3990;">
    {{ isset($dosen) ? 'Edit Data Dosen' : 'Tambah Data Dosen' }}
</h3>

{{-- Notifikasi Error Melayang Responsif --}}
@if(session('error') || $errors->any())
    <div id="error-alert" class="alert alert-danger fade show rounded-4 d-flex align-items-center justify-content-between px-4 py-3 shadow-lg" 
         role="alert" 
         style="position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 9999; width: 92%; max-width: 500px; background-color: #dc3545; color: white; border: none; transition: all 0.5s ease;">
        <div class="d-flex align-items-center me-3">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i> 
            <span class="fw-semibold">{{ session('error') ?? $errors->first() }}</span>
        </div>
        <button type="button" class="btn-close btn-close-white ms-auto" onclick="closeErrorNotification()" aria-label="Close"></button>
    </div>
@endif

<div class="form-card p-3 p-sm-4" style="background-color: #f7ede2; border-radius: 12px;">
    <div class="d-flex justify-content-between align-items-start gap-2 mb-4 pb-2 border-bottom">
        <h5 class="fw-bold m-0" style="color: #2b3990;">
            ➕ {{ isset($dosen) ? 'Edit Data Dosen Pembimbing' : 'Tambah Dosen Pembimbing Baru' }}
        </h5>
        <a href="{{ route('admin.dosen.index') }}" class="text-decoration-none text-dark fs-4 fw-bold lh-1">&times;</a>
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
                <label class="fw-bold mb-1 fs-6">
                    Password Akun Login {{ isset($dosen) ? '(Isi Jika Ingin Mengubah)' : '*' }}
                </label>
                <div class="input-group">
                    <input type="text" name="password" id="passwordInput" class="form-control rounded-start-3 p-2 fw-bold" 
                           placeholder="{{ isset($dosen) ? 'Kosongkan jika tidak diubah' : 'Klik Shuffle' }}" 
                           {{ isset($dosen) ? '' : 'required' }}>
                    <button class="btn btn-warning fw-bold px-3 text-nowrap" type="button" onclick="generateShufflePassword()">
                        🔀 <span class="d-none d-sm-inline">Shuffle Password</span><span class="d-inline d-sm-none">Shuffle</span>
                    </button>
                    <button class="btn btn-outline-secondary rounded-end-3 px-3" type="button" onclick="togglePasswordVisibility()">👁️</button>
                </div>
                <small class="d-block text-muted mt-1">
                    {{ isset($dosen) 
                        ? '*Kosongkan jika tidak ingin mengganti password, atau klik Shuffle untuk buat password baru.' 
                        : '*Klik tombol Shuffle untuk membuat kombinasi password unik otomatis.' }}
                </small>
            </div>
        </div>

        {{-- Tombol Aksi Form Konsisten & Responsif --}}
        <div class="d-grid gap-2 d-sm-flex justify-content-sm-end pt-3 border-top">
            <a href="{{ route('admin.dosen.index') }}" 
               class="btn btn-secondary rounded-pill px-4 py-2 fw-bold d-inline-flex align-items-center justify-content-center text-nowrap">
                Batal
            </a>
            <button type="submit" 
                    class="btn btn-success rounded-pill px-4 py-2 fw-bold d-inline-flex align-items-center justify-content-center text-nowrap">
                💾 Simpan Data Dosen
            </button>
        </div>
    </form>
</div>

<script>
    const isEditMode = "{{ isset($dosen) ? 'true' : 'false' }}" === 'true';

    function generateShufflePassword() {
        const chars = "ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789";
        let newPassword = "";
        for (let i = 0; i < 8; i++) {
            newPassword += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        
        const passInput = document.getElementById('passwordInput');
        passInput.value = newPassword;
        passInput.type = 'text';
    }

    function togglePasswordVisibility() {
        const passInput = document.getElementById('passwordInput');
        passInput.type = passInput.type === 'password' ? 'text' : 'password';
    }

    function closeErrorNotification() {
        const alertElement = document.getElementById('error-alert');
        if (alertElement) {
            alertElement.style.opacity = '0';
            alertElement.style.transform = 'translate(-50%, -20px)';
            setTimeout(() => { alertElement.style.display = 'none'; }, 500);
        }
    }

    document.addEventListener("DOMContentLoaded", function () {
        const passInput = document.getElementById('passwordInput');
        if (!isEditMode && !passInput.value) {
            generateShufflePassword();
        }

        setTimeout(function() {
            closeErrorNotification();
        }, 5000);
    });
</script>
@endsection