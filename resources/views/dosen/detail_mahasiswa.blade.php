@extends('layouts.dosen')

@section('title', 'Detail Mahasiswa Bimbingan')
@section('page-title', 'Detail Mahasiswa Bimbingan')

@section('content')
@php
    $namaMhs = $mahasiswa->nama_mahasiswa ?? $mahasiswa->nama ?? '-';
    $nimMhs = $mahasiswa->nim ?? '-';
    $jmlBimbingan = $jumlahBimbingan ?? 0;
    $targetBimbingan = $targetBimbingan ?? 5;
    $memenuhi = $statusMemenuhi ?? ($jmlBimbingan >= $targetBimbingan);
@endphp

<div class="container-fluid px-0">
    <!-- Informasi Mahasiswa -->
    <div class="p-3 p-md-4 bg-white rounded-4 shadow-sm mb-4 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div>
            <h4 class="fw-bold mb-1" style="color: #2b3990;">{{ $namaMhs }}</h4>
            <p class="text-muted mb-1" style="font-size: 14px;">
                NIM: <strong class="text-dark">{{ $nimMhs }}</strong>
            </p>
            <p class="text-muted mb-0" style="font-size: 14px;">
                Total Bimbingan Disetujui: <strong class="text-dark">{{ $jmlBimbingan }}/{{ $targetBimbingan }}</strong>
            </p>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-2">
            @if($memenuhi)
                <span class="badge rounded-pill px-3 py-2 fw-bold"
                      style="background-color: #d3f9d8; color: #2b8a3e; font-size: 12px;">
                    Status: Memenuhi
                </span>
            @else
                <span class="badge rounded-pill px-3 py-2 fw-bold"
                      style="background-color: #fff3bf; color: #9c6b00; font-size: 12px;">
                    Status: Belum Memenuhi
                </span>
            @endif

            <!-- Tombol Cetak Kartu (Aktif jika >= target) -->
            @if($memenuhi)
                <a href="{{ Route::has('dosen.mahasiswa.cetak') ? route('dosen.mahasiswa.cetak', $mahasiswa->id) : '#' }}"
                   target="_blank"
                   class="btn btn-sm btn-success rounded-pill px-3 py-2 fw-bold d-inline-flex align-items-center gap-1"
                   style="font-size: 12px;">
                    <i class="bi bi-printer"></i>
                    <span>Cetak Kartu</span>
                </a>
            @else
                <button type="button"
                        disabled
                        class="btn btn-sm btn-secondary rounded-pill px-3 py-2 fw-bold opacity-50"
                        style="font-size: 12px;">
                    Cetak Kartu (Belum {{ $jmlBimbingan }}/{{ $targetBimbingan }})
                </button>
            @endif
        </div>
    </div>

    <!-- Riwayat & Log Konsultasi -->
    <div class="p-3 p-md-4 bg-white rounded-4 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0" style="color: #2b3990; font-size: 15px;">
                RIWAYAT KONSULTASI & VERIFIKASI
            </h6>
            <a href="{{ Route::has('dosen.mahasiswa.bimbingan') ? route('dosen.mahasiswa.bimbingan') : '#' }}"
               class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold"
               style="font-size: 12px;">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="d-flex flex-column gap-3">
            @forelse($konsultasis as $log)
                @php
                    $statusVal = strtolower($log->status_validasi ?? 'menunggu');
                    $isMenunggu = in_array($statusVal, ['pending', 'menunggu', 'menunggu verifikasi', '']);
                @endphp
                <div class="border rounded-4 p-3 p-md-4 bg-light bg-opacity-50">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 border-bottom pb-3 mb-3">
                        <div>
                            <div class="text-uppercase fw-bold" style="font-size: 11px; color: #2b3990; letter-spacing: 0.4px;">
                                {{ $log->media ?? 'Tatap Muka' }}
                            </div>
                            <h6 class="fw-bold text-dark mb-0 mt-1">
                                Topik: {{ $log->topik_dibahas }}
                            </h6>
                        </div>

                        <div>
                            @if($statusVal === 'disetujui')
                                <span class="badge rounded-pill px-3 py-2 fw-bold"
                                      style="background-color: #d3f9d8; color: #2b8a3e; font-size: 12px;">
                                    Disetujui
                                </span>
                            @elseif($statusVal === 'ditolak')
                                <span class="badge rounded-pill px-3 py-2 fw-bold"
                                      style="background-color: #ffe3e3; color: #c92a2a; font-size: 12px;">
                                    Ditolak
                                </span>
                            @else
                                <span class="badge rounded-pill px-3 py-2 fw-bold"
                                      style="background-color: #fff3bf; color: #9c6b00; font-size: 12px;">
                                    Menunggu Verifikasi
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="row g-3" style="font-size: 14px;">
                        <div class="col-12 col-md-6">
                            <div class="text-muted fw-semibold small">Tanggal &amp; Waktu:</div>
                            <div class="text-dark fw-medium mt-1">
                                {{ !empty($log->tanggal_konsul) ? \Carbon\Carbon::parse($log->tanggal_konsul)->translatedFormat('d F Y') : '-' }}
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="text-muted fw-semibold small">Hasil / Saran Dosen:</div>
                            <div class="text-dark fw-medium mt-1">
                                {{ $log->saran_dosen ?? 'Belum ada catatan saran.' }}
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi Verifikasi Dosen -->
                    @if($isMenunggu)
                        <div class="d-flex align-items-center gap-2 pt-3 mt-3 border-top">
                            <form action="{{ Route::has('dosen.konsultasi.setujui') ? route('dosen.konsultasi.setujui', $log->id) : '#' }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success rounded-pill px-3 py-1 fw-bold">
                                    <i class="bi bi-check-lg me-1"></i> Setujui
                                </button>
                            </form>

                            <form action="{{ Route::has('dosen.konsultasi.tolak') ? route('dosen.konsultasi.tolak', $log->id) : '#' }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger rounded-pill px-3 py-1 fw-bold">
                                    <i class="bi bi-x-lg me-1"></i> Tolak
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @empty
                <div class="text-center py-5 text-muted" style="font-size: 14px;">
                    Belum ada riwayat log bimbingan yang diajukan oleh mahasiswa ini.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection