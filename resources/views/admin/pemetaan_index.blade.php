@extends('layouts.admin')

@section('content')
<h3 class="fw-bold mb-3" style="color: #2b3990;">Pemetaan Penempatan & Bimbingan Asistensi Mengajar</h3>

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

<div class="p-3" style="background-color: #f7ede2; border-radius: 12px;">
    <h6 class="fw-bold mb-3"><i class="bi bi-people-fill"></i> Alokasi Mahasiswa ke Sekolah Mitra, Guru Pamong, dan Dosen Pembimbing (PILKOM ULM)</h6>
    {{-- Filter & Action Bar --}}
    <form action="{{ route('admin.pemetaan.index') }}" method="GET" class="row g-2 align-items-center mb-3">
        <div class="col-md-5">
            <input type="text" name="search" class="search-input w-100" placeholder="🔍 Cari Sekolah / Dosen / Mahasiswa..." value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <select name="periode_id" class="form-select rounded-pill" onchange="this.form.submit()">
                <option value="">-- Semua Periode --</option>
                @foreach($periodeList as $p)
                    <option value="{{ $p->id }}" {{ request('periode_id') == $p->id ? 'selected' : '' }}>{{ $p->nama_periode }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="jenjang" class="form-select rounded-pill" onchange="this.form.submit()">
                <option value="">Jenjang: Semua</option>
                <option value="SMP/MTS" {{ request('jenjang') == 'SMP/MTS' ? 'selected' : '' }}>SMP/MTS</option>
                <option value="SMA/MA/SMK" {{ request('jenjang') == 'SMA/MA/SMK' ? 'selected' : '' }}>SMA/MA/SMK</option>
            </select>
        </div>
        <div class="col-md-2 text-end d-flex gap-1 justify-content-end">
            <a href="{{ route('admin.pemetaan.create') }}" class="btn-tambah text-nowrap">+ Plotting Baru</a>
        </div>
    </form>

    {{-- Tabel Pemetaan --}}
    <div class="table-responsive">
        <table class="table table-custom align-middle text-start">
            <thead>
                <tr class="text-center">
                    <th width="4%">NO</th>
                    <th width="24%">SEKOLAH MITRA & JENJANG</th>
                    <th width="20%">GURU PAMONG</th>
                    <th width="20%">DOSEN PEMBIMBING</th>
                    <th width="24%">MAHASISWA & NO. WA</th>
                    <th width="8%">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($plotting as $index => $item)
                @php
                    $ditempatkan = $item->mahasiswa->count();
                    $kuota = $item->sekolah->kuota ?? 0;
                    $isFull = $ditempatkan >= $kuota;
                @endphp
                <tr>
                    <td class="text-center fw-bold">{{ $index + 1 }}</td>
                    
                    {{-- Column 1: Sekolah Mitra --}}
                    <td>
                        <div class="fw-bold text-primary">{{ $item->sekolah->nama_sekolah ?? '-' }}</div>
                        <small class="d-block text-muted">Jenjang: {{ $item->sekolah->jenjang ?? '-' }}</small>
                        <small class="d-block text-muted">Kuota: {{ $kuota }} / Ditempatkan: {{ $ditempatkan }}</small>
                        <div class="mt-1">
                            @if($isFull)
                                <span class="badge bg-success rounded-pill" style="font-size: 11px;">[ STATUS: FULL 🟢 ]</span>
                            @else
                                <span class="badge bg-warning text-dark rounded-pill" style="font-size: 11px;">[ Sisa Kuota: {{ $kuota - $ditempatkan }} 🟡 ]</span>
                            @endif
                        </div>
                    </td>

                    {{-- Column 2: Guru Pamong --}}
                    <td>
                        <div class="fw-semibold">{{ $item->sekolah->guruPamong->nama_guru_pamong ?? '-' }}</div>
                        <small class="text-muted d-block">WA: {{ $item->sekolah->guruPamong->no_hp ?? '-' }}</small>
                    </td>

                    {{-- Column 3: Dosen Pembimbing --}}
                    <td>
                        <div class="fw-semibold">{{ $item->dosen->nama_dosen ?? '-' }}</div>
                        <small class="text-muted d-block">NIP: {{ $item->dosen->nip ?? '-' }}</small>
                    </td>

                    {{-- Column 4: Mahasiswa List --}}
                    <td>
                        <ol class="ps-3 m-0" style="font-size: 13px;">
                            @foreach($item->mahasiswa as $mhs)
                                <li class="mb-1">
                                    <strong>{{ $mhs->nama_mahasiswa }}</strong>
                                    <span class="text-muted">({{ $mhs->nim }})</span>
                                    @if($mhs->no_hp)
                                        <br><small class="text-success"><i class="bi bi-whatsapp"></i> {{ $mhs->no_hp }}</small>
                                    @endif
                                </li>
                            @endforeach
                        </ol>
                    </td>

                    {{-- Column 5: Aksi --}}
                    <td class="text-center">
                        <a href="{{ route('admin.pemetaan.edit', $item->id) }}" class="btn-action-edit d-inline-block mb-1">✏️ Edit</a>
                        <button type="button" class="btn-action-hapus" onclick="openDeleteModal('{{ $item->id }}')">🗑️ Hapus</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-4 text-center text-muted">
                        Belum ada data pemetaan bimbingan. Klik <b>+ Plotting Baru</b> untuk menambahkan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Hapus --}}
@foreach($plotting as $item)
<div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 p-4 text-center border-0 shadow">
            <div class="modal-body p-0">
                <h5 class="fw-bold mb-3" style="color: #2b3990;">Konfirmasi Hapus</h5>
                <p class="text-muted mb-4">Apakah Anda yakin ingin menghapus data plot bimbingan di <strong>{{ $item->sekolah->nama_sekolah }}</strong>?</p>
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('admin.pemetaan.destroy', $item->id) }}" method="POST" class="d-inline">
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
        const alertElement = document.getElementById('success-alert');
        if (alertElement) {
            alertElement.style.opacity = '0';
            alertElement.style.transform = 'translate(-50%, -20px)';
            setTimeout(() => { alertElement.style.display = 'none'; }, 500);
        }
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