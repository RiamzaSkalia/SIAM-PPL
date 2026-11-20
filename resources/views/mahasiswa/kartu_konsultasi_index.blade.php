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
{{-- Notifikasi Error --}}
@if(session('error'))
    <div class="alert alert-danger rounded-4 mb-4 border-0 shadow-sm fw-semibold fs-6 d-flex align-items-center px-3 px-md-4 py-3">
        <i class="bi bi-exclamation-triangle-fill fs-5 me-3 flex-shrink-0"></i>
        <div class="text-break">{{ session('error') }}</div>
    </div>
@endif

{{-- Card Container Utama --}}
<div class="p-3 p-md-5 shadow-sm bg-white rounded-4 border" style="border-color: #f1f5f9 !important;">
    
    {{-- Header & Tombol Aksi --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-center gap-3 mb-4 pb-3 border-bottom">
        <div>
            <h3 class="fw-bold m-0 fs-4 fs-md-3" style="color: #2b3990;">📄 Kartu Konsultasi</h3>
            <p class="text-muted small fs-md-6 m-0 mt-1">Preview dan cetak kartu bimbingan resmi Asistensi Mengajar</p>
        </div>
        <div class="d-flex flex-column flex-sm-row gap-2">
            <button type="button" class="btn btn-outline-secondary rounded-pill px-4 py-2 fw-bold w-100 w-sm-auto" style="font-size: 14px;" onclick="window.scrollTo({top: 350, behavior: 'smooth'});">
                👁️ Preview
            </button>
            
            @if($isEligible)
                <a href="{{ route('mahasiswa.kartu.cetak') }}" target="_blank" class="btn btn-success rounded-pill px-4 py-2 fw-bold shadow-sm text-center w-100 w-sm-auto" style="font-size: 14px;">
                    🖨️ Cetak Kartu Konsultasi
                </a>
            @else
                <button type="button" class="btn btn-secondary rounded-pill px-4 py-2 fw-bold shadow-sm w-100 w-sm-auto" disabled style="opacity: 0.65; cursor: not-allowed; font-size: 14px;" title="Minimal 5 bimbingan disetujui">
                    🔒 Cetak Kartu Konsultasi
                </button>
            @endif
        </div>
    </div>

    {{-- Alert Kelayakan --}}
    @if($isEligible)
        <div class="alert alert-success border-0 rounded-4 py-3 px-3 px-md-4 mb-4 fs-6 d-flex align-items-start align-items-sm-center shadow-sm" style="background-color: #d1e7dd; color: #0f5132;">
            <i class="bi bi-check-circle-fill me-3 fs-4 flex-shrink-0"></i>
            <div>
                <strong>Syarat Terpenuhi!</strong> ({{ $totalDisetujui }}/5 Sesi Terverifikasi). Kartu konsultasi telah siap untuk dicetak.
            </div>
        </div>
    @else
        <div class="alert alert-warning border-0 rounded-4 py-3 px-3 px-md-4 mb-4 fs-6 d-flex align-items-start align-items-sm-center shadow-sm" style="background-color: #fff3cd; color: #664d03;">
            <i class="bi bi-exclamation-triangle-fill me-3 fs-4 flex-shrink-0"></i>
            <div>
                <strong>Syarat belum terpenuhi!</strong> Saat ini baru <strong>{{ $totalDisetujui }}/5 Sesi Terverifikasi</strong>. Anda memerlukan minimal 5 bimbingan disetujui DPL.
            </div>
        </div>
    @endif

    {{-- Banner Program Studi --}}
    <div class="p-3 p-md-4 text-white mb-4 shadow-sm" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%); border-radius: 16px;">
        <div class="d-flex align-items-center justify-content-between gap-3">
            <div class="min-w-0">
                <small class="text-uppercase text-light opacity-75 fw-semibold d-block mb-1" style="letter-spacing: 0.5px;">Program Studi</small>
                <h2 class="fw-bold m-0 fs-4 fs-md-2 text-break" style="color: #a5b4fc;">Pendidikan Komputer</h2>
                <span class="small fs-md-6 text-light opacity-90 d-block mt-1">Universitas Lambung Mangkurat</span>
            </div>
            <div class="d-none d-md-block opacity-25 flex-shrink-0">
                <i class="bi bi-mortarboard-fill" style="font-size: 3.5rem;"></i>
            </div>
        </div>
    </div>

    {{-- Detail Biodata --}}
    <div class="p-3 p-md-4 rounded-4 mb-4 border" style="background-color: #f8fafc; border-color: #e2e8f0 !important;">
        <h6 class="fw-bold mb-3 text-uppercase text-secondary" style="letter-spacing: 0.5px; font-size: 13px;">
            <i class="bi bi-person-vcard me-1"></i> Data Peserta &amp; Penugasan
        </h6>
        <div class="row g-3" style="font-size: 14px;">
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="p-1 p-md-2">
                    <span class="text-muted d-block small">Nama Mahasiswa</span>
                    <strong class="text-dark fs-6 d-block text-break">{{ $mahasiswa->nama_mahasiswa }}</strong>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="p-1 p-md-2">
                    <span class="text-muted d-block small">NIM</span>
                    <strong class="text-dark fs-6 d-block text-break">{{ $mahasiswa->nim }}</strong>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="p-1 p-md-2">
                    <span class="text-muted d-block small">Sekolah Mitra</span>
                    <strong class="text-dark fs-6 d-block text-break">{{ $plotting->sekolah->nama_sekolah ?? '-' }}</strong>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="p-1 p-md-2">
                    <span class="text-muted d-block small">Dosen Pembimbing Lapangan</span>
                    <strong class="text-dark fs-6 d-block text-break">{{ $plotting->dosen->nama_dosen ?? 'Belum ditentukan' }}</strong>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="p-1 p-md-2">
                    <span class="text-muted d-block small">Guru Pamong</span>
                    <strong class="text-dark fs-6 d-block text-break">{{ $plotting->sekolah->guruPamong->nama_guru_pamong ?? '-' }}</strong>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="p-1 p-md-2">
                    <span class="text-muted d-block small">Periode Asistensi Mengajar</span>
                    <strong class="text-dark fs-6 d-block text-break">{{ $plotting->periode->nama_periode ?? '2025/2026' }}</strong>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Riwayat Konsultasi --}}
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h5 class="fw-bold m-0 fs-6 fs-md-5" style="color: #2b3990;">
            <i class="bi bi-journal-check me-2"></i>Riwayat Konsultasi Terverifikasi
        </h5>
    </div>

    <div class="bg-white rounded-4 border shadow-sm p-3">
        <table id="tabelKartuTerverifikasi" class="table align-middle mb-0 w-100" style="font-size: 13.5px; min-width: 820px;">
            <thead style="background-color: #1e1b4b; color: white;">
                <tr>
                    <th width="4%" class="text-center py-3">NO</th>
                    <th width="12%" class="py-3">TANGGAL</th>
                    <th width="12%" class="py-3">MEDIA</th>
                    <th width="18%" class="py-3" style="min-width: 160px;">TOPIK DIBAHAS</th>
                    <th width="18%" class="py-3" style="min-width: 160px;">REFLEKSI MAHASISWA</th>
                    <th width="18%" class="py-3" style="min-width: 160px;">SARAN / UMPAN BALIK</th>
                    <th width="18%" class="py-3" style="min-width: 160px;">TINDAK LANJUT</th>
                </tr>
            </thead>
            <tbody>
                @foreach($riwayatKonsultasi as $idx => $k)
                <tr @style(['background-color: #f8fafc;' => $idx % 2 == 1])>
                    <td class="text-center fw-bold py-3">{{ $idx + 1 }}</td>
                    <td class="py-3 fw-semibold text-nowrap">{{ \Carbon\Carbon::parse($k->tanggal_konsul)->translatedFormat('d F Y') }}</td>
                    <td class="py-3 text-nowrap">
                        <span class="badge bg-light text-dark border px-2 py-1">{{ $k->media_konsul }}</span>
                    </td>
                    <td class="py-3">{{ $k->topik_dibahas }}</td>
                    <td class="py-3 text-secondary">{{ $k->refleksi_mahasiswa ?? '-' }}</td>
                    <td class="py-3 text-secondary">{{ $k->saran_dosen ?? '-' }}</td>
                    <td class="py-3 text-secondary">{{ $k->tindak_lanjut ?? '-' }}</td>
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
        if ($.fn.DataTable.isDataTable('#tabelKartuTerverifikasi')) return;

        const table = $('#tabelKartuTerverifikasi').DataTable({
            scrollX: true,
            autoWidth: false,
            pageLength: 10,
            lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Semua"]],
            columnDefs: [
                { orderable: false, searchable: false, targets: [0] }
            ],
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                infoFiltered: "(disaring dari _MAX_ total data)",
                zeroRecords: "Belum ada bimbingan yang divalidasi/disetujui oleh Dosen Pembimbing.",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "›",
                    previous: "‹"
                }
            }
        });

        table.on('order.dt search.dt draw.dt', function () {
            let i = 1;
            table.cells(null, 0, { search: 'applied', order: 'applied' }).every(function () {
                this.data(i++);
            });
        }).draw();
    });
</script>
@endpush