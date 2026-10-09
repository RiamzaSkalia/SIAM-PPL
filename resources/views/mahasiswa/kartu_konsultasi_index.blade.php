@extends('layouts.mahasiswa')

@section('content')
<h3 class="fw-bold mb-4" style="color: #2b3990;">Kartu Konsultasi</h3>

@if(session('error'))
    <div class="alert alert-danger rounded-4 mb-4 border-0 shadow-sm fw-semibold fs-6">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
    </div>
@endif

<div class="p-4 shadow-sm" style="background-color: #fcf6ef; border-radius: 16px; width: 100%;">
    <div class="mb-3">
        <h4 class="fw-bold m-0" style="color: #2b3990;">Kartu Konsultasi</h4>
        <p class="text-muted fs-6 m-0">Preview dan cetak kartu konsultasi bimbingan Anda</p>
    </div>

    {{-- Alert Kelayakan --}}
    @if($isEligible)
        <div class="alert alert-primary border-0 rounded-3 py-3 px-4 mb-4 fs-6" style="background-color: #e8f1ff; color: #1e429f;">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i> Syarat konsultasi terpenuhi (<strong>{{ $totalDisetujui }}/5 Sesi Terverifikasi</strong>). Kartu konsultasi dapat dicetak.
        </div>
    @else
        <div class="alert alert-warning border-0 rounded-3 py-3 px-4 mb-4 fs-6" style="background-color: #fff3cd; color: #856404;">
            <i class="bi bi-exclamation-circle-fill me-2 fs-5"></i> Syarat konsultasi belum terpenuhi (<strong>{{ $totalDisetujui }}/5 Sesi Terverifikasi</strong>). Anda memerlukan minimal 5 bimbingan yang disetujui dosen untuk dapat mencetak kartu.
        </div>
    @endif

    {{-- Tombol Aksi --}}
    <div class="d-flex justify-content-end gap-3 mb-4">
        <button type="button" class="btn btn-outline-primary rounded-pill px-4 py-2 fw-bold" style="font-size: 14px;" onclick="window.scrollTo({top: 250, behavior: 'smooth'});">Preview</button>
        
        @if($isEligible)
            <a href="{{ route('mahasiswa.kartu.cetak') }}" target="_blank" class="btn btn-primary rounded-pill px-4 py-2 fw-bold" style="background-color: #3b5998; border: none; font-size: 14px;">
                🖨️ Cetak Kartu Konsultasi
            </a>
        @else
            <button class="btn btn-secondary rounded-pill px-4 py-2 fw-bold" disabled style="opacity: 0.65; cursor: not-allowed; font-size: 14px;" title="Minimal 5 bimbingan disetujui">
                🔒 Cetak Kartu Konsultasi
            </button>
        @endif
    </div>

    {{-- Banner Program Studi --}}
    <div class="p-4 text-white mb-4" style="background-color: #1e1b4b; border-radius: 12px;">
        <div class="fw-bold fs-6 text-light-50">Program Studi</div>
        <div class="fw-bold fs-3" style="color: #a5b4fc;">Pendidikan Komputer</div>
        <div class="fs-6 text-light-50">Universitas Lambung Mangkurat</div>
    </div>

    {{-- Detail Biodata --}}
    <div class="p-4 rounded-3 mb-4" style="background-color: #fff8f0; font-size: 15px;">
        <div class="row g-4">
            <div class="col-md-4">
                <span class="text-muted d-block">Nama Mahasiswa</span>
                <strong class="text-dark fs-5">{{ $mahasiswa->nama_mahasiswa }}</strong>
            </div>
            <div class="col-md-4">
                <span class="text-muted d-block">NIM</span>
                <strong class="text-dark fs-5">{{ $mahasiswa->nim }}</strong>
            </div>
            <div class="col-md-4">
                <span class="text-muted d-block">Sekolah Mitra</span>
                <strong class="text-dark fs-5">{{ $plotting->sekolah->nama_sekolah ?? '-' }}</strong>
            </div>
            <div class="col-md-4">
                <span class="text-muted d-block">Dosen Pembimbing</span>
                <strong class="text-dark fs-6">{{ $plotting->dosen->nama_dosen ?? 'Belum ditentukan' }}</strong>
            </div>
            <div class="col-md-4">
                <span class="text-muted d-block">Guru Pamong</span>
                <strong class="text-dark fs-6">{{ $plotting->sekolah->guruPamong->nama_guru_pamong ?? '-' }}</strong>
            </div>
            <div class="col-md-4">
                <span class="text-muted d-block">Periode Asistensi Mengajar</span>
                <strong class="text-dark fs-6">{{ $plotting->periode->nama_periode ?? '2025/2026' }}</strong>
            </div>
        </div>
    </div>

    {{-- Tabel Riwayat Konsultasi --}}
    <h5 class="fw-bold mb-3" style="color: #2b3990;">Riwayat Konsultasi</h5>
    <div class="table-responsive bg-white rounded-3 overflow-hidden shadow-sm">
        <table class="table align-middle m-0" style="font-size: 14px;">
            <thead style="background-color: #1e1b4b; color: white;">
                <tr>
                    <th width="4%" class="text-center py-3">No</th>
                    <th width="12%" class="py-3">Tanggal</th>
                    <th width="12%" class="py-3">Media / Metode</th>
                    <th width="18%" class="py-3">Topik Dibahas</th>
                    <th width="18%" class="py-3">Refleksi Mahasiswa</th>
                    <th width="18%" class="py-3">Saran / Umpan Balik Dosen</th>
                    <th width="18%" class="py-3">Tindak Lanjut Mahasiswa</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayatKonsultasi as $idx => $k)
                <tr @style(['background-color: #fffdf5;' => $idx % 2 == 1])></tr>
                    <td class="text-center fw-bold py-3">{{ $idx + 1 }}</td>
                    <td class="py-3 fw-semibold">{{ \Carbon\Carbon::parse($k->tanggal_konsul)->translatedFormat('d F Y') }}</td>
                    <td class="py-3"><span class="badge bg-light text-dark border px-2 py-1 fs-6">{{ $k->media_konsul }}</span></td>
                    <td class="py-3">{{ $k->topik_dibahas }}</td>
                    <td class="py-3">{{ $k->refleksi_mahasiswa ?? '-' }}</td>
                    <td class="py-3">{{ $k->saran_dosen ?? '-' }}</td>
                    <td class="py-3">{{ $k->tindak_lanjut ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted fs-6">
                        <em>Belum ada bimbingan yang divalidasi/disetujui oleh Dosen Pembimbing.</em>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection