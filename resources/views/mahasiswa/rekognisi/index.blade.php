@extends('layouts.mahasiswa')

@section('content')
<div class="mb-4">
    <h3 class="fw-bold m-0" style="color: #2b3990;">🎓 Pengajuan Mata Kuliah Rekognisi AM</h3>
    <p class="text-muted fs-6 m-0">Pilih mata kuliah yang ingin Anda ajukan untuk direkognisi dari kegiatan Asistensi Mengajar</p>
</div>

{{-- Alert Notifikasi --}}
@if(session('success'))
    <div class="alert alert-success rounded-4 border-0 shadow-sm mb-4 fw-semibold">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger rounded-4 border-0 shadow-sm mb-4 fw-semibold">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
    </div>
@endif

{{-- Banner Status Jadwal Pengajuan --}}
@if($isLocked)
    <div class="alert alert-danger rounded-4 border-0 p-4 mb-4 shadow-sm">
        <h5 class="fw-bold m-0 mb-1"><i class="bi bi-lock-fill me-2"></i>Pengajuan Rekognisi Telah Ditutup!</h5>
        <p class="m-0 fs-6">
            @if($isClosedManual)
                Admin telah menutup sesi pengajuan mata kuliah rekognisi untuk periode ini.
            @elseif($isExpired)
                Batas waktu pengajuan telah berakhir pada <strong>{{ \Carbon\Carbon::parse($pengaturan->tenggat_waktu)->translatedFormat('d F Y, H:i') }} WITA</strong>. Anda tidak dapat melakukan perubahan atau pengajuan baru.
            @endif
        </p>
    </div>
@else
    @if($pengaturan && $pengaturan->tenggat_waktu)
        <div class="alert alert-warning rounded-4 border-0 p-3 mb-4 shadow-sm text-dark fs-6">
            <i class="bi bi-clock-history me-2 fs-5"></i> Sesi pengajuan dibuka hingga: <strong>{{ \Carbon\Carbon::parse($pengaturan->tenggat_waktu)->translatedFormat('d F Y - H:i') }} WITA</strong>
        </div>
    @endif
@endif

<form action="{{ route('mahasiswa.rekognisi.store') }}" method="POST">
    @csrf
    <div class="bg-white rounded-4 shadow-sm p-4 mb-4">
        <div class="table-responsive">
            <table class="table align-middle text-center m-0" style="font-size: 14px;">
                <thead style="background-color: #1e1b4b; color: white;">
                    <tr>
                        <th width="8%" class="py-3">Pilih</th>
                        <th width="15%" class="py-3">Kode MK</th>
                        <th width="40%" class="text-start py-3">Nama Mata Kuliah</th>
                        <th width="15%" class="py-3">Bobot SKS</th>
                        <th width="22%" class="py-3">Status Pengajuan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($availableMatkul as $mk)
                    @php
                        $isAlreadySelected = in_array($mk->id, $pengajuanSaya);
                    @endphp
                    <tr>
                        <td>
                            <input type="checkbox" name="matkul_ids[]" value="{{ $mk->id }}" 
                                   class="form-check-input" style="width: 20px; height: 20px;"
                                   {{ $isAlreadySelected ? 'checked disabled' : '' }}
                                   {{ $isLocked ? 'disabled' : '' }}>
                        </td>
                        <td><span class="badge bg-light text-dark border px-2 py-1 fs-6">{{ $mk->kode_mk }}</span></td>
                        <td class="text-start fw-semibold fs-6">{{ $mk->nama_mk }}</td>
                        <td class="fs-6">{{ $mk->sks }} SKS</td>
                        <td>
                            @if($isAlreadySelected)
                                <span class="badge bg-success rounded-pill px-3 py-2 fs-6">Telah Diajukan 🟢</span>
                            @elseif($isLocked)
                                <span class="badge bg-secondary rounded-pill px-3 py-2 fs-6">Terkunci 🔒</span>
                            @else
                                <span class="badge bg-primary rounded-pill px-3 py-2 fs-6">Tersedia 🔵</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-5 text-muted fs-6">Belum ada mata kuliah rekognisi yang dibuka oleh Admin.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-end">
        @if(!$isLocked)
            <button type="submit" class="btn text-white rounded-pill px-5 py-2 fw-bold" style="background-color: #3b31b2; font-size: 15px;">
                💾 Simpan Pilihan Rekognisi
            </button>
        @else
            <button type="button" class="btn btn-secondary rounded-pill px-5 py-2 fw-bold" disabled style="opacity: 0.65; cursor: not-allowed; font-size: 15px;">
                🔒 Pengajuan Ditutup
            </button>
        @endif
    </div>
</form>
@endsection