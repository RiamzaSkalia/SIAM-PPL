@extends('layouts.admin')

@section('content')
<h3 class="fw-bold mb-4">Data Dosen</h3>

@if(session('success'))
    <div class="alert alert-success rounded-4">{{ session('success') }}</div>
@endif

<div class="form-card">
    <div class="form-card-header">
        + Tambah Data Dosen Pembimbing Baru
    </div>

    <form action="{{ route('admin.dosen.store') }}" method="POST">
        @csrf
        <label class="fw-bold mb-1 fs-6">NIP / NIDN</label>
        <input type="text" name="nip" class="form-control form-control-custom" required>

        <label class="fw-bold mb-1 fs-6">Nama Lengkap beserta Gelar</label>
        <input type="text" name="nama_dosen" class="form-control form-control-custom" required>

        <label class="fw-bold mb-1 fs-6">Email Instansi (Untuk Akun Login)</label>
        <input type="email" name="email" class="form-control form-control-custom" required>

        <label class="fw-bold mb-1 fs-6">Password Default (Opsional)</label>
        <input type="password" name="password" class="form-control form-control-custom">
        <small class="d-block text-muted mb-4" style="margin-top: -10px;">Jika dikosongkan, password default adalah NIP Dosen.</small>

        <div class="d-flex justify-content-end gap-2">
            <button type="reset" class="btn-batal">BATAL</button>
            <button type="submit" class="btn-simpan">SIMPAN</button>
        </div>
    </form>
</div>
@endsection