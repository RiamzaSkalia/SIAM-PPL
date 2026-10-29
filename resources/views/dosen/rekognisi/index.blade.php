@extends('layouts.dosen')

@section('content')
<div class="mb-4">
    <h3 class="fw-bold m-0" style="color: #2b3990;">📖 Monitoring Rekognisi Matkul Mahasiswa</h3>
    <p class="text-muted fs-6 m-0">Daftar mata kuliah yang diajukan untuk direkognisi oleh mahasiswa bimbingan Anda</p>
</div>

<div class="bg-white rounded-4 shadow-sm p-4">
    <div class="table-responsive">
        <table class="table align-middle text-center m-0" style="font-size: 14px;">
            <thead style="background-color: #1e1b4b; color: white;">
                <tr>
                    <th width="5%" class="py-3">No</th>
                    <th width="25%" class="text-start py-3">Nama Mahasiswa / NIM</th>
                    <th width="15%" class="py-3">Kode MK</th>
                    <th width="35%" class="text-start py-3">Nama Mata Kuliah Rekognisi</th>
                    <th width="10%" class="py-3">SKS</th>
                    <th width="10%" class="py-3">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengajuanList as $idx => $p)
                <tr>
                    <td class="fw-bold">{{ $idx + 1 }}</td>
                    <td class="text-start">
                        <strong>{{ $p->mahasiswa->nama_mahasiswa }}</strong><br>
                        <small class="text-muted">NIM: {{ $p->mahasiswa->nim }}</small>
                    </td>
                    <td><span class="badge bg-light text-dark border px-2 py-1">{{ $p->matkulRekognisi->kode_mk }}</span></td>
                    <td class="text-start fw-semibold">{{ $p->matkulRekognisi->nama_mk }}</td>
                    <td>{{ $p->matkulRekognisi->sks }} SKS</td>
                    <td><span class="badge bg-success rounded-pill px-3 py-2">Disetujui 🟢</span></td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-5 text-muted">Belum ada mahasiswa bimbingan yang memilih mata kuliah rekognisi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection