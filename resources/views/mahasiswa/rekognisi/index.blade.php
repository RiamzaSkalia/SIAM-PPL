@extends('layouts.mahasiswa')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="mb-4">
        <h3 class="fw-bold mb-1" style="color: #2b3990;">Rekognisi Mata Kuliah Asistensi Mengajar</h3>
        <p class="text-muted fs-6">
            Ajukan mata kuliah di semester ini yang dapat disetarakan dengan kegiatan Asistensi Mengajar Anda (Maksimal total SKS menyesuaikan pedoman)
        </p>
    </div>

    {{-- Alert Notifikasi --}}
    @if(session('success'))
        <div class="alert alert-success rounded-4 border-0 shadow-sm mb-4 fw-semibold">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger rounded-4 border-0 shadow-sm mb-4 fw-semibold">
            {{ session('error') }}
        </div>
    @endif

    {{-- Form Pengajuan --}}
    <div class="card border-0 shadow-sm rounded-4 p-4" style="background-color: #f7ede2;">
        <form action="{{ route('mahasiswa.rekognisi.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Pilih Mata Kuliah -->
            <div class="mb-3">
                <label class="form-label fw-bold text-dark">Mata Kuliah</label>
                <select name="matkul_rekognisi_id" id="matkulSelect" required class="form-select rounded-3 py-2">
                    <option value="" disabled selected>pilihan mata kuliah</option>
                    @foreach($availableMatkul as $mk)
                        <option value="{{ $mk->id }}" data-sks="{{ $mk->sks }}">
                            {{ $mk->nama_mk }} ({{ $mk->kode_mk }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Jumlah SKS (Otomatis Terisi) -->
            <div class="mb-3">
                <label class="form-label fw-bold text-dark">Jumlah SKS</label>
                <input type="text" id="sksInput" readonly placeholder="Otomatis terisi" class="form-control rounded-3 py-2 bg-light text-muted">
            </div>

            <!-- Upload Bukti Pendukung -->
            <div class="mb-4">
                <label class="form-label fw-bold text-dark">Upload Bukti Pendukung (Opsional)</label>
                <input type="file" name="bukti_pendukung" class="form-control rounded-3 py-2">
            </div>

            <!-- Tombol Aksi -->
            <div class="d-flex align-items-center justify-content-end gap-2 pt-3 border-top">
                <a href="{{ route('mahasiswa.dashboard') }}" class="btn btn-secondary rounded-pill px-4 fw-bold">
                    Batal
                </a>
                <button type="submit" class="btn text-white rounded-pill px-4 fw-bold" style="background-color: #2b3990; border: none;">
                    Ajukan Mata Kuliah Rekognisi
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Script JavaScript untuk mengisi SKS secara otomatis -->
<script>
    document.getElementById('matkulSelect').addEventListener('change', function() {
        var selectedOption = this.options[this.selectedIndex];
        var sks = selectedOption.getAttribute('data-sks');
        document.getElementById('sksInput').value = sks ? sks + ' SKS' : '';
    });
</script>
@endsection