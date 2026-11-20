@extends('layouts.mahasiswa')

@section('content')
<div class="container-fluid px-3 px-md-4">
    <!-- Header -->
    <div class="mb-3 mb-md-4">
        <h3 class="fw-bold mb-1 fs-4 fs-md-3" style="color: #2b3990;">Upload Dokumen</h3>
    </div>

    {{-- Form Upload Dokumen --}}
    <div class="card border-0 shadow-sm rounded-4 p-3 p-md-4" style="background-color: #f7ede2;">
        
        {{-- Alert Notifikasi disamakan persis dengan halaman konsultasi --}}
        @if(session('success'))
            <div class="alert alert-success d-flex align-items-start align-items-sm-center rounded-3 border-0 shadow-sm mb-4" role="alert" style="background-color: #d1e7dd; color: #0f5132;">
                <i class="bi bi-check-circle-fill me-2 fs-5 flex-shrink-0"></i>
                <div class="fw-semibold small fs-sm-6 text-break">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger d-flex align-items-start align-items-sm-center rounded-3 border-0 shadow-sm mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5 flex-shrink-0"></i>
                <div class="fw-semibold small fs-sm-6 text-break">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <div class="mb-3">
            <h5 class="fw-bold text-dark mb-1 fs-5">Form Unggah Berkas</h5>
            <p class="text-muted small fs-sm-6 m-0">Unggah laporan, atau dokumen pendukung bimbingan Anda di sini.</p>
        </div>

        <form action="{{ route('mahasiswa.dokumen.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Jenis Dokumen -->
<div class="mb-3">
    <label class="form-label fw-bold text-dark small fs-sm-6">Jenis Dokumen <span class="text-danger">*</span></label>
    <select name="jenis_dokumen" required class="form-select rounded-3 py-2 bg-white pe-5">
        <option value="" disabled selected>Pilih jenis dokumen...</option>
        <option value="Laporan Mingguan">Laporan Mingguan</option>
        <option value="Perangkat Pembelajaran">Perangkat Pembelajaran</option>
        <option value="Lainnya">Lainnya</option>
    </select>
</div>

            <!-- File Upload Area (Gaya Drag & Drop / Klik) -->
            <div class="mb-3">
                <label class="form-label fw-bold text-dark small fs-sm-6">File <span class="text-danger">*</span></label>
                <div id="dropArea" class="border border-2 border-dashed rounded-3 p-4 p-sm-5 text-center bg-white position-relative" style="cursor: pointer; border-color: #cbd5e1 !important; transition: all 0.2s ease;" onclick="document.getElementById('fileInput').click();">
                    <div class="mb-2">
                        <i class="bi bi-upload fs-3 text-secondary"></i>
                    </div>
                    <span class="text-muted small fs-sm-6 d-block text-break" id="fileLabel">Seret file ke sini atau klik untuk memilih file (PDF, DOCX)</span>
                    <input type="file" name="file" id="fileInput" class="d-none" accept=".pdf,.doc,.docx" required onchange="updateFileName(this)">
                </div>
            </div>

            <!-- Catatan Opsional -->
            <div class="mb-4">
                <label class="form-label fw-bold text-dark small fs-sm-6">Catatan Opsional</label>
                <textarea name="catatan" rows="3" class="form-control rounded-3 bg-white" placeholder="catatan tambahan"></textarea>
            </div>

            <!-- Tombol Aksi -->
            <div class="d-flex align-items-center justify-content-stretch justify-content-sm-end gap-2 pt-3 border-top">
                <button type="submit" class="btn text-white rounded-pill px-4 py-2 fw-bold w-100 w-sm-auto" style="background-color: #2b3990; border: none; font-size: 14px;">
                    Upload Dokumen
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Script interaktif untuk menampilkan nama file yang dipilih & drag-drop -->
<script>
    function updateFileName(input) {
        const label = document.getElementById('fileLabel');
        if (input.files && input.files[0]) {
            label.textContent = "File terpilih: " + input.files[0].name;
            label.classList.remove("text-muted");
            label.classList.add("text-success", "fw-bold");
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const dropArea = document.getElementById('dropArea');
        const fileInput = document.getElementById('fileInput');
        if (!dropArea || !fileInput) return;

        ['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, function (e) {
                e.preventDefault();
                e.stopPropagation();
                dropArea.style.borderColor = '#2b3990';
                dropArea.style.backgroundColor = '#f8faff';
            });
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, function (e) {
                e.preventDefault();
                e.stopPropagation();
                dropArea.style.borderColor = '#cbd5e1';
                dropArea.style.backgroundColor = '#ffffff';
            });
        });

        dropArea.addEventListener('drop', function (e) {
            const files = e.dataTransfer?.files;
            if (files && files.length > 0) {
                fileInput.files = files;
                updateFileName(fileInput);
            }
        });
    });
</script>
@endsection