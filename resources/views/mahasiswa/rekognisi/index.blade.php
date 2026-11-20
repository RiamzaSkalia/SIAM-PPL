@extends('layouts.mahasiswa')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
<style>
    /* Rapikan kontrol DataTables di layar HP */
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
<div class="container-fluid px-3 px-md-4">
    <!-- Header & Tombol Tambah -->
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-stretch align-items-sm-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1 fs-4 fs-md-3" style="color: #2b3990;">Daftar Mata Kuliah Rekognisi</h3>
            <p class="text-muted small fs-md-6 mb-0">
                Daftar mata kuliah yang Anda ajukan untuk disetarakan dengan kegiatan Asistensi Mengajar pada periode aktif.
            </p>
        </div>
        <div class="flex-shrink-0">
            <a href="{{ route('mahasiswa.rekognisi.create') }}" class="btn text-white rounded-pill px-4 py-2 fw-bold shadow-sm w-100 w-sm-auto text-center" style="background-color: #2b3990; border: none; font-size: 14px;">
                <i class="bi bi-plus-lg me-1"></i> Ajukan Mata Kuliah
            </a>
        </div>
    </div>

    {{-- Alert Notifikasi Sukses --}}
    @if(session('success'))
        <div class="alert alert-success rounded-4 border-0 shadow-sm mb-4 fw-semibold small fs-sm-6">
            {{ session('success') }}
        </div>
    @endif

    {{-- Card Ringkasan Total SKS --}}
    <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 d-inline-flex flex-wrap align-items-center gap-1" style="background-color: #f7ede2;">
        <span class="text-secondary fw-semibold small">Total SKS Diajukan:</span>
        <span class="fs-5 fw-bold ms-1" style="color: #2b3990;">{{ $totalSks ?? 0 }} SKS</span>
        @if(isset($pengaturan) && $pengaturan->max_sks)
            <span class="text-muted small ms-1">(Maksimal: {{ $pengaturan->max_sks }} SKS)</span>
        @endif
    </div>

    {{-- Tabel Daftar Pengajuan --}}
    <div class="card border-0 shadow-sm rounded-4 p-3 p-md-4" style="background-color: #f7ede2;">
        <div class="bg-white rounded-4 p-3 shadow-sm">
            <table id="tabelRekognisi" class="table table-hover align-middle mb-0 w-100" style="min-width: 780px; font-size: 14px;">
                <thead class="table-light text-uppercase fs-7 text-secondary">
                    <tr>
                        <th class="py-3 px-3 text-center" width="6%">No</th>
                        <th class="py-3 px-3" width="28%" style="min-width: 180px;">Mata Kuliah Rekognisi</th>
                        <th class="py-3 px-3 text-center" width="14%">Jumlah SKS</th>
                        <th class="py-3 px-3 text-center" width="20%">Bukti Pendukung</th>
                        <th class="py-3 px-3 text-center" width="15%">Status</th>
                        <th class="py-3 px-3 text-center" width="17%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pengajuanList as $index => $item)
                        <tr>
                            <td class="py-3 px-3 text-center fw-semibold">{{ $index + 1 }}</td>
                            <td class="py-3 px-3">
                                <span class="fw-bold text-dark d-block">{{ $item->matkulRekognisi->nama_mk ?? 'Nama Mata Kuliah' }}</span>
                                <small class="text-muted">{{ $item->matkulRekognisi->kode_mk ?? '' }}</small>
                            </td>
                            <td class="py-3 px-3 text-center fw-semibold text-nowrap">
                                {{ $item->matkulRekognisi->sks ?? 0 }} SKS
                            </td>
                            <td class="py-3 px-3 text-center text-nowrap">
                                @if($item->bukti_pendukung)
                                    <a href="{{ asset('storage/' . $item->bukti_pendukung) }}" download 
                                       class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 fw-bold fs-7">
                                        Download Dokumen
                                    </a>
                                @else
                                    <span class="text-muted small fst-italic">Tidak ada</span>
                                @endif
                            </td>
                            <td class="py-3 px-3 text-center text-nowrap">
                                @if($item->status == 'Disetujui')
                                    <span class="badge bg-success rounded-pill px-3 py-2 fw-semibold">Disetujui</span>
                                @elseif($item->status == 'Ditolak')
                                    <span class="badge bg-danger rounded-pill px-3 py-2 fw-semibold">Ditolak</span>
                                @else
                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2 fw-semibold">Pending</span>
                                @endif
                            </td>
                            <td class="py-3 px-3 text-center text-nowrap">
                                @if($item->status == 'Pending')
                                    <form action="{{ route('mahasiswa.rekognisi.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan/menghapus pengajuan mata kuliah ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1 fw-bold fs-7">
                                            Hapus
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted small fst-italic">Terkunci</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function () {
        const table = $('#tabelRekognisi').DataTable({
            scrollX: true,
            autoWidth: false,
            pageLength: 10,
            lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Semua"]],
            columnDefs: [
                { orderable: false, searchable: false, targets: [0, 3, 5] }
            ],
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                infoFiltered: "(disaring dari _MAX_ total data)",
                zeroRecords: "Belum ada mata kuliah rekognisi yang diajukan atau tidak ditemukan data yang cocok.",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "›",
                    previous: "‹"
                }
            }
        });

        // Perbarui nomor urut otomatis setelah sort/search/paging
        table.on('order.dt search.dt draw.dt', function () {
            let i = 1;
            table.cells(null, 0, { search: 'applied', order: 'applied' }).every(function () {
                this.data(i++);
            });
        }).draw();
    });
</script>
@endpush