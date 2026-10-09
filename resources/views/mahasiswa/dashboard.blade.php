@extends('layouts.mahasiswa')

@section('content')
<!-- SALAM PEMBUKA & HEADING DASHBOARD -->
<div class="mb-4">
    <h3 class="fw-bold m-0" style="color: #2b3990;">
        Selamat Datang, {{ $mahasiswa->nama_mahasiswa ?? Auth::user()->name }}! 👋
    </h3>
    <p class="text-muted fs-6 m-0">
        Program Asistensi Mengajar {{ $plotting->periode->nama_periode ?? '2025/2026' }} — Pendidikan Komputer (PILKOM ULM)
    </p>
</div>

<!-- INFO DOSEN, GURU PAMONG & SEKOLAH PENUGASAN -->
<div class="card card-custom p-4 mb-4" style="background-color: #f7ede2; border-left: 6px solid #3b31b2;">
    <div class="row g-3">
        <div class="col-md-4 d-flex align-items-center">
            <i class="bi bi-person-badge fs-2 me-3" style="color: #3b31b2;"></i>
            <div>
                <small class="text-muted d-block fw-semibold">👨‍🏫 Dosen Pembimbing Lapangan (DPL)</small>
                <strong class="text-dark fs-6">{{ $plotting->dosen->nama_dosen ?? 'Belum Di-plotting' }}</strong>
            </div>
        </div>
        <div class="col-md-4 d-flex align-items-center">
            <i class="bi bi-person-check fs-2 me-3" style="color: #28a745;"></i>
            <div>
                <small class="text-muted d-block fw-semibold">👩‍🏫 Guru Pamong Sekolah</small>
                <strong class="text-dark fs-6">{{ $plotting->sekolah->guruPamong->nama_guru_pamong ?? 'Belum terdaftar' }}</strong>
            </div>
        </div>
        <div class="col-md-4 d-flex align-items-center">
            <i class="bi bi-building fs-2 me-3" style="color: #fd7e14;"></i>
            <div>
                <small class="text-muted d-block fw-semibold">🏫 Sekolah Penugasan</small>
                <strong class="text-dark fs-6">{{ $plotting->sekolah->nama_sekolah ?? 'Belum ditentukan' }}</strong> 
                <small class="text-muted">({{ $plotting->sekolah->jenjang ?? '-' }})</small>
            </div>
        </div>
    </div>
</div>

<!-- BARIS CARD PROGRESS & STATUS KARTU KONSULTASI -->
<div class="row g-4 mb-4">
    <!-- BARIS LEFT: PROGRESS KONSULTASI -->
    <div class="col-lg-7">
        <div class="card card-custom p-4 h-100 shadow-sm">
            <h6 class="fw-bold mb-3" style="color: #2b3990;">
                📊 PROGRESS KONSULTASI BIMBINGAN
            </h6>
            
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="fw-bold text-dark fs-6">Total Sesi Valid : {{ $totalSesiValid }} / 5 Sesi</span>
                <span class="badge bg-primary px-3 py-2 rounded-pill fs-6">{{ $persenProgress }}%</span>
            </div>

            <div class="progress mb-4" style="height: 14px; border-radius: 10px; background-color: #e9ecef;">
                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" 
                     @style(['width' => $persenProgress . '%', 'background-color' => '#3b31b2',])
                     aria-valuenow="{{ $persenProgress }}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>

            <div class="row text-center pt-2 border-top">
                <div class="col-4 border-end">
                    <small class="text-muted d-block">Tervalidasi DPL</small>
                    <strong class="text-success fs-5">{{ $totalSesiValid }} Sesi</strong>
                </div>
                <div class="col-4 border-end">
                    <small class="text-muted d-block">Menunggu Review</small>
                    <strong class="text-warning fs-5">{{ $totalMenunggu }} Sesi</strong>
                </div>
                <div class="col-4">
                    <small class="text-muted d-block">Perlu Perbaikan</small>
                    <strong class="text-danger fs-5">{{ $totalDitolak }} Sesi</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- BARIS RIGHT: STATUS KARTU KONSULTASI (PDF) -->
    <div class="col-lg-5">
        <div class="card card-custom p-4 h-100 shadow-sm text-center d-flex flex-column justify-content-between" style="background-color: #f8fafc;">
            <div>
                <h6 class="fw-bold mb-3 text-start" style="color: #2b3990;">
                    📄 STATUS KARTU KONSULTASI (PDF)
                </h6>

                @if($isEligible)
                    <div class="alert alert-success rounded-4 border-0 py-3 mb-3">
                        <strong class="d-block fs-6 mb-1">🟢 SYARAT TERPENUHI & SIAP DICETAK</strong>
                        <small style="font-size: 13px;">Seluruh sesi bimbingan telah divalidasi oleh DPL. Silakan unduh dokumen resmi.</small>
                    </div>
                @else
                    <div class="alert alert-warning rounded-4 border-0 py-3 mb-3">
                        <strong class="d-block fs-6 mb-1">🟡 SYARAT BELUM TERPENUHI</strong>
                        <small style="font-size: 13px;">Memerlukan minimal 5 bimbingan disetujui (Saat ini: <strong>{{ $totalSesiValid }}/5 Sesi</strong>).</small>
                    </div>
                @endif
            </div>

            <div>
                @if($isEligible)
                    <a href="{{ route('mahasiswa.kartu.cetak') }}" target="_blank" class="btn text-white rounded-pill px-4 py-2 w-100 fw-bold shadow-sm" style="background-color: #28a745; font-size: 15px;">
                        🖨️ CETAK KARTU KONSULTASI (PDF)
                    </a>
                @else
                    <a href="{{ route('mahasiswa.kartu') }}" class="btn btn-secondary rounded-pill px-4 py-2 w-100 fw-bold shadow-sm" style="font-size: 15px;">
                        👁️ PREVIEW KARTU KONSULTASI
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- TABEL RIWAYAT KONSULTASI TERAKHIR -->
<div class="bg-white rounded-4 shadow-sm p-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h5 class="fw-bold m-0" style="color: #2b3990;">
            🕒 RIWAYAT KONSULTASI TERAKHIR
        </h5>
        <a href="{{ route('mahasiswa.konsultasi.create') }}" class="btn text-white rounded-pill px-4 py-2 fw-bold" style="background-color: #3b31b2; font-size: 14px;">
            ➕ Tambah Log Bimbingan Baru
        </a>
    </div>

    <div class="table-responsive">
        <table class="table align-middle text-center m-0" style="font-size: 14px;">
            <thead style="background-color: #1e1b4b; color: white;">
                <tr>
                    <th width="15%" class="py-3">TGL & MEDIA</th>
                    <th width="28%" class="py-3">TOPIK KONSULTASI</th>
                    <th width="18%" class="py-3">STATUS VALIDASI DPL</th>
                    <th width="27%" class="py-3">CATATAN / SARAN DOSEN</th>
                    <th width="12%" class="py-3">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($konsultasiTerakhir as $item)
                <tr>
                    <td class="py-3">
                        <strong>{{ \Carbon\Carbon::parse($item->tanggal_konsul)->translatedFormat('d M Y') }}</strong><br>
                        <span class="badge bg-light text-dark border mt-1">{{ $item->media_konsul }}</span>
                    </td>
                    <td class="text-start py-3 fw-semibold">{{ $item->topik_dibahas }}</td>
                    <td class="py-3">
                        @if($item->status_validasi == 'disetujui')
                            <span class="badge bg-success rounded-pill px-3 py-2">🟢 Disetujui (Paraf Digital ✅)</span>
                        @elseif($item->status_validasi == 'ditolak')
                            <span class="badge bg-danger rounded-pill px-3 py-2">🔴 Ditolak</span>
                        @else
                            <span class="badge bg-warning text-dark rounded-pill px-3 py-2">🟡 Menunggu</span>
                        @endif
                    </td>
                    <td class="text-start py-3">{{ $item->saran_dosen ?? '-' }}</td>
                    <td class="py-3">
                        <a href="{{ route('mahasiswa.konsultasi.index') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold">
                            👁️ Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-5 text-muted fs-6">
                        <i class="bi bi-journal-x fs-2 d-block mb-2"></i>
                        Belum ada riwayat bimbingan yang tercatat.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection