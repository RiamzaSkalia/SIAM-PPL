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
<div class="d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center justify-content-between gap-2 mb-4">
    <div>
        <h3 class="fw-bold m-0 fs-4 fs-md-3" style="color: #2b3990;">Daftar Bimbingan</h3>
        <p class="text-muted small fs-md-6 m-0 mt-1">Daftar seluruh catatan konsultasi bimbingan Anda</p>
    </div>
    <a href="{{ route('mahasiswa.konsultasi.create') }}" class="btn text-white rounded-pill px-4 py-2 fw-bold text-center shadow-sm" style="background-color: #3b31b2; font-size: 14px;">
        ➕ Tambah Log Bimbingan
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success rounded-4 mb-4 border-0 shadow-sm fw-semibold fs-6">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-4 shadow-sm p-3 p-md-4">
    <table id="tabelDaftarBimbingan" class="table align-middle text-center m-0 w-100" style="font-size: 14px; min-width: 920px;">
        <thead style="background-color: #1e1b4b; color: white;">
            <tr>
                <th width="4%" class="py-3">No</th>
                <th width="12%" class="py-3">Tanggal / Waktu</th>
                <th width="11%" class="py-3">Media</th>
                <th width="18%" class="py-3" style="min-width: 170px;">Topik Konsultasi</th>
                <th width="18%" class="py-3" style="min-width: 170px;">Refleksi Mahasiswa</th>
                <th width="16%" class="py-3" style="min-width: 160px;">Saran / Umpan Balik Dosen</th>
                <th width="13%" class="py-3" style="min-width: 150px;">Tindak Lanjut Mahasiswa</th>
                <th width="8%" class="py-3">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($konsultasi as $idx => $item)
            <tr>
                <td class="fw-bold py-3">{{ $idx + 1 }}</td>
                <td class="py-3 text-nowrap">
                    <strong>{{ \Carbon\Carbon::parse($item->tanggal_konsul)->translatedFormat('d M Y') }}</strong><br>
                    <small class="text-muted">🕒 {{ $item->waktu_format ?? '09:00' }}</small>
                </td>
                <td class="py-3 text-nowrap">
                    <span class="badge bg-light text-dark border px-2 py-1 fs-6">{{ $item->media_konsul }}</span>
                </td>
                <td class="text-start py-3">{{ $item->topik_dibahas }}</td>
                <td class="text-start py-3">{{ $item->refleksi_mahasiswa ?? '-' }}</td>
                <td class="text-start py-3">{{ $item->saran_dosen ?? '-' }}</td>
                <td class="text-start py-3">{{ $item->tindak_lanjut_mahasiswa ?? '-' }}</td>
                <td class="py-3 text-nowrap">
                    @if($item->status_validasi == 'disetujui')
                        <span class="badge bg-success rounded-pill px-3 py-2 fs-6">Disetujui 🟢</span>
                    @elseif($item->status_validasi == 'ditolak')
                        <span class="badge bg-danger rounded-pill px-3 py-2 fs-6">Ditolak 🔴</span>
                    @else
                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2 fs-6">Menunggu 🟡</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function () {
        if ($.fn.DataTable.isDataTable('#tabelDaftarBimbingan')) return;

        const table = $('#tabelDaftarBimbingan').DataTable({
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
                zeroRecords: "Belum ada riwayat bimbingan yang tercatat. Silakan klik Tambah Log Bimbingan.",
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