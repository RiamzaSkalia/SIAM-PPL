@extends('layouts.mahasiswa')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="mb-4">
        <h3 class="fw-bold mb-1" style="color: #2b3990;">Upload Dokumen</h3>
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

    {{-- Form Upload Dokumen --}}
    <div class="card border-0 shadow-sm rounded-4 p-4" style="background-color: #f7ede2;">
        <div class="mb-3">
            <h5 class="fw-bold text-dark mb-1">Upload Dokumen Pendukung</h5>
            <p class="text-muted small">Unggah laporan, atau dokumen pendukung bimbingan Anda di sini.</p>
        </div>

        <form action="{{ route('mahasiswa.dokumen.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Jenis Dokumen -->
            <div class="mb-3">
                <label class="form-label fw-bold text-dark">Jenis Dokumen</label>
                <select name="jenis_dokumen" required class="form-select rounded-3 py-2">
                    <option value="" disabled selected>seperti Laporan Mingguan, Perangkat Pembelajaran, dll</option>
                    <option value="Laporan Mingguan">Laporan Mingguan</option>
                    <option value="Perangkat Pembelajaran">Perangkat Pembelajaran</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>

            <!-- File Upload Area (Gaya Drag & Drop / Klik) -->
            <div class="mb-3">
                <label class="form-label fw-bold text-dark">File</label>
                <div class="border border-2 border-dashed rounded-3 p-4 text-center bg-white position-relative" style="cursor: pointer; border-color: #cbd5e1 !important;" onclick="document.getElementById('fileInput').click();">
                    <div class="mb-2">
                        <i class="bi bi-upload fs-3 text-secondary"></i>
                    </div>
                    <span class="text-muted small d-block" id="fileLabel">Seret file ke sini atau klik untuk memilih file (PDF, DOCX)</span>
                    <input type="file" name="file" id="fileInput" class="d-none" accept=".pdf,.doc,.docx" required onchange="updateFileName(this)">
                </div>
            </div>

            <!-- Catatan Opsional -->
            <div class="mb-4">
                <label class="form-label fw-bold text-dark">Catatan Opsional</label>
                <textarea name="catatan" rows="3" class="form-control rounded-3" placeholder="catatan tambahan"></textarea>
            </div>

            <!-- Tombol Aksi -->
            <div class="d-flex align-items-center justify-content-end gap-2 pt-3 border-top">
                <button type="submit" class="btn text-white rounded-pill px-4 fw-bold" style="background-color: #2b3990; border: none;">
                    Upload Dokumen
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Script interaktif untuk menampilkan nama file yang dipilih -->
<script>
    function updateFileName(input) {
        const label = document.getElementById('fileLabel');
        if (input.files && input.files[0]) {
            label.textContent = "File terpilih: " + input.files[0].name;
            label.classList.add("text-success", "fw-bold");
        }
    }
</script>
@endsection