<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Guru Pamong - SIAM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background: url('/images/bg-siam.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.28);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border: 1px solid rgba(255, 255, 255, 0.45);
            border-radius: 24px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.25);
            padding: 35px 30px;
            width: 100%;
            max-width: 450px;
        }
        .form-control-glass {
            background: rgba(255, 255, 255, 0.9) !important;
            border: none;
            border-radius: 12px;
            padding: 10px 16px;
            font-size: 13px;
            color: #222;
        }
        .form-control-glass:focus {
            background: #ffffff !important;
            box-shadow: 0 0 0 3px rgba(247, 187, 83, 0.5);
        }
        .btn-daftar {
            background-color: #f7bb53;
            color: #1e1b4b;
            font-weight: bold;
            border-radius: 12px;
            padding: 12px;
            border: none;
            transition: all 0.2s ease;
        }
        .btn-daftar:hover {
            background-color: #e2a83e;
            color: #1e1b4b;
        }
        .toggle-password {
            cursor: pointer;
            position: absolute;
            right: 15px;
            top: 36px;
            color: #6c757d;
            z-index: 10;
        }
        label {
            color: #ffffff;
            font-weight: 600;
            text-shadow: 0 1px 2px rgba(0,0,0,0.3);
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center py-4">

<div class="glass-card text-center">
    <div class="mb-3">
        <h4 class="fw-bold text-white mb-1">Pendaftaran Guru Pamong</h4>
        <p class="text-white-50 small m-0" style="font-size: 12px;">Masukkan NPSN Sekolah Tempat Anda Bertugas</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger rounded-3 p-2 mb-3 text-start" style="font-size: 12px;">
            <ul class="m-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pamong.register.post') }}" method="POST" class="text-start">
        @csrf

        <div class="mb-2">
            <label class="form-label mb-1" style="font-size: 12px;">NPSN Sekolah Mitra *</label>
            <input type="text" name="npsn" class="form-control form-control-glass" value="{{ old('npsn') }}" placeholder="Contoh: 30304561" required>
        </div>

        <div class="mb-2">
            <label class="form-label mb-1" style="font-size: 12px;">NIP / NIK Guru Pamong *</label>
            <input type="text" name="nip_nik" class="form-control form-control-glass" value="{{ old('nip_nik') }}" placeholder="Masukkan NIP/NIK" required>
            <small class="text-warning d-block mt-1" style="font-size: 11px;">
                <i class="bi bi-info-circle me-1"></i>NIP/NIK ini akan digunakan sebagai <strong>Username Login</strong> Anda.
            </small>
        </div>

        <div class="mb-2">
            <label class="form-label mb-1" style="font-size: 12px;">Nama Lengkap & Gelar *</label>
            <input type="text" name="nama_guru_pamong" class="form-control form-control-glass" value="{{ old('nama_guru_pamong') }}" placeholder="Nama & Gelar" required>
        </div>

        <div class="mb-2">
            <label class="form-label mb-1" style="font-size: 12px;">No. WhatsApp *</label>
            <input type="text" name="no_hp" class="form-control form-control-glass" value="{{ old('no_hp') }}" placeholder="081234567890" required>
        </div>

        <div class="mb-2 position-relative">
            <label class="form-label mb-1" style="font-size: 12px;">Password *</label>
            <input type="password" name="password" id="regPassword" class="form-control form-control-glass pe-5" placeholder="Buat Password" required>
            <i class="bi bi-eye-slash-fill toggle-password" id="toggleRegPassword" onclick="togglePasswordVisibility('regPassword', 'toggleRegPassword')"></i>
        </div>

        <div class="mb-4 position-relative">
            <label class="form-label mb-1" style="font-size: 12px;">Konfirmasi Password *</label>
            <input type="password" name="password_confirmation" id="regPasswordConfirm" class="form-control form-control-glass pe-5" placeholder="Ulangi Password" required>
            <i class="bi bi-eye-slash-fill toggle-password" id="toggleRegPasswordConfirm" onclick="togglePasswordVisibility('regPasswordConfirm', 'toggleRegPasswordConfirm')"></i>
        </div>

        <button type="submit" class="btn btn-daftar w-100 mb-3 fs-6">DAFTAR SEKARANG</button>

        <div class="text-center">
            <a href="{{ route('login') }}" class="text-white text-decoration-underline" style="font-size: 12px;">Sudah Mempunyai Akun? Login Kembali</a>
        </div>
    </form>
</div>

<script>
    function togglePasswordVisibility(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("bi-eye-slash-fill");
            icon.classList.add("bi-eye-fill");
        } else {
            input.type = "password";
            icon.classList.remove("bi-eye-fill");
            icon.classList.add("bi-eye-slash-fill");
        }
    }
</script>

</body>
</html>