@extends('layouts.admin')

@section('content')
<h3 class="fw-bold mb-4" style="color: #2b3990;">
    {{ isset($mahasiswa) ? 'Edit Data Mahasiswa' : 'Data Mahasiswa' }}
</h3>

<div class="form-card">
    <div class="form-card-header">
        {{ isset($mahasiswa) ? '✏️ Edit Data Mahasiswa AM' : '+ Tambah Data Mahasiswa AM' }}
    </div>

    <form action="{{ isset($mahasiswa) ? route('admin.mahasiswa.update', $mahasiswa->id) : route('admin.mahasiswa.store') }}" method="POST">
        @csrf
        @if(isset($mahasiswa))
            @method('PUT')
        @endif

        <label class="fw-bold mb-1 fs-6">NIM (Untuk Akun Login)</label>
        <input type="text" name="nim" class="form-control form-control-custom mb-3" value="{{ old('nim', $mahasiswa->nim ?? '') }}" required>

        <label class="fw-bold mb-1 fs-6">Nama Lengkap Mahasiswa</label>
        <input type="text" name="nama_mahasiswa" class="form-control form-control-custom mb-3" value="{{ old('nama_mahasiswa', $mahasiswa->nama_mahasiswa ?? '') }}" required>

        <label class="fw-bold mb-1 fs-6">Pilih Periode Akademik Aktif</label>
        <select name="periode_id" class="form-control form-control-custom mb-3" required>
            <option value="">-- Pilih Periode --</option>
            @foreach($periode as $p)
                <option value="{{ $p->id }}" {{ (old('periode_id', $mahasiswa->periode_id ?? '') == $p->id) ? 'selected' : '' }}>
                    {{ $p->nama_periode }}
                </option>
            @endforeach
        </select>

        <label class="fw-bold mb-1 fs-6">Password Akun Login {{ isset($mahasiswa) ? '(Isi Jika Ingin Mengubah)' : '' }}</label>
        <input type="password" name="password" class="form-control form-control-custom" {{ isset($mahasiswa) ? '' : 'required' }}>
        <small class="d-block text-muted mb-4" style="margin-top: -5px;">
            {{ isset($mahasiswa) ? 'Kosongkan jika tidak ingin mengganti password.' : 'Password ini akan digunakan mahasiswa untuk login ke sistem.' }}
        </small>

        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('admin.mahasiswa.index') }}" class="btn-batal text-decoration-none d-inline-block text-center" style="line-height: 28px;">BATAL</a>
            <button type="submit" class="btn-simpan">SIMPAN</button>
        </div>
    </form>
</div>
@endsection