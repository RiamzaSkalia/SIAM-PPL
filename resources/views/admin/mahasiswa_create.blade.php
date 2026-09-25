@extends('layouts.admin')

@section('content')
<h3 class="fw-bold mb-4">Data Mahasiswa</h3>

@if(session('success'))
    <div class="alert alert-success rounded-4">{{ session('success') }}</div>
@endif

<div class="form-card">
    <div class="form-card-header">
        + Tambah Data Mahasiswa AM
    </div>

    <form action="{{ route('admin.mahasiswa.store') }}" method="POST">
        @csrf
        <label class="fw-bold mb-1 fs-6">NIM (Untuk Akun Login)</label>
        <input type="text" name="nim" class="form-control form-control-custom" required>

        <label class="fw-bold mb-1 fs-6">Nama Lengkap Mahasiswa</label>
        <input type="text" name="nama_mahasiswa" class="form-control form-control-custom" required>

        <label class="fw-bold mb-1 fs-6">Pilih Periode Akademik Aktif</label>
        <select name="periode_id" class="form-control form-control-custom" required>
            <option value="">-- Pilih Periode --</option>
            @foreach($periode as $p)
                <option value="{{ $p->id }}">{{ $p->nama_periode }}</option>
            @endforeach
        </select>

        <label class="fw-bold mb-1 fs-6">Password Default (Opsional)</label>
        <input type="password" name="password" class="form-control form-control-custom">
        <small class="d-block text-muted mb-4" style="margin-top: -10px;">Jika dikosongkan, password default adalah NIM Mahasiswa.</small>

        <div class="d-flex justify-content-end gap-2">
            <button type="reset" class="btn-batal">BATAL</button>
            <button type="submit" class="btn-simpan">SIMPAN</button>
        </div>
    </form>
</div>
@endsection