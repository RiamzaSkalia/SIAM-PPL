@extends('layouts.mahasiswa')

@section('content')
<div class="mb-4">
    <h3 class="fw-bold m-0" style="color: #2b3990;">🎓 Pilih Mata Kuliah Rekognisi AM</h3>
    <p class="text-muted fs-6 m-0">Pilih mata kuliah yang ingin Anda ajukan untuk direkognisi dari kegiatan Asistensi Mengajar</p>
</div>

@if(session('success'))
    <div class="alert alert-success rounded-4 border-0 shadow-sm mb-4">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
    </div>
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
                        <th width="35%" class="text-start py-3">Nama Mata Kuliah</th>
                        <th width="10%" class="py-3">SKS</th>
                        <th width="20%" class="py-3">Batas Waktu</th>
                        <th width="12%" class="py-3">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($availableMatkul as $mk)
                    @php
                        $isAlreadySelected = in_array($mk->id, $pengajuanSaya);
                        $isExpired = now()->greaterThan($mk->batas_waktu);
                    @endphp
                    <tr>
                        <td>
                            <input type="checkbox" name="matkul_ids[]" value="{{ $mk->id }}" 
                                   class="form-check-input" style="width: 20px; height: 20px;"
                                   {{ $isAlreadySelected ? 'checked disabled' : '' }}
                                   {{ $isExpired ? 'disabled' : '' }}>
                        </td>
                        <td><span class="badge bg-light text-dark border px-2 py-1">{{ $mk->kode_mk }}</span></td>
                        <td class="text-start fw-semibold">{{ $mk->nama_mk }}</td>
                        <td>{{ $mk->sks }} SKS</td>
                        <td>
                            @if($isExpired)
                                <span class="badge bg-secondary px-3 py-2">🔒 Ditutup</span>
                            @else
                                <span class="badge bg-warning text-dark px-3 py-2">
                                    🕒 {{ \Carbon\Carbon::parse($mk->batas_waktu)->translatedFormat('d M Y, H:i') }}
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($isAlreadySelected)
                                <span class="badge bg-success rounded-pill px-3 py-2">Tergabung 🟢</span>
                            @elseif($isExpired)
                                <span class="badge bg-danger rounded-pill px-3 py-2">Expired 🔴</span>
                            @else
                                <span class="badge bg-info text-dark rounded-pill px-3 py-2">Tersedia 🔵</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-5 text-muted">Belum ada mata kuliah rekognisi yang dibuka oleh Admin.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-end">
        <button type="submit" class="btn text-white rounded-pill px-5 py-2 fw-bold" style="background-color: #3b31b2;">
            💾 Simpan Pilihan Rekognisi
        </button>
    </div>
</form>
@endsection