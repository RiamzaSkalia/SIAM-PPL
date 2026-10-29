@extends('layouts.admin')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h3 class="fw-bold m-0" style="color: #2b3990;">📚 Kelola Mata Kuliah Rekognisi AM</h3>
        <p class="text-muted fs-6 m-0">Periode Aktif: <strong>{{ $periodeAktif->nama_periode ?? 'Belum Ditentukan' }}</strong></p>
    </div>
    <button class="btn text-white rounded-pill px-4 py-2 fw-bold" style="background-color: #3b31b2;" data-bs-toggle="modal" data-bs-target="#modalTambahMatkul">
        ➕ Tambah Matkul Rekognisi
    </button>
</div>

@if(session('success'))
    <div class="alert alert-success rounded-4 border-0 shadow-sm mb-4">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-4 shadow-sm p-4">
    <div class="table-responsive">
        <table class="table align-middle text-center m-0" style="font-size: 14px;">
            <thead style="background-color: #1e1b4b; color: white;">
                <tr>
                    <th width="5%" class="py-3">No</th>
                    <th width="15%" class="py-3">Kode MK</th>
                    <th width="35%" class="text-start py-3">Nama Mata Kuliah</th>
                    <th width="10%" class="py-3">SKS</th>
                    <th width="20%" class="py-3">Batas Waktu Rekognisi</th>
                    <th width="15%" class="py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($matkulList as $idx => $mk)
                <tr>
                    <td class="fw-bold">{{ $idx + 1 }}</td>
                    <td><span class="badge bg-light text-dark border px-2 py-1">{{ $mk->kode_mk }}</span></td>
                    <td class="text-start fw-semibold">{{ $mk->nama_mk }}</td>
                    <td>{{ $mk->sks }} SKS</td>
                    <td>
                        <span class="badge bg-warning text-dark px-3 py-2">
                            🕒 {{ \Carbon\Carbon::parse($mk->batas_waktu)->translatedFormat('d M Y, H:i') }}
                        </span>
                    </td>
                    <td>
                        <form action="{{ route('admin.rekognisi.destroy', $mk->id) }}" method="POST" onsubmit="return confirm('Hapus mata kuliah ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-outline-danger btn-sm rounded-pill px-3">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-5 text-muted">Belum ada mata kuliah rekognisi untuk periode ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Matkul -->
<div class="modal fade" id="modalTambahMatkul" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 p-4 border-0">
            <h5 class="fw-bold mb-3" style="color: #2b3990;">Tambah Mata Kuliah Rekognisi</h5>
            <form action="{{ route('admin.rekognisi.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold small">Kode Mata Kuliah</label>
                    <input type="text" name="kode_mk" class="form-control rounded-3" placeholder="Contoh: PIL301" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Nama Mata Kuliah</label>
                    <input type="text" name="nama_mk" class="form-control rounded-3" placeholder="Contoh: Pembelajaran Digital" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Jumlah SKS</label>
                    <input type="number" name="sks" class="form-control rounded-3" min="1" max="6" value="3" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Batas Waktu Rekognisi (Penutupan)</label>
                    <input type="datetime-local" name="batas_waktu" class="form-control rounded-3" required>
                </div>
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn text-white rounded-pill px-4" style="background-color: #3b31b2;">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection