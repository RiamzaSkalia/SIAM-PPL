@extends('layouts.admin')

@section('content')
{{-- Header Sambutan & Tanggal Responsif --}}
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2 mb-4">
    <div>
        <h3 class="fw-bold m-0" style="color: #2b3990;">Selamat Datang, Admin PILKOM ULM!</h3>
        <p class="text-muted m-0 mt-1" style="font-size: 14px;">
            Ringkasan Program Asistensi Mengajar — Periode Aktif: 
            <strong>{{ $activePeriode->nama_periode ?? 'Belum Ditentukan' }}</strong>
        </p>
    </div>
    <span class="badge bg-primary px-3 py-2 rounded-pill shadow-sm text-nowrap" style="font-size: 13px;">
        📅 {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
    </span>
</div>

{{-- Row 1: Statistik Card Modern (2x2 di HP, 1x4 di Desktop) --}}
<div class="row g-2 g-md-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="p-3 bg-white rounded-4 shadow-sm border-start border-primary border-4 d-flex justify-content-between align-items-center h-100">
            <div>
                <small class="text-muted fw-bold d-block" style="font-size: 11px;">TOTAL MAHASISWA AM</small>
                <h3 class="fw-bold m-0 mt-1 text-primary">{{ $totalMahasiswa }}</h3>
            </div>
            <i class="bi bi-people-fill fs-2 fs-md-1 text-primary-subtle ms-2"></i>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="p-3 bg-white rounded-4 shadow-sm border-start border-success border-4 d-flex justify-content-between align-items-center h-100">
            <div>
                <small class="text-muted fw-bold d-block" style="font-size: 11px;">DOSEN PEMBIMBING</small>
                <h3 class="fw-bold m-0 mt-1 text-success">{{ $totalDosen }}</h3>
            </div>
            <i class="bi bi-person-badge-fill fs-2 fs-md-1 text-success-subtle ms-2"></i>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="p-3 bg-white rounded-4 shadow-sm border-start border-warning border-4 d-flex justify-content-between align-items-center h-100">
            <div>
                <small class="text-muted fw-bold d-block" style="font-size: 11px;">SEKOLAH MITRA</small>
                <h3 class="fw-bold m-0 mt-1 text-warning">{{ $totalSekolah }}</h3>
            </div>
            <i class="bi bi-building-fill fs-2 fs-md-1 text-warning-subtle ms-2"></i>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="p-3 bg-white rounded-4 shadow-sm border-start border-info border-4 d-flex justify-content-between align-items-center h-100">
            <div>
                <small class="text-muted fw-bold d-block" style="font-size: 11px;">TOTAL KONSULTASI</small>
                <h3 class="fw-bold m-0 mt-1 text-info">{{ $totalKonsultasi }}</h3>
            </div>
            <i class="bi bi-journal-check fs-2 fs-md-1 text-info-subtle ms-2"></i>
        </div>
    </div>
</div>

{{-- Row 2: Progress Kelayakan --}}
<div class="p-3 p-md-4 bg-white rounded-4 shadow-sm mb-4">
    <h6 class="fw-bold mb-3" style="color: #2b3990; font-size: 14px;">
        PROGRESS KELAYAKAN CETAK KARTU KONSULTASI (MINIMAL 5 SESI VALIDASI)
    </h6>
    
    <div class="mb-3">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-1 mb-1" style="font-size: 14px;">
            <span class="fw-semibold">Mahasiswa Layak Cetak</span>
            <span class="fw-bold text-success">{{ $persenLayak }}% ({{ $mahasiswaLayak }} / {{ $totalMahasiswa }} Mhs)</span>
        </div>
        <div class="progress rounded-pill" style="height: 14px; background-color: #e9ecef;">
            <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" 
                 @style(['width: ' . $persenLayak . '%'])
                 aria-valuenow="{{ $persenLayak }}" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
    </div>

    <div>
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-1 mb-1" style="font-size: 14px;">
            <span class="fw-semibold">Mahasiswa Belum Layak</span>
            <span class="fw-bold text-danger">{{ $persenBelumLayak }}% ({{ $mahasiswaBelumLayak }} / {{ $totalMahasiswa }} Mhs)</span>
        </div>
        <div class="progress rounded-pill" style="height: 14px; background-color: #e9ecef;">
            <div class="progress-bar bg-danger progress-bar-striped progress-bar-animated" role="progressbar" 
                 @style(['width: ' . $persenBelumLayak . '%'])
                 aria-valuenow="{{ $persenBelumLayak }}" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
    </div>
</div>

{{-- Row 3: Peringatan & Aktivitas --}}
<div class="row g-3">
    <div class="col-12 col-lg-6">
        <div class="p-3 p-md-4 bg-white rounded-4 shadow-sm h-100 d-flex flex-column justify-content-between">
            <div>
                <h6 class="fw-bold mb-3 text-danger">⚠️ PERINGATAN SISTEM & PEMETAAN</h6>
                <ul class="list-unstyled mb-4" style="font-size: 14px;">
                    @if($sekolahTanpaPamong->count() > 0)
                        <li class="mb-2">
                            • <strong>{{ $sekolahTanpaPamong->count() }} Sekolah Mitra belum diisi data Guru Pamong</strong>
                            <br><small class="text-muted">({{ $sekolahTanpaPamong->pluck('nama_sekolah')->implode(', ') }})</small>
                        </li>
                    @endif
                    @if($mhsBelumPlot->count() > 0)
                        <li class="mb-2">
                            • <strong>{{ $mhsBelumPlot->count() }} Mahasiswa belum di-plot ke Dosen Pembimbing</strong>
                            <br><small class="text-muted">(NIM: {{ $mhsBelumPlot->pluck('nim')->implode(', ') }})</small>
                        </li>
                    @endif
                    @if($sekolahTanpaPamong->count() == 0 && $mhsBelumPlot->count() == 0)
                        <li class="text-success fw-bold">Semua data pada periode ini telah lengkap & terkelola dengan baik!</li>
                    @endif
                </ul>
            </div>
            <div>
                <a href="{{ route('admin.pemetaan.index') }}" class="btn btn-outline-primary rounded-pill btn-sm px-3 py-2 fw-bold">
                    🔗 Buka Halaman Pemetaan
                </a>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6">
        <div class="p-3 p-md-4 bg-white rounded-4 shadow-sm h-100">
            <h6 class="fw-bold mb-3" style="color: #2b3990;">AKTIVITAS KONSULTASI TERAKHIR</h6>
            <ul class="list-unstyled mb-0" style="font-size: 14px;">
                @forelse($konsultasiTerakhir as $konsul)
                    @php
                        $plotData = $konsul->plottingBimbingan ?? $konsul->plotting;
                    @endphp
                    <li class="mb-2 pb-2 border-bottom">
                        • <strong>{{ $plotData->mahasiswa->first()->nama_mahasiswa ?? 'Mahasiswa' }}</strong> 
                        <span class="text-muted">({{ $plotData->sekolah->nama_sekolah ?? '-' }})</span>
                        <br><small class="text-muted">Konsul — Status: {{ ucfirst($konsul->status_validasi) }} oleh {{ $plotData->dosen->nama_dosen ?? 'DPL' }}</small>
                    </li>
                @empty
                    <li class="text-muted">Belum ada aktivitas konsultasi pada periode ini.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection