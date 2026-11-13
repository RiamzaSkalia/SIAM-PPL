@extends('layouts.admin')

@section('content')
<h3 class="fw-bold mb-3" style="color: #2b3990;">Data Mahasiswa</h3>

{{-- Notifikasi Melayang Responsif (Success & Error) --}}
@if(session('success'))
    <div id="success-alert" class="alert alert-success fade show rounded-4 d-flex align-items-center justify-content-between px-4 py-3 shadow-lg" 
         role="alert" 
         style="position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 9999; width: 92%; max-width: 450px; background-color: #28a745; color: white; border: none; transition: all 0.5s ease;">
        <div class="d-flex align-items-center me-3">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i> 
            <span class="fw-semibold">{{ session('success') }}</span>
        </div>
        <button type="button" class="btn-close btn-close-white ms-auto" onclick="closeNotification()" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div id="error-alert" class="alert alert-danger fade show rounded-4 d-flex align-items-center justify-content-between px-4 py-3 shadow-lg" 
         role="alert" 
         style="position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 9999; width: 92%; max-width: 450px; background-color: #dc3545; color: white; border: none; transition: all 0.5s ease;">
        <div class="d-flex align-items-center me-3">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i> 
            <span class="fw-semibold">{{ session('error') }}</span>
        </div>
        <button type="button" class="btn-close btn-close-white ms-auto" onclick="closeNotification()" aria-label="Close"></button>
    </div>
@endif

<div class="p-3" style="background-color: #f7ede2; border-radius: 12px;">
    <h6 class="fw-bold mb-3"><i class="bi bi-people-fill"></i> Kelola Data Mahasiswa AM</h6>

    {{-- Filter & Action Bar Responsif --}}
    <div class="row g-2 align-items-center mb-3">
        <div class="col-12 col-md-5 col-lg-4">
            <form action="{{ route('admin.mahasiswa.index') }}" method="GET">
                <input type="text" name="search" class="search-input w-100" placeholder="🔍 Cari NIM / Nama Mahasiswa.." value="{{ request('search') }}">
            </form>
        </div>

        <div class="col-12 col-md-7 col-lg-8 d-flex flex-wrap align-items-stretch justify-content-md-end gap-2">
            <a href="{{ route('admin.mahasiswa.export_pdf') }}" target="_blank" 
               class="btn btn-danger rounded-pill px-3 py-2 fw-bold shadow-sm d-inline-flex align-items-center justify-content-center text-nowrap flex-fill flex-sm-grow-0"
               style="line-height: 1.2;">
                📄 Rekap Akun PDF
            </a>
            <a href="{{ route('admin.mahasiswa.create') }}" 
               class="btn-tambah px-3 py-2 d-inline-flex align-items-center justify-content-center text-nowrap flex-fill flex-sm-grow-0"
               style="line-height: 1.2;">
                + Tambah Mahasiswa
            </a>
        </div>
    </div>

    {{-- Tabel Responsif (Scroll Horizontal Mulus di HP) --}}
    <div class="table-responsive">
        <table class="table table-custom align-middle text-center" style="min-width: 880px;">
            <thead>
                <tr class="text-nowrap">
                    <th width="5%">No</th>
                    <th width="14%">NIM</th>
                    <th width="20%">Nama Mahasiswa</th>
                    <th width="20%">Sekolah Mitra</th>
                    <th width="16%">Dosen Pembimbing</th>
                    <th width="10%">Progres</th>
                    <th width="15%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mahasiswa as $index => $item)
                @php
                    // Ambil data plotting pertama mahasiswa (jika ada)
                    $plot = $item->plotting->first();
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-nowrap">{{ $item->nim }}</td>
                    <td class="text-start ps-3 fw-semibold">{{ $item->nama_mahasiswa }}</td>
                    <td>
                        @if($plot && $plot->sekolah)
                            <span class="fw-semibold text-primary">{{ $plot->sekolah->nama_sekolah }}</span>
                            <br><small class="text-muted">({{ $plot->sekolah->jenjang }})</small>
                        @else
                            <span class="badge bg-secondary">Belum Diplot</span>
                        @endif
                    </td>
                    <td>
                        @if($plot && $plot->dosen)
                            <span class="fw-semibold">{{ $plot->dosen->nama_dosen }}</span>
                        @else
                            <span class="badge bg-secondary">Belum Ada DPL</span>
                        @endif
                    </td>
                    <td class="text-nowrap">
                        @if($plot)
                            <span class="badge bg-success rounded-pill px-3 py-2">Aktif AM</span>
                        @else
                            <span class="badge bg-warning text-dark rounded-pill px-3 py-2">Belum Terdaftar</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex justify-content-center gap-1 text-nowrap">
                            <a href="{{ route('admin.mahasiswa.edit', $item->id) }}" class="btn-action-edit">✏️ Edit</a>
                            <button type="button" class="btn-action-hapus" onclick="openDeleteModal('{{ $item->id }}')">🗑️ Hapus</button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-4 text-center text-muted">Belum ada data mahasiswa.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Custom Modal Konfirmasi Hapus --}}
@foreach($mahasiswa as $item)
<div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 p-4 text-center border-0 shadow">
            <div class="modal-body p-0">
                <h5 class="fw-bold mb-3" style="color: #2b3990;">Konfirmasi Hapus</h5>
                <p class="text-muted mb-4">Apakah Anda yakin ingin menghapus data <strong>{{ $item->nama_mahasiswa }}</strong>?</p>
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('admin.mahasiswa.destroy', $item->id) }}" method="POST" class="d-inline">
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
    // 1. Fungsi Tutup Notifikasi (Success & Error)
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

    // Auto dismiss dalam 5 detik
    document.addEventListener("DOMContentLoaded", function () {
        setTimeout(function() {
            closeNotification();
        }, 5000);
    });

    // 2. Fungsi Buka Modal Hapus
    function openDeleteModal(id) {
        const modalElement = document.getElementById('deleteModal' + id);
        if (modalElement) {
            if (typeof bootstrap !== 'undefined') {
                const myModal = new bootstrap.Modal(modalElement);
                myModal.show();
            } else {
                alert('JS Bootstrap belum terhubung di layout utama!');
            }
        }
    }
</script>
@endsection