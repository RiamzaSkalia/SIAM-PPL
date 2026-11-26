@extends('layouts.dosen')

@section('page-title', 'Mahasiswa Bimbingan')

@section('content')
@php
    $daftarMahasiswa = $mahasiswaBimbingan
        ?? $mahasiswas
        ?? $mahasiswa
        ?? $bimbingan
        ?? $plotting
        ?? collect();
@endphp

<div class="container-fluid px-0">
    <div class="mb-4">
        <h3 class="fw-bold mb-1" style="color: #2b3990;">Daftar Mahasiswa Bimbingan</h3>
        <p class="text-muted mb-0" style="font-size: 14px;">
            Daftar mahasiswa yang dibimbing dan dimonitor perkembangannya pada periode aktif.
        </p>
    </div>

    <div class="p-3 p-md-4 bg-white rounded-4 shadow-sm">

        <div class="table-responsive">
            <table id="tabelMahasiswaBimbingan" class="table table-hover align-middle mb-0 w-100" style="font-size: 14px;">
                <thead>
                    <tr class="text-muted border-bottom">
                        <th class="py-3 px-3 fw-semibold">Nama</th>
                        <th class="py-3 px-3 fw-semibold">NIM</th>
                        <th class="py-3 px-3 fw-semibold">Bimbingan</th>
                        <th class="py-3 px-3 fw-semibold">Status</th>
                        <th class="py-3 px-3 fw-semibold text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($daftarMahasiswa as $item)
                        @php
                            // Menangani jika $item berupa model Mahasiswa langsung atau objek plotting/bimbingan
                            $mhs = $item->mahasiswa ?? $item;
                            $idMhs = $mhs->id ?? $item->mahasiswa_id ?? $item->id ?? null;
                            $namaMhs = $mhs->nama_mahasiswa
                                ?? $mhs->nama
                                ?? $mhs->nama_mhs
                                ?? optional($mhs->user)->name
                                ?? '-';
                            $nimMhs = $mhs->nim ?? '-';

                            $jumlahValidasi = $item->jumlah_bimbingan_valid
                                ?? $item->jumlah_bimbingan
                                ?? $mhs->jumlah_bimbingan_valid
                                ?? $mhs->konsultasi_disetujui_count
                                ?? $mhs->konsultasi_valid_count
                                ?? 0;
                            $targetMinimal = $item->target_bimbingan ?? 5;
                            $memenuhi = $jumlahValidasi >= $targetMinimal;

                            $detailUrl = '#';
                            if ($idMhs && Route::has('dosen.mahasiswa.detail')) {
                                $detailUrl = route('dosen.mahasiswa.detail', $idMhs);
                            } elseif ($idMhs && Route::has('dosen.mahasiswa.show')) {
                                $detailUrl = route('dosen.mahasiswa.show', $idMhs);
                            }
                        @endphp
                        <tr class="border-bottom">
                            <td class="py-3 px-3 fw-bold text-dark">{{ $namaMhs }}</td>
                            <td class="py-3 px-3 text-secondary">{{ $nimMhs }}</td>
                            <td class="py-3 px-3 text-dark fw-medium">{{ $jumlahValidasi }}/{{ $targetMinimal }}</td>
                            <td class="py-3 px-3">
                                @if ($memenuhi)
                                    <span class="badge rounded-pill px-3 py-2 fw-semibold"
                                          style="background-color: #d3f9d8; color: #2b8a3e; font-size: 12px;">
                                        Memenuhi
                                    </span>
                                @else
                                    <span class="badge rounded-pill px-3 py-2 fw-semibold"
                                          style="background-color: #fff3bf; color: #9c6b00; font-size: 12px;">
                                        Belum Memenuhi
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-3 text-end">
                                <a href="{{ $detailUrl }}"
                                   class="btn btn-sm text-white rounded-3 px-3 py-1 fw-semibold"
                                   style="background-color: #2b3990; font-size: 12px;">
                                    Lihat Detail
                                </a>
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
<script>
    $(function () {
        $('#tabelMahasiswaBimbingan').DataTable({
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50],
            order: [[0, 'asc']],
            columnDefs: [
                { orderable: false, targets: -1 }
            ],
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ mahasiswa",
                infoEmpty: "Belum ada data mahasiswa bimbingan yang terdaftar.",
                infoFiltered: "(disaring dari _MAX_ total data)",
                zeroRecords: "Data mahasiswa bimbingan tidak ditemukan",
                paginate: {
                    first: "Awal",
                    last: "Akhir",
                    next: "Berikutnya",
                    previous: "Sebelumnya"
                }
            }
        });
    });
</script>
@endpush