@extends('layouts.admin')

@section('content')
<h3 class="fw-bold mb-3" style="color: #2b3990;">Data Dosen</h3>

{{-- Notifikasi Melayang (Position Fixed di Tengah Atas - Tidak Mendorong Layout) --}}
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

<div class="p-3" style="background-color: #f7ede2; border-radius: 12px;">
    <h6 class="fw-bold mb-3"><i class="bi bi-people-fill"></i> Kelola Data Dosen Pembimbing</h6>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <form action="{{ route('admin.dosen.index') }}" method="GET">
            <input type="text" name="search" class="search-input" placeholder="🔍 Cari NIP / Nama Dosen.." value="{{ request('search') }}">
        </form>
        <a href="{{ route('admin.dosen.create') }}" class="btn-tambah">+ Tambah Dosen</a>
    </div>

    <div class="table-responsive">
        <table class="table table-custom align-middle text-center">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="25%">Nama Dosen</th>
                    <th width="20%">NIP/NIDN</th>
                    <th width="20%">Mahasiswa Bimbingan</th>
                    <th width="12%">Total Log</th>
                    <th width="18%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dosen as $index => $item)
                @php
                    // Mengambil semua mahasiswa dari seluruh plotting dosen ini
                    $mhsBimbingan = $item->plotting->pluck('mahasiswa')->flatten();
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-start ps-3 fw-semibold">{{ $item->nama_dosen }}</td>
                    <td>{{ $item->nip }}</td>
                    <td class="text-start ps-3">
                        @if($mhsBimbingan->count() > 0)
                            <ol class="m-0 ps-3" style="font-size: 13px;">
                                @foreach($mhsBimbingan as $mhs)
                                    <li>{{ $mhs->nama_mahasiswa }}</li>
                                @endforeach
                            </ol>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-primary rounded-pill px-3 py-2">{{ $mhsBimbingan->count() }} Mhs</span>
                    </td>
                    <td>
                        <a href="{{ route('admin.dosen.edit', $item->id) }}" class="btn-action-edit me-1">Edit</a>
                        <button type="button" class="btn-action-hapus" onclick="openDeleteModal('{{ $item->id }}')">Hapus</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-4 text-center text-muted">Belum ada data dosen.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Custom Modal Konfirmasi Hapus --}}
@foreach($dosen as $item)
<div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 p-4 text-center border-0 shadow">
            <div class="modal-body p-0">
                <h5 class="fw-bold mb-3" style="color: #2b3990;">Konfirmasi Hapus</h5>
                <p class="text-muted mb-4">Apakah Anda yakin ingin menghapus data <strong>{{ $item->nama_dosen }}</strong>?</p>
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('admin.dosen.destroy', $item->id) }}" method="POST" class="d-inline">
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
    // 1. Fungsi Tutup Notifikasi
    function closeNotification() {
        const alertElement = document.getElementById('success-alert');
        if (alertElement) {
            alertElement.style.opacity = '0';
            alertElement.style.transform = 'translate(-50%, -20px)';
            setTimeout(() => { alertElement.style.display = 'none'; }, 500);
        }
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