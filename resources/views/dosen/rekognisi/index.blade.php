@extends('layouts.dosen')

@section('content')
<div class="mb-4">
    <h3 class="fw-bold m-0" style="color: #2b3990;">📖 Monitoring Rekognisi Matkul Mahasiswa</h3>
    <p class="text-muted fs-6 m-0">Daftar mahasiswa bimbingan yang mengajukan mata kuliah rekognisi</p>
</div>

{{-- Alert Notifikasi Error / Success --}}
@if(session('error'))
    <div class="alert alert-danger rounded-4 border-0 shadow-sm mb-4 fw-semibold">
        {{ session('error') }}
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success rounded-4 border-0 shadow-sm mb-4 fw-semibold">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-4 shadow-sm p-4">
    <div class="table-responsive">
        <table class="table align-middle text-center m-0" style="font-size: 14px;">
            <thead style="background-color: #1e1b4b; color: white;">
                <tr>
                    <th width="5%" class="py-3">No</th>
                    <th width="35%" class="text-start py-3">Nama Mahasiswa / NIM</th>
                    <th width="20%" class="py-3">Jumlah Matkul Diajukan</th>
                    <th width="20%" class="py-3">Akumulasi SKS Disetujui</th>
                    <th width="20%" class="py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mahasiswaList as $idx => $mhs)
                @php
                    // Hitung total SKS yang sudah disetujui untuk mahasiswa ini
                    $totalSks = $mhs->pengajuanRekognisi->where('status', 'Disetujui')->sum(function($item) {
    return $item->matkulRekognisi->sks ?? $item->sks;
});
                    $maxSks = $mhs->max_sks_rekognisi ?? 20; // Default batas maksimal 20 SKS
                @endphp
                <tr>
                    <td class="fw-bold">{{ $idx + 1 }}</td>
                    <td class="text-start">
                        <strong>{{ $mhs->nama_mahasiswa }}</strong><br>
                        <small class="text-muted">NIM: {{ $mhs->nim }}</small>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border px-3 py-2">
                            {{ $mhs->pengajuanRekognisi->count() }} Mata Kuliah
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-info text-dark px-3 py-2">
                            {{ $totalSks }} / {{ $maxSks }} SKS
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('dosen.rekognisi.show', $mhs->id) }}" class="btn btn-sm btn-primary rounded-pill px-4 fw-bold shadow-sm">
                            Kelola & Verifikasi
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-5 text-muted">Belum ada mahasiswa bimbingan yang mengajukan mata kuliah rekognisi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection