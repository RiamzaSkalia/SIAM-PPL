@extends('layouts.mahasiswa')

@section('content')
<div class="container-fluid px-4">
    <!-- Header & Tombol Tambah -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h3 class="fw-bold mb-1" style="color: #2b3990;">Daftar Mata Kuliah Rekognisi</h3>
            <p class="text-muted fs-6 mb-0">
                Daftar mata kuliah yang Anda ajukan untuk disetarakan dengan kegiatan Asistensi Mengajar pada periode aktif.
            </p>
        </div>
        <div class="mt-3 mt-md-0">
            <a href="{{ route('mahasiswa.rekognisi.create') }}" class="btn text-white rounded-pill px-4 fw-bold shadow-sm" style="background-color: #2b3990; border: none;">
                <i class="bi bi-plus-lg me-1"></i> Ajukan Mata Kuliah
            </a>
        </div>
    </div>

    {{-- Alert Notifikasi Sukses --}}
    @if(session('success'))
        <div class="alert alert-success rounded-4 border-0 shadow-sm mb-4 fw-semibold">
            {{ session('success') }}
        </div>
    @endif

    {{-- Card Ringkasan Total SKS --}}
    <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 d-inline-block" style="background-color: #f7ede2;">
        <span class="text-secondary fw-semibold small">Total SKS Diajukan:</span>
        <span class="fs-5 fw-bold ms-2" style="color: #2b3990;">{{ $totalSks ?? 0 }} SKS</span>
        @if(isset($pengaturan) && $pengaturan->max_sks)
            <span class="text-muted small ms-1">(Maksimal: {{ $pengaturan->max_sks }} SKS)</span>
        @endif
    </div>

    {{-- Tabel Daftar Pengajuan --}}
    <div class="card border-0 shadow-sm rounded-4 p-4" style="background-color: #f7ede2;">
        <div class="table-responsive bg-white rounded-4 p-3 shadow-sm">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-uppercase fs-7 text-secondary">
                    <tr>
                        <th class="py-3 px-3">No</th>
                        <th class="py-3 px-3">Mata Kuliah Rekognisi</th>
                        <th class="py-3 px-3 text-center">Jumlah SKS</th>
                        <th class="py-3 px-3 text-center">Bukti Pendukung</th>
                        <th class="py-3 px-3 text-center">Status</th>
                        <th class="py-3 px-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengajuanList as $index => $item)
                        <tr>
                            <td class="py-3 px-3 fw-semibold">{{ $index + 1 }}</td>
                            <td class="py-3 px-3">
                                <span class="fw-bold text-dark">{{ $item->matkulRekognisi->nama_mk ?? 'Nama Mata Kuliah' }}</span>
                                <br>
                                <small class="text-muted">{{ $item->matkulRekognisi->kode_mk ?? '' }}</small>
                            </td>
                            <td class="py-3 px-3 text-center fw-semibold">
                                {{ $item->matkulRekognisi->sks ?? 0 }} SKS
                            </td>
                            <td class="py-3 px-3 text-center">
    @if($item->bukti_pendukung)
        <a href="{{ asset('storage/' . $item->bukti_pendukung) }}" download 
           class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 fw-bold fs-7">
            Download Dokumen
        </a>
    @else
        <span class="text-muted small fst-italic">Tidak ada</span>
    @endif
</td>
                            <td class="py-3 px-3 text-center">
                                @if($item->status == 'Disetujui')
                                    <span class="badge bg-success rounded-pill px-3 py-2 fw-semibold">Disetujui</span>
                                @elseif($item->status == 'Ditolak')
                                    <span class="badge bg-danger rounded-pill px-3 py-2 fw-semibold">Ditolak</span>
                                @else
                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2 fw-semibold">Pending</span>
                                @endif
                            </td>
                            <td class="py-3 px-3 text-center">
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
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                Belum ada mata kuliah rekognisi yang diajukan. Silakan klik tombol <strong style="color: #2b3990;">"+ Ajukan Mata Kuliah"</strong> di atas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection