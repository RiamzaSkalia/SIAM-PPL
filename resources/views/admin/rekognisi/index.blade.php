@extends('layouts.admin')

@section('content')
<h3 class="fw-bold mb-3" style="color: #2b3990;">Kelola Mata Kuliah Rekognisi AM</h3>

{{-- Notifikasi Melayang (Floating 5 Detik) --}}
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

@if(session('error') || $errors->any())
    <div id="error-alert" class="alert alert-danger fade show rounded-4 d-flex align-items-center justify-content-between px-4 py-3 shadow-lg" 
         role="alert" 
         style="position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 9999; min-width: 320px; max-width: 450px; background-color: #dc3545; color: white; border: none; transition: all 0.5s ease;">
        <div class="d-flex align-items-center me-3">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i> 
            <span class="fw-semibold">{{ session('error') ?? $errors->first() }}</span>
        </div>
        <button type="button" class="btn-close btn-close-white ms-auto" onclick="closeNotification()" aria-label="Close"></button>
    </div>
@endif

<div class="p-3" style="background-color: #f7ede2; border-radius: 12px;">
    {{-- Header Content & Action Bar --}}
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <h6 class="fw-bold m-0 d-flex align-items-center gap-2">
            <i class="bi bi-journal-bookmark-fill"></i> 
            <span>Daftar Mata Kuliah Rekognisi</span>
            <small class="text-muted fw-normal ms-2">(Periode Aktif: <strong>{{ $periodeAktif->nama_periode ?? 'Belum Ditentukan' }}</strong>)</small>
        </h6>

        <div class="d-flex gap-2 align-items-center ms-auto">
            <button type="button" class="btn btn-warning rounded-pill px-3 fw-bold text-nowrap text-dark" data-bs-toggle="modal" data-bs-target="#modalJadwal">
                ⏱️ Pengaturan Tenggat Waktu
            </button>
            <button type="button" class="btn-tambah text-nowrap" style="border: none;" data-bs-toggle="modal" data-bs-target="#modalTambah">
                + Tambah Matkul Rekognisi
            </button>
        </div>
    </div>

    {{-- Tabel Mata Kuliah Rekognisi --}}
    <div class="table-responsive">
        <table class="table table-custom align-middle text-start">
            <thead>
                <tr class="text-center">
                    <th width="6%">NO</th>
                    <th width="20%">KODE MK</th>
                    <th width="48%">NAMA MATA KULIAH</th>
                    <th width="14%">JUMLAH SKS</th>
                    <th width="12%">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($matkulList as $index => $item)
                <tr>
                    <td class="text-center fw-bold">{{ $index + 1 }}</td>
                    <td class="fw-bold text-primary text-center">{{ $item->kode_mk }}</td>
                    <td class="fw-semibold">{{ $item->nama_mk }}</td>
                    <td class="text-center fw-bold">{{ $item->sks }} SKS</td>
                    <td class="text-center">
                        <button type="button" class="btn-action-hapus" onclick="openDeleteModal('{{ $item->id }}')">🗑️ Hapus</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-4 text-center text-muted">
                        Belum ada mata kuliah rekognisi untuk periode ini. Klik <b>+ Tambah Matkul Rekognisi</b> di atas.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modal 1: Pengaturan Tenggat Waktu & Akses --}}
<div class="modal fade" id="modalJadwal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 p-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold mb-0" style="color: #2b3990;">⏱️ Pengaturan Tenggat Waktu Rekognisi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.rekognisi.jadwal') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Tenggat Waktu Pengajuan (Sistem Otomatis Kunci)</label>
                        <input type="datetime-local" name="tenggat_waktu" class="form-control rounded-3" 
                               value="{{ $pengaturan && $pengaturan->tenggat_waktu ? \Carbon\Carbon::parse($pengaturan->tenggat_waktu)->format('Y-m-d\TH:i') : '' }}">
                        <small class="text-muted d-block mt-1">Kosongkan jika tidak ada batas waktu otomatis.</small>
                    </div>

                    <div class="form-check form-switch p-3 bg-light rounded-3 d-flex align-items-center justify-content-between me-0">
                        <label class="form-check-label fw-bold small mb-0 ms-2" for="isOpenSwitch">Buka Akses Pengajuan untuk Mahasiswa</label>
                        <input class="form-check-input ms-0 fs-5" type="checkbox" name="is_open" id="isOpenSwitch" value="1" {{ ($pengaturan && $pengaturan->is_open) ? 'checked' : '' }}>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4" style="background-color: #2b3990; border: none;">Simpan Pengaturan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal 2: Tambah Matkul Rekognisi --}}
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 p-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold mb-0" style="color: #2b3990;">➕ Tambah Mata Kuliah Rekognisi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.rekognisi.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Kode Mata Kuliah</label>
                        <input type="text" name="kode_mk" class="form-control rounded-3" placeholder="Contoh: PIL301" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Nama Mata Kuliah</label>
                        <input type="text" name="nama_mk" class="form-control rounded-3" placeholder="Contoh: Asistensi Mengajar" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Jumlah SKS</label>
                        <input type="number" name="sks" class="form-control rounded-3" min="1" max="20" placeholder="Contoh: 4" required>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-tambah rounded-pill px-4" style="border: none;">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal 3: Hapus Data --}}
@foreach($matkulList as $item)
<div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 p-4 text-center border-0 shadow">
            <div class="modal-body p-0">
                <h5 class="fw-bold mb-3" style="color: #2b3990;">Konfirmasi Hapus</h5>
                <p class="text-muted mb-4">Apakah Anda yakin ingin menghapus mata kuliah <strong>{{ $item->nama_mk }} ({{ $item->kode_mk }})</strong> dari daftar rekognisi?</p>
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('admin.rekognisi.destroy', $item->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger rounded-pill px-4">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

<script>
    function closeNotification() {
        const alertSuccess = document.getElementById('success-alert');
        const alertError = document.getElementById('error-alert');
        [alertSuccess, alertError].forEach(alertElement => {
            if (alertElement) {
                alertElement.style.opacity = '0';
                alertElement.style.transform = 'translate(-50%, -20px)';
                setTimeout(() => { alertElement.style.display = 'none'; }, 500);
            }
        });
    }

    document.addEventListener("DOMContentLoaded", function () {
        setTimeout(function() { closeNotification(); }, 5000);
    });

    function openDeleteModal(id) {
        const modalElement = document.getElementById('deleteModal' + id);
        if (modalElement && typeof bootstrap !== 'undefined') {
            const myModal = new bootstrap.Modal(modalElement);
            myModal.show();
        }
    }
</script>
@endsection