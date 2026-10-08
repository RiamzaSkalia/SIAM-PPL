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

{{-- SECTION 2: TEMPLATE KOP SURAT & LEMBAR PENGESAHAN --}}
<div class="p-4" style="background-color: #f7ede2; border-radius: 12px;">
    <h6 class="fw-bold mb-3" style="color: #2b3990;">📄 TEMPLATE CETAK KARTU KONSULTASI PDF (KOP & LEMBAR PENGESAHAN)</h6>
    <hr>

    <form action="{{ route('admin.pengaturan.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-4">
            <label class="fw-bold mb-1">Logo Instansi / Kampus:</label>
            <div class="d-flex align-items-center gap-3">
                @if($pengaturan->logo_path)
                    <img src="{{ asset('storage/' . $pengaturan->logo_path) }}" alt="Logo" style="height: 60px;">
                @else
                    <span class="text-muted fs-7">Belum ada logo diunggah.</span>
                @endif
                <input type="file" name="logo" class="form-control rounded-3" style="max-width: 300px;">
            </div>
        </div>

        <div class="mb-4">
            <label class="fw-bold mb-2">Header Teks Kop Surat:</label>
            <input type="text" name="header_1" class="form-control rounded-3 mb-2" value="{{ old('header_1', $pengaturan->header_1 ?? 'KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI') }}" placeholder="Baris 1">
            <input type="text" name="header_2" class="form-control rounded-3 mb-2" value="{{ old('header_2', $pengaturan->header_2 ?? 'UNIVERSITAS LAMBUNG MANGKURAT') }}" placeholder="Baris 2">
            <input type="text" name="header_3" class="form-control rounded-3 mb-2" value="{{ old('header_3', $pengaturan->header_3 ?? 'FAKULTAS KEGURUAN DAN ILMU PENDIDIKAN') }}" placeholder="Baris 3">
            <input type="text" name="header_4" class="form-control rounded-3 mb-2" value="{{ old('header_4', $pengaturan->header_4 ?? 'JURUSAN PENDIDIKAN KOMPUTER') }}" placeholder="Baris 4">
            <input type="text" name="header_5" class="form-control rounded-3" value="{{ old('header_5', $pengaturan->header_5 ?? 'Jalan Brigjen H. Hasan Basry Banjarmasin 70123') }}" placeholder="Baris 5 (Alamat / Kontak)">
        </div>

        <div class="mb-4">
            <label class="fw-bold mb-2">Penandatangan Lembar Pengesahan (Footer Dokumen):</label>
            <div class="row g-2">
                <div class="col-md-4">
                    <input type="text" name="ttd_jabatan" class="form-control rounded-3" value="{{ old('ttd_jabatan', $pengaturan->ttd_jabatan ?? 'Koordinator Program Studi Pendidikan Komputer') }}" placeholder="Jabatan">
                </div>
                <div class="col-md-4">
                    <input type="text" name="ttd_nama" class="form-control rounded-3" value="{{ old('ttd_nama', $pengaturan->ttd_nama ?? 'Dr. Harja Santanapurba, M.Kom Ph.D.') }}" placeholder="Nama Lengkap & Gelar">
                </div>
                <div class="col-md-4">
                    <input type="text" name="ttd_nip" class="form-control rounded-3" value="{{ old('ttd_nip', $pengaturan->ttd_nip ?? '197801232005011002') }}" placeholder="NIP">
                </div>
            </div>
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