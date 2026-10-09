@extends('layouts.admin')

@section('content')
<h3 class="fw-bold mb-1" style="color: #2b3990;">⚙️ Pengaturan Sistem & Konfigurasi Dokumen</h3>
<p class="text-muted mb-4">Kelola periode akademik, serta template Kartu Konsultasi PDF.</p>

@if(session('success'))
    <div id="success-alert" class="alert alert-success fade show rounded-4 d-flex align-items-center justify-content-between px-4 py-3 shadow-lg" 
         role="alert" 
         style="position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 9999; min-width: 320px; max-width: 450px; background-color: #28a745; color: white; border: none;">
        <div class="d-flex align-items-center me-3">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i> 
            <span class="fw-semibold">{{ session('success') }}</span>
        </div>
        <button type="button" class="btn-close btn-close-white ms-auto" onclick="this.parentElement.remove()"></button>
    </div>
@endif

{{-- SECTION 1: KELOLA PERIODE AKADEMIK --}}
<div class="p-4 mb-4" style="background-color: #f7ede2; border-radius: 12px;">
    <h6 class="fw-bold mb-3" style="color: #2b3990;">📅 KELOLA PERIODE AKADEMIK ASISTENSI MENGAJAR</h6>
    <hr>

    <form action="{{ route('admin.pengaturan.periode.aktif') }}" method="POST" class="row align-items-center mb-4">
        @csrf
        <label class="col-md-3 fw-bold">Periode Aktif Saat Ini :</label>
        <div class="col-md-6">
            <select name="periode_id" class="form-select rounded-pill" onchange="this.form.submit()">
                @foreach($periodeList as $p)
                    <option value="{{ $p->id }}" {{ $p->status == 'aktif' ? 'selected' : '' }}>
                        {{ $p->nama_periode }} {{ $p->status == 'aktif' ? '(Aktif)' : '' }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>

    <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="fw-bold">Daftar Periode:</span>
        <button type="button" class="btn btn-sm btn-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalTambahPeriode">
            ➕ Tambah Periode Baru
        </button>
    </div>

    <ul class="list-group rounded-3">
        @foreach($periodeList as $p)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong>• {{ $p->nama_periode }}</strong> 
                    <span class="text-muted ms-3">| {{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('d M Y') }}</span>
                </div>
                <div>
                    Status: 
                    @if($p->status == 'aktif')
                        <span class="badge bg-success rounded-pill px-3 py-1">AKTIF 🟢</span>
                    @else
                        <span class="badge bg-danger rounded-pill px-3 py-1">SELESAI 🔴</span>
                    @endif
                </div>
            </li>
        @endforeach
    </ul>
</div>

{{-- SECTION 2: TEMPLATE KOP SURAT --}}
<div class="p-4" style="background-color: #f7ede2; border-radius: 12px;">
    <h6 class="fw-bold mb-3" style="color: #2b3990;">📄 TEMPLATE CETAK KARTU KONSULTASI PDF (KOP SURAT)</h6>
    <hr>

    <form action="{{ route('admin.pengaturan.update') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="fw-bold mb-1 small">Header Teks Kop Surat Baris 1:</label>
            <input type="text" name="header_1" class="form-control rounded-3 mb-2" value="{{ old('header_1', $pengaturan->header_1 ?? $pengaturan->header_line_1 ?? 'KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI') }}" placeholder="Baris 1" required>
        </div>

        <div class="mb-3">
            <label class="fw-bold mb-1 small">Header Teks Kop Surat Baris 2:</label>
            <input type="text" name="header_2" class="form-control rounded-3 mb-2" value="{{ old('header_2', $pengaturan->header_2 ?? $pengaturan->header_line_2 ?? 'UNIVERSITAS LAMBUNG MANGKURAT') }}" placeholder="Baris 2" required>
        </div>

        <div class="mb-3">
            <label class="fw-bold mb-1 small">Header Teks Kop Surat Baris 3:</label>
            <input type="text" name="header_3" class="form-control rounded-3 mb-2" value="{{ old('header_3', $pengaturan->header_3 ?? $pengaturan->header_line_3 ?? 'FAKULTAS KEGURUAN DAN ILMU PENDIDIKAN') }}" placeholder="Baris 3" required>
        </div>

        <div class="mb-3">
            <label class="fw-bold mb-1 small">Header Teks Kop Surat Baris 4:</label>
            <input type="text" name="header_4" class="form-control rounded-3 mb-2" value="{{ old('header_4', $pengaturan->header_4 ?? $pengaturan->header_line_4 ?? 'JURUSAN PENDIDIKAN KOMPUTER') }}" placeholder="Baris 4" required>
        </div>

        <div class="mb-4">
            <label class="fw-bold mb-1 small">Alamat / Kontak (Baris 5):</label>
            <input type="text" name="header_5" class="form-control rounded-3" value="{{ old('header_5', $pengaturan->header_5 ?? $pengaturan->header_line_5 ?? 'Jalan Brigjen H. Hasan Basry Banjarmasin 70123 Telepon: (0511) 3304914, Laman: pilkom.ulm.ac.id, Email: pilkom@ulm.ac.id') }}" placeholder="Baris 5 (Alamat / Kontak)" required>
        </div>

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold">💾 Simpan Perubahan</button>
        </div>
    </form>
</div>

{{-- MODAL TAMBAH PERIODE --}}
<div class="modal fade" id="modalTambahPeriode" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 p-4 border-0">
            <h5 class="fw-bold mb-3" style="color: #2b3990;">➕ Tambah Periode Akademik Baru</h5>
            <form action="{{ route('admin.pengaturan.periode.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="fw-bold mb-1">Nama Periode</label>
                    <input type="text" name="nama_periode" class="form-control rounded-3" placeholder="Contoh: 2026/2027 Genap" required>
                </div>
                <div class="mb-3">
                    <label class="fw-bold mb-1">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" class="form-control rounded-3" required>
                </div>
                <div class="mb-3">
                    <label class="fw-bold mb-1">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" class="form-control rounded-3" required>
                </div>
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection