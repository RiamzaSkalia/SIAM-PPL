@extends('layouts.mahasiswa')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
<style>
    div.dataTables_wrapper div.dataTables_length,
    div.dataTables_wrapper div.dataTables_filter {
        text-align: left;
        margin-bottom: 0.75rem;
    }
    div.dataTables_wrapper div.dataTables_filter input {
        width: 100%;
        margin-left: 0 !important;
        margin-top: 0.25rem;
    }
    @media (min-width: 576px) {
        div.dataTables_wrapper div.dataTables_filter {
            text-align: right;
        }
        div.dataTables_wrapper div.dataTables_filter input {
            width: auto;
            margin-left: 0.5rem !important;
            margin-top: 0;
        }
    }
    div.dataTables_wrapper div.dataTables_paginate ul.pagination {
        justify-content: center;
        flex-wrap: wrap;
        gap: 2px;
    }
    @media (min-width: 576px) {
        div.dataTables_wrapper div.dataTables_paginate ul.pagination {
            justify-content: flex-end;
        }
    }
</style>
@endpush

@section('content')
<!-- SALAM PEMBUKA & HEADING DASHBOARD -->
<div class="mb-4">
    <h3 class="fw-bold m-0 fs-4 fs-md-3" style="color: #2b3990;">
        Selamat Datang, {{ $mahasiswa->nama_mahasiswa ?? Auth::user()->name }}!
    </h3>
    <p class="text-muted small fs-md-6 m-0 mt-1">
        Program Asistensi Mengajar {{ $plotting->periode->nama_periode ?? '2025/2026' }} — Pendidikan Komputer (PILKOM ULM)
    </p>
</div>

<!-- INFO DOSEN, GURU PAMONG & SEKOLAH PENUGASAN -->
<div class="card card-custom p-3 p-md-4 mb-4" style="background-color: #f7ede2; border-left: 6px solid #3b31b2;">
    <div class="row g-3">
        <div class="col-12 col-md-4 d-flex align-items-center">
            <i class="bi bi-person-badge fs-2 me-3 flex-shrink-0" style="color: #3b31b2;"></i>
            <div class="min-w-0">
                <small class="text-muted d-block fw-semibold">Dosen Pembimbing Lapangan (DPL)</small>
                <strong class="text-dark fs-6 d-block text-break">{{ $plotting->dosen->nama_dosen ?? 'Belum Di-plotting' }}</strong>
            </div>
        </div>
        <div class="col-12 col-md-4 d-flex align-items-center">
            <i class="bi bi-person-check fs-2 me-3 flex-shrink-0" style="color: #28a745;"></i>
            <div class="min-w-0">
                <small class="text-muted d-block fw-semibold">Guru Pamong Sekolah</small>
                <strong class="text-dark fs-6 d-block text-break">{{ $plotting->sekolah->guruPamong->nama_guru_pamong ?? 'Belum terdaftar' }}</strong>
            </div>
        </div>
        <div class="col-12 col-md-4 d-flex align-items-center">
            <i class="bi bi-building fs-2 me-3 flex-shrink-0" style="color: #fd7e14;"></i>
            <div class="min-w-0">
                <small class="text-muted d-block fw-semibold">Sekolah Penugasan</small>
                <strong class="text-dark fs-6 d-block text-break">{{ $plotting->sekolah->nama_sekolah ?? 'Belum ditentukan' }}</strong>
                <small class="text-muted">({{ $plotting->sekolah->jenjang ?? '-' }})</small>
            </div>
        </div>
    </div>
</div>

<!-- BARIS CARD PROGRESS & STATUS KARTU KONSULTASI -->
<div class="row g-3 g-md-4 mb-4">
    <!-- BARIS LEFT: PROGRESS KONSULTASI -->
    <div class="col-12 col-lg-7">
        <div class="card card-custom p-3 p-md-4 h-100 shadow-sm">
            <h6 class="fw-bold mb-3" style="color: #2b3990;">
                PROGRESS KONSULTASI BIMBINGAN
            </h6>

            <div class="d-flex justify-content-between align-items-center gap-2 mb-2">
                <span class="fw-bold text-dark fs-6">Total Sesi Valid : {{ $totalSesiValid }} / 5 Sesi</span>
                <span class="badge bg-primary px-3 py-2 rounded-pill fs-6">{{ $persenProgress }}%</span>
            </div>

            <div class="progress mb-4" style="height: 14px; border-radius: 10px; background-color: #e9ecef;">
                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                    @style(['width: ' . $persenProgress . '%', 'background-color: #3b31b2'])
                    aria-valuenow="{{ $persenProgress }}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>

            <div class="row text-center pt-2 border-top g-2">
                <div class="col-4 border-end">
                    <small class="text-muted d-block" style="font-size: 12px;">Tervalidasi DPL</small>
                    <strong class="text-success fs-6 fs-md-5">{{ $totalSesiValid }} Sesi</strong>
                </div>
                <div class="col-4 border-end">
                    <small class="text-muted d-block" style="font-size: 12px;">Menunggu Review</small>
                    <strong class="text-warning fs-6 fs-md-5">{{ $totalMenunggu }} Sesi</strong>
                </div>
                <div class="col-4">
                    <small class="text-muted d-block" style="font-size: 12px;">Perlu Perbaikan</small>
                    <strong class="text-danger fs-6 fs-md-5">{{ $totalDitolak }} Sesi</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- BARIS RIGHT: STATUS KARTU KONSULTASI (PDF) -->
    <div class="col-12 col-lg-5">
        <div class="card card-custom p-3 p-md-4 h-100 shadow-sm text-center d-flex flex-column justify-content-between" style="background-color: #f8fafc;">
            <div>
                <h6 class="fw-bold mb-3 text-start" style="color: #2b3990;">
                    STATUS KARTU KONSULTASI (PDF)
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
                    <a href="{{ route('mahasiswa.kartu.cetak') }}" target="_blank" class="btn text-white rounded-pill px-3 px-md-4 py-2 w-100 fw-bold shadow-sm" style="background-color: #28a745; font-size: 14px;">
                        🖨️ CETAK KARTU KONSULTASI (PDF)
                    </a>
                @else
                    <a href="{{ route('mahasiswa.kartu') }}" class="btn btn-secondary rounded-pill px-3 px-md-4 py-2 w-100 fw-bold shadow-sm" style="font-size: 14px;">
                        👁️ PREVIEW KARTU KONSULTASI
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- TABEL RIWAYAT KONSULTASI TERAKHIR -->
<div class="bg-white rounded-4 shadow-sm p-3 p-md-4">
    <div class="d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center justify-content-between gap-2 mb-3">
        <h5 class="fw-bold m-0 fs-6 fs-md-5" style="color: #2b3990;">
            🕒 RIWAYAT KONSULTASI TERAKHIR
        </h5>
        <a href="{{ route('mahasiswa.konsultasi.create') }}" class="btn text-white rounded-pill px-4 py-2 fw-bold text-center" style="background-color: #3b31b2; font-size: 14px;">
            ➕ Tambah Log Bimbingan Baru
        </a>
    </div>

    <div class="bg-white rounded-3">
        <table id="tabelRiwayatDashboard" class="table align-middle text-center m-0 w-100" style="font-size: 14px; min-width: 650px;">
            <thead style="background-color: #1e1b4b; color: white;">
                <tr>
                    <th width="16%" class="py-3">TGL & MEDIA</th>
                    <th width="27%" class="py-3">TOPIK KONSULTASI</th>
                    <th width="18%" class="py-3">STATUS VALIDASI DPL</th>
                    <th width="27%" class="py-3">CATATAN / SARAN DOSEN</th>
                    <th width="12%" class="py-3">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @foreach($konsultasiTerakhir as $item)
                <tr>
                    <td class="py-3 text-nowrap">
                        <strong>{{ \Carbon\Carbon::parse($item->tanggal_konsul)->translatedFormat('d M Y') }}</strong><br>
                        <span class="badge bg-light text-dark border mt-1">{{ $item->media_konsul }}</span>
                    </td>
                    <td class="text-start py-3 fw-semibold">{{ $item->topik_dibahas }}</td>
                    <td class="py-3 text-nowrap">
                        @if($item->status_validasi == 'disetujui')
                            <span class="badge bg-success rounded-pill px-3 py-2">🟢 Disetujui</span>
                        @elseif($item->status_validasi == 'ditolak')
                            <span class="badge bg-danger rounded-pill px-3 py-2">🔴 Ditolak</span>
                        @else
                            <span class="badge bg-warning text-dark rounded-pill px-3 py-2">🟡 Menunggu</span>
                        @endif
                    </td>
                    <td class="text-start py-3">{{ $item->saran_dosen ?? '-' }}</td>
                    <td class="py-3 text-nowrap">
                        <a href="{{ route('mahasiswa.konsultasi.index') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold">
                            Detail
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function () {
        if ($.fn.DataTable.isDataTable('#tabelRiwayatDashboard')) return;

        $('#tabelRiwayatDashboard').DataTable({
            scrollX: true,
            autoWidth: false,
            pageLength: 5,
            lengthMenu: [[5, 10, 25, -1], [5, 10, 25, "Semua"]],
            columnDefs: [
                { orderable: false, searchable: false, targets: [4] }
            ],
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                infoFiltered: "(disaring dari _MAX_ total data)",
                zeroRecords: "Belum ada riwayat bimbingan yang tercatat.",
                paginate: {
                    next: "›",
                    previous: "‹"
                }
            }
        });
    });
</script>
@endpush