@extends('layouts.mahasiswa')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h3 class="fw-bold m-0" style="color: #2b3990;">Daftar Bimbingan</h3>
        <p class="text-muted fs-6 m-0">Daftar seluruh catatan konsultasi bimbingan Anda</p>
    </div>
    <a href="{{ route('mahasiswa.konsultasi.create') }}" class="btn text-white rounded-pill px-4 py-2 fw-bold" style="background-color: #3b31b2; font-size: 14px;">
        ➕ Tambah Log Bimbingan
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success rounded-4 mb-4 border-0 shadow-sm fw-semibold fs-6">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-4 shadow-sm overflow-hidden p-3">
    <div class="table-responsive">
        <table class="table align-middle text-center m-0" style="font-size: 14px;">
            <thead style="background-color: #1e1b4b; color: white;">
                <tr>
                    <th width="4%" class="py-3">No</th>
                    <th width="12%" class="py-3">Tanggal / Waktu</th>
                    <th width="11%" class="py-3">Media</th>
                    <th width="18%" class="py-3">Topik Konsultasi</th>
                    <th width="18%" class="py-3">Refleksi Mahasiswa</th>
                    <th width="16%" class="py-3">Saran / Umpan Balik Dosen</th>
                    <th width="13%" class="py-3">Tindak Lanjut Mahasiswa</th>
                    <th width="8%" class="py-3">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($konsultasi as $idx => $item)
                <tr>
                    <td class="fw-bold py-3">{{ $idx + 1 }}</td>
                    <td class="py-3">
                        <strong>{{ \Carbon\Carbon::parse($item->tanggal_konsul)->translatedFormat('d M Y') }}</strong><br>
                        <small class="text-muted">🕒 {{ $item->waktu_format ?? '09:00' }}</small>
                    </td>
                    <td class="py-3">
                        <span class="badge bg-light text-dark border px-2 py-1 fs-6">{{ $item->media_konsul }}</span>
                    </td>
                    <td class="text-start py-3">{{ $item->topik_dibahas }}</td>
                    <td class="text-start py-3">{{ $item->refleksi_mahasiswa ?? '-' }}</td>
                    <td class="text-start py-3">{{ $item->saran_dosen ?? '-' }}</td>
                    <td class="text-start py-3">{{ $item->tindak_lanjut ?? '-' }}</td>
                    <td class="py-3">
                        @if($item->status_validasi == 'disetujui')
                            <span class="badge bg-success rounded-pill px-3 py-2 fs-6">Disetujui 🟢</span>
                        @elseif($item->status_validasi == 'ditolak')
                            <span class="badge bg-danger rounded-pill px-3 py-2 fs-6">Ditolak 🔴</span>
                        @else
                            <span class="badge bg-warning text-dark rounded-pill px-3 py-2 fs-6">Menunggu 🟡</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="py-5 text-muted fs-6">
                        <i class="bi bi-journal-x fs-2 d-block mb-2"></i>
                        Belum ada riwayat bimbingan yang tercatat. Silakan klik <strong>Tambah Log Bimbingan</strong>.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection