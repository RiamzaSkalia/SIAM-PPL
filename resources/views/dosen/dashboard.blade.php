@extends('layouts.dosen')

@section('content')
@php
    $namaDosen = $dosen->nama_dosen ?? auth()->user()->name ?? 'Dosen Pembimbing';
    $periodeLabel = $activePeriode->nama_periode ?? ($periode ?? 'Belum Ditentukan');

    $jmlMahasiswa = $jumlahMahasiswa ?? ($totalMahasiswa ?? 0);
    $jmlTotalLog = $totalLog ?? ($totalKonsultasi ?? 0);
    $jmlMenunggu = $menungguVerifikasi ?? 0;
    $jmlDisetujui = $logDisetujui ?? max($jmlTotalLog - $jmlMenunggu, 0);

    $mhsLayak = $mahasiswaLayak ?? 0;
    $mhsBelumLayak = $mahasiswaBelumLayak ?? max($jmlMahasiswa - $mhsLayak, 0);

    $pctLayak = $persenLayak ?? ($jmlMahasiswa > 0 ? round(($mhsLayak / $jmlMahasiswa) * 100) : 0);
    $pctLayak = min(max((int) $pctLayak, 0), 100);
    $pctBelumLayak = $persenBelumLayak ?? (100 - $pctLayak);
    $pctBelumLayak = min(max((int) $pctBelumLayak, 0), 100);

    $daftarLogMenunggu = $logMenunggu ?? ($konsultasiMenunggu ?? collect());
    $daftarAktivitas = $konsultasiTerakhir ?? ($aktivitasTerakhir ?? $daftarLogMenunggu);
@endphp

{{-- Header Sambutan & Tanggal Responsif --}}
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2 mb-4">
    <div>
        <h3 class="fw-bold m-0" style="color: #2b3990;">Selamat Datang, {{ $namaDosen }}!</h3>
        <p class="text-muted m-0 mt-1" style="font-size: 14px;">
            Ringkasan Bimbingan Asistensi Mengajar — Periode Aktif:
            <strong>{{ $periodeLabel }}</strong>
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
                <small class="text-muted fw-bold d-block" style="font-size: 11px;">MAHASISWA BIMBINGAN</small>
                <h3 class="fw-bold m-0 mt-1 text-primary">{{ $jmlMahasiswa }}</h3>
            </div>
            <i class="bi bi-people-fill fs-2 text-primary-subtle ms-2"></i>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="p-3 bg-white rounded-4 shadow-sm border-start border-success border-4 d-flex justify-content-between align-items-center h-100">
            <div>
                <small class="text-muted fw-bold d-block" style="font-size: 11px;">TOTAL LOG BIMBINGAN</small>
                <h3 class="fw-bold m-0 mt-1 text-success">{{ $jmlTotalLog }}</h3>
            </div>
            <i class="bi bi-journal-check fs-2 text-success-subtle ms-2"></i>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="p-3 bg-white rounded-4 shadow-sm border-start border-warning border-4 d-flex justify-content-between align-items-center h-100">
            <div>
                <small class="text-muted fw-bold d-block" style="font-size: 11px;">MENUNGGU VERIFIKASI</small>
                <h3 class="fw-bold m-0 mt-1 text-warning">{{ $jmlMenunggu }}</h3>
            </div>
            <i class="bi bi-hourglass-split fs-2 text-warning-subtle ms-2"></i>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="p-3 bg-white rounded-4 shadow-sm border-start border-info border-4 d-flex justify-content-between align-items-center h-100">
            <div>
                <small class="text-muted fw-bold d-block" style="font-size: 11px;">LOG DISETUJUI</small>
                <h3 class="fw-bold m-0 mt-1 text-info">{{ $jmlDisetujui }}</h3>
            </div>
            <i class="bi bi-patch-check-fill fs-2 text-info-subtle ms-2"></i>
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
            <span class="fw-bold text-success">{{ $pctLayak }}% ({{ $mhsLayak }} / {{ $jmlMahasiswa }} Mhs)</span>
        </div>
        <div class="progress rounded-pill" style="height: 14px; background-color: #e9ecef;">
            <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar"
                 @style(['width: ' . $pctLayak . '%'])
                 aria-valuenow="{{ $pctLayak }}" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
    </div>

    <div>
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-1 mb-1" style="font-size: 14px;">
            <span class="fw-semibold">Mahasiswa Belum Layak</span>
            <span class="fw-bold text-danger">{{ $pctBelumLayak }}% ({{ $mhsBelumLayak }} / {{ $jmlMahasiswa }} Mhs)</span>
        </div>
        <div class="progress rounded-pill" style="height: 14px; background-color: #e9ecef;">
            <div class="progress-bar bg-danger progress-bar-striped progress-bar-animated" role="progressbar"
                 @style(['width: ' . $pctBelumLayak . '%'])
                 aria-valuenow="{{ $pctBelumLayak }}" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
    </div>
</div>

{{-- Row 3: Perhatian & Aktivitas --}}
<div class="row g-3">
    <div class="col-12 col-lg-6">
        <div class="p-3 p-md-4 bg-white rounded-4 shadow-sm h-100 d-flex flex-column justify-content-between">
            <div>
                <h6 class="fw-bold mb-3 text-danger">⚠️ PERINGATAN & TINDAK LANJUT BIMBINGAN</h6>
                <ul class="list-unstyled mb-4" style="font-size: 14px;">
                    @if($jmlMenunggu > 0)
                        <li class="mb-2">
- <strong>{{ $jmlMenunggu }} Log Konsultasi menunggu validasi Anda</strong>
                            <br><small class="text-muted">(Segera tinjau catatan bimbingan mahasiswa agar progres mereka berjalan lancar)</small>
                        </li>
                    @endif
                    @if($mhsBelumLayak > 0)
                        <li class="mb-2">
- <strong>{{ $mhsBelumLayak }} Mahasiswa bimbingan belum memenuhi minimal 5 sesi validasi</strong>
                            <br><small class="text-muted">(Pantau frekuensi konsultasi rutin sebelum batas cetak kartu)</small>
                        </li>
                    @endif
                    @if($jmlMenunggu == 0 && $mhsBelumLayak == 0)
                        <li class="text-success fw-bold">Seluruh log bimbingan telah divalidasi dan mahasiswa bimbingan sudah memenuhi target!</li>
                    @endif
                </ul>
            </div>
            <div>
                <a href="{{ Route::has('dosen.mahasiswa.bimbingan') ? route('dosen.mahasiswa.bimbingan') : '#' }}"
                   class="btn btn-outline-primary rounded-pill btn-sm px-3 py-2 fw-bold">
                    👥 Buka Mahasiswa Bimbingan
                </a>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6">
        <div class="p-3 p-md-4 bg-white rounded-4 shadow-sm h-100">
            <h6 class="fw-bold mb-3" style="color: #2b3990;">AKTIVITAS KONSULTASI TERAKHIR</h6>
            <ul class="list-unstyled mb-0" style="font-size: 14px;">
                @forelse($daftarAktivitas as $item)
                    @php
                        $namaMhs = $item->mahasiswa->nama_mahasiswa
                            ?? $item->mahasiswa->nama
                            ?? optional(optional($item->plottingBimbingan ?? $item->plotting)->mahasiswa)->first()->nama_mahasiswa
                            ?? 'Mahasiswa';
                        $namaSekolah = $item->mahasiswa->sekolahMitra->nama_sekolah
                            ?? optional(optional($item->plottingBimbingan ?? $item->plotting)->sekolah)->nama_sekolah
                            ?? '-';
                        $statusVal = $item->status_validasi ?? 'menunggu';
                    @endphp
                    <li class="mb-2 pb-2 border-bottom d-flex justify-content-between align-items-start gap-2">
                        <div>
- <strong>{{ $namaMhs }}</strong>
                            <span class="text-muted">({{ $namaSekolah }})</span>
                            <br><small class="text-muted">Konsul — Status: {{ ucfirst($statusVal) }} oleh {{ $namaDosen }}</small>
                        </div>
                        @if(strtolower($statusVal) === 'menunggu' && isset($item->id) && Route::has('dosen.konsultasi.verify'))
                            <a href="{{ route('dosen.konsultasi.verify', $item->id) }}"
                               class="btn btn-sm text-white rounded-pill px-3 fw-bold flex-shrink-0"
                               style="background-color: #2b3990;">
                                Verifikasi
                            </a>
                        @endif
                    </li>
                @empty
                    <li class="text-muted">Belum ada aktivitas konsultasi pada periode ini.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection