@extends('layouts.admin')

@section('content')
<h3 class="fw-bold mb-4" style="color: #2b3990;">
    {{ isset($dosen) ? 'Edit Data Dosen' : 'Data Dosen' }}
</h3>

<div class="form-card">
    <div class="form-card-header">
        {{ isset($dosen) ? '✏️ Edit Data Dosen Pembimbing' : '+ Tambah Data Dosen Pembimbing Baru' }}
    </div>

    <form action="{{ isset($dosen) ? route('admin.dosen.update', $dosen->id) : route('admin.dosen.store') }}" method="POST">
        @csrf
        @if(isset($dosen))
            @method('PUT')
        @endif

        <label class="fw-bold mb-1 fs-6">NIP / NIDN (Untuk Akun Login)</label>
        <input type="text" name="nip" class="form-control form-control-custom mb-3" value="{{ old('nip', $dosen->nip ?? '') }}" required>

        <label class="fw-bold mb-1 fs-6">Nama Dosen</label>
        <input type="text" name="nama_dosen" class="form-control form-control-custom mb-3" value="{{ old('nama_dosen', $dosen->nama_dosen ?? '') }}" required>

        <label class="fw-bold mb-1 fs-6">Email Instansi</label>
        <input type="email" name="email" class="form-control form-control-custom mb-3" value="{{ old('email', $dosen->email ?? '') }}" required>

        <label class="fw-bold mb-1 fs-6">Password Akun Login {{ isset($dosen) ? '(Isi Jika Ingin Mengubah)' : '' }}</label>
        <input type="password" name="password" class="form-control form-control-custom" {{ isset($dosen) ? '' : 'required' }}>
        <small class="d-block text-muted mb-4" style="margin-top: -5px;">
            {{ isset($dosen) ? 'Kosongkan jika tidak ingin mengganti password.' : 'Password ini akan digunakan dosen untuk login ke sistem.' }}
        </small>

        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('admin.dosen.index') }}" class="btn-batal text-decoration-none d-inline-block text-center" style="line-height: 28px;">BATAL</a>
            <button type="submit" class="btn-simpan">SIMPAN</button>
        </div>
    </form>
</div>
@endsection