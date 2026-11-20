@extends('layouts.dosen')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h3 class="fw-bold m-0" style="color: #2b3990;">Detail Rekognisi: {{ $mahasiswa->nama_mahasiswa }}</h3>
        <p class="text-muted fs-6 m-0">NIM: {{ $mahasiswa->nim }} | Atur batas SKS dan verifikasi mata kuliah</p>
    </div>
    <a href="{{ route('dosen.rekognisi.index') }}" class="btn btn-secondary rounded-pill px-4 fw-bold">
        Kembali
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success rounded-4 border-0 shadow-sm mb-4 fw-semibold">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger rounded-4 border-0 shadow-sm mb-4 fw-semibold">{{ session('error') }}</div>
@endif

<!-- Kotak Pengaturan Batas Maksimal Total SKS Mahasiswa -->
<div class="card border-0 shadow-sm rounded-4 p-4 mb-4" style="background-color: #f8f9fa;">
    <form action="{{ route('dosen.rekognisi.updateBatas', $mahasiswa->id) }}" method="POST" class="row align-items-end">
        @csrf
        @method('PUT')
        <div class="col-md-8">
            <label class="form-label fw-bold text-dark">Batas Maksimal Total Akumulasi SKS untuk Mahasiswa Ini</label>
            <input type="number" name="maksimal_sks" value="{{ $mahasiswa->max_sks_rekognisi ?? 20 }}" class="form-control rounded-3" min="1" max="24" required>
            <small class="text-muted">Total SKS yang saat ini disetujui: <strong>{{ $totalSksDisetujui }} SKS</strong></small>
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn text-white fw-bold rounded-pill w-100 py-2" style="background-color: #2b3990;">
                Simpan Batas SKS
            </button>
        </div>
    </form>
</div>

<!-- Tabel Daftar Mata Kuliah Rekognisi Mahasiswa -->
<div class="bg-white rounded-4 shadow-sm p-4">
    <h5 class="fw-bold mb-3" style="color: #2b3990;">Daftar Mata Kuliah Rekognisi yang Diajukan</h5>
    <div class="table-responsive">
        <table class="table align-middle text-center m-0" style="font-size: 14px;">
            <thead style="background-color: #1e1b4b; color: white;">
                <tr>
                    <th width="5%" class="py-3">No</th>
                    <th width="15%" class="py-3">Kode MK</th>
                    <th width="35%" class="text-start py-3">Nama Mata Kuliah</th>
                    <th width="15%" class="py-3">Bobot SKS</th>
                    <th width="15%" class="py-3">Aksi Verifikasi</th>
                    <th width="15%" class="py-3">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mahasiswa->pengajuanRekognisi as $idx => $p)
                <tr>
                    <td class="fw-bold">{{ $idx + 1 }}</td>
                    <td><span class="badge bg-light text-dark border px-2 py-1">{{ $p->matkulRekognisi->kode_mk ?? '-' }}</span></td>
                    <td class="text-start fw-semibold">{{ $p->matkulRekognisi->nama_mk ?? '-' }}</td>
                    <td><span class="fw-bold text-primary">{{ $p->matkulRekognisi->sks ?? $p->sks }} SKS</span></td>
                    
                    {{-- Kolom Tombol Aksi Menyetujui --}}
                    <td>
                        @if($p->status != 'Disetujui')
                            <form action="{{ route('dosen.rekognisi.update', $p->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="sks" value="{{ $p->matkulRekognisi->sks ?? $p->sks }}">
                                <button type="submit" class="btn btn-sm btn-success rounded-pill px-3 fw-bold w-100 shadow-sm">
                                    Setujui
                                </button>
                            </form>
                        @else
                            <span class="text-muted small fst-italic">Telah Disetujui</span>
                        @endif
                    </td>

                    <td>
                        @if($p->status == 'Disetujui')
                            <span class="badge bg-success px-3 py-2">Disetujui</span>
                        @else
                            <span class="badge bg-warning text-dark px-3 py-2">Pending</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-4 text-muted">Mahasiswa ini belum mengajukan mata kuliah rekognisi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection