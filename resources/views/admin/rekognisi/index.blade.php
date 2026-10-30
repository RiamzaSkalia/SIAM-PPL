@extends('layouts.admin')

@section('content')
<h3 class="fw-bold mb-3" style="color: #2b3990;">Kelola Mata Kuliah Rekognisi AM</h3>

{{-- Notifikasi Melayang (Floating) --}}
@if(session('success'))
    <div id="success-alert" class="alert alert-success fade show rounded-4 d-flex align-items-center justify-content-between px-4 py-3 shadow-lg" 
         role="alert" 
         style="position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 9999; min-width: 320px; max-width: 450px; background-color: #28a745; color: white; border: none; transition: all 0.5s ease;">
        <div class="d-flex align-items-center me-3">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i> 
            <span class="fw-semibold">{{ session('success') }}</span>
        </div>
        <button type="button" class="btn-close btn-close-white ms-auto" onclick="closeNotification()" aria-label="Close"></button>
    </div>
@endif

<div class="p-4 mb-4" style="background-color: #f7ede2; border-radius: 12px;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h6 class="fw-bold m-0" style="color: #2b3990;"><i class="bi bi-journal-bookmark-fill me-2"></i>Daftar Mata Kuliah Rekognisi</h6>
            <small class="text-muted">Periode Aktif: <strong>({{ $periodeAktif->nama_periode ?? 'Belum Ditentukan' }})</strong></small>
        </div>
        
        {{-- Tombol Aksi Kanan (Export PDF Merah, Pengaturan, Tambah) --}}
        <div class="d-flex gap-2">
            <a href="{{ route('admin.rekognisi.export.pdf') }}" target="_blank" class="btn btn-danger rounded-pill px-3 fw-bold shadow-sm d-flex align-items-center">
                📄 Export PDF
            </a>
            <button type="button" class="btn btn-warning rounded-pill px-3 fw-bold text-dark border-0 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalJadwal">
                ⏱️ Pengaturan Tenggat Waktu
            </button>
            <button type="button" class="btn text-white rounded-pill px-4 fw-bold shadow-sm" style="background-color: #00c853;" data-bs-toggle="modal" data-bs-target="#modalTambah">
                + Tambah Matkul Rekognisi
            </button>
        </div>
    </div>

    {{-- Banner Status Tenggat --}}
    @if($pengaturan)
        <div class="alert {{ ($pengaturan->is_open && (!$pengaturan->tenggat_waktu || now()->lessThanOrEqualTo($pengaturan->tenggat_waktu))) ? 'alert-info' : 'alert-danger' }} rounded-3 mb-3 border-0 py-2 px-3 small d-flex justify-content-between align-items-center">
            <div>
                <strong>Status Akses Mahasiswa:</strong> 
                @if($pengaturan->is_open && (!$pengaturan->tenggat_waktu || now()->lessThanOrEqualTo($pengaturan->tenggat_waktu)))
                    <span class="badge bg-success">DIBUKA 🟢</span>
                @else
                    <span class="badge bg-danger">DITUTUP 🔴</span>
                @endif
                <span class="ms-3">Batas Waktu: <strong>{{ $pengaturan->tenggat_waktu ? \Carbon\Carbon::parse($pengaturan->tenggat_waktu)->translatedFormat('d F Y - H:i') . ' WITA' : 'Tidak Ada Batas Waktu' }}</strong></span>
            </div>
        </div>
    @endif

    {{-- Tabel Data Matkul & Mahasiswa Pengaju --}}
    <div class="table-responsive bg-white rounded-3 shadow-sm overflow-hidden">
        <table class="table align-middle text-center m-0" style="font-size: 14px;">
            <thead style="background-color: #536dfe; color: white;">
                <tr>
                    <th width="5%" class="py-3">NO</th>
                    <th width="12%" class="py-3">KODE MK</th>
                    <th width="25%" class="text-start py-3">NAMA MATA KULIAH</th>
                    <th width="10%" class="py-3">JUMLAH SKS</th>
                    <th width="35%" class="text-start py-3">MAHASISWA MENGAJUKAN</th>
                    <th width="13%" class="py-3">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($matkulList as $index => $item)
                <tr>
                    <td class="fw-bold">{{ $index + 1 }}</td>
                    <td><span class="fw-bold text-primary">{{ $item->kode_mk }}</span></td>
                    <td class="text-start ps-3 fw-semibold">{{ $item->nama_mk }}</td>
                    <td>{{ $item->sks }} SKS</td>
                    <td class="text-start ps-3">
                        @if($item->pengajuan && $item->pengajuan->count() > 0)
                            <div style="max-height: 90px; overflow-y: auto;">
                                @foreach($item->pengajuan as $p)
                                    @if($p->mahasiswa)
                                        <div class="mb-1">
                                            <span class="fw-semibold text-dark">• {{ $p->mahasiswa->nama_mahasiswa }}</span> 
                                            <small class="text-muted">({{ $p->mahasiswa->nim }})</small>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <small class="text-success fw-bold">Total: {{ $item->pengajuan->count() }} Mahasiswa</small>
                        @else
                            <span class="badge bg-light text-muted border">Belum Ada Pengajuan</span>
                        @endif
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger rounded-pill px-3 fw-bold" onclick="openDeleteModal('{{ $item->id }}')">
                            🗑️ Hapus
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-5 text-center text-muted">Belum ada mata kuliah rekognisi untuk periode ini. Klik <strong>+ Tambah Matkul Rekognisi</strong> di atas.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL TAMBAH MATKUL --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 p-4 border-0 shadow">
            <h5 class="fw-bold mb-3" style="color: #2b3990;">➕ Tambah Mata Kuliah Rekognisi</h5>
            <form action="{{ route('admin.rekognisi.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold small">Kode Mata Kuliah <span class="text-danger">*</span></label>
                    <input type="text" name="kode_mk" class="form-control rounded-3" placeholder="Contoh: PIL301" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Nama Mata Kuliah <span class="text-danger">*</span></label>
                    <input type="text" name="nama_mk" class="form-control rounded-3" placeholder="Contoh: Pembelajaran Digital" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Jumlah SKS <span class="text-danger">*</span></label>
                    <input type="number" name="sks" class="form-control rounded-3" min="1" max="6" value="3" required>
                </div>
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn text-white rounded-pill px-4 fw-bold" style="background-color: #3b31b2;">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL PENGATURAN TENGGAT --}}
<div class="modal fade" id="modalJadwal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 p-4 border-0 shadow">
            <h5 class="fw-bold mb-3" style="color: #2b3990;">⏱️ Pengaturan Tenggat Waktu Rekognisi</h5>
            <form action="{{ route('admin.rekognisi.jadwal') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold small">Tenggat Waktu Pengajuan</label>
                    <input type="datetime-local" name="tenggat_waktu" class="form-control rounded-3" 
                           value="{{ $pengaturan && $pengaturan->tenggat_waktu ? \Carbon\Carbon::parse($pengaturan->tenggat_waktu)->format('Y-m-d\TH:i') : '' }}">
                </div>

                <div class="form-check form-switch mb-4 p-3 bg-light rounded-3">
                    <input class="form-check-input ms-0 me-2" type="checkbox" name="is_open" id="isOpenSwitch" {{ ($pengaturan && $pengaturan->is_open) ? 'checked' : '' }}>
                    <label class="form-check-input-label fw-bold small" for="isOpenSwitch">Buka Akses Pengajuan untuk Mahasiswa</label>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold">💾 Simpan Pengaturan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Hapus --}}
@foreach($matkulList as $item)
<div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 p-4 text-center border-0 shadow">
            <h5 class="fw-bold mb-3" style="color: #2b3990;">Konfirmasi Hapus</h5>
            <p class="text-muted mb-4">Hapus mata kuliah <strong>{{ $item->nama_mk }}</strong>?</p>
            <div class="d-flex justify-content-center gap-2">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.rekognisi.destroy', $item->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger rounded-pill px-4">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

<script>
    function closeNotification() {
        const alertElement = document.getElementById('success-alert');
        if (alertElement) {
            alertElement.style.opacity = '0';
            setTimeout(() => { alertElement.style.display = 'none'; }, 500);
        }
    }
    document.addEventListener("DOMContentLoaded", function () {
        setTimeout(closeNotification, 5000);
    });
    function openDeleteModal(id) {
        const modalElement = document.getElementById('deleteModal' + id);
        if (modalElement) {
            new bootstrap.Modal(modalElement).show();
        }
    }
</script>
@endsection