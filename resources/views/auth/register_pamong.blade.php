<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Guru Pamong - SIAM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('{{ asset("images/bg-siam.jpg") }}') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 28px;
            padding: 35px 30px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            color: white;
        }

        .form-control-glass {
            background: rgba(255, 255, 255, 0.85);
            border: none;
            border-radius: 15px;
            padding: 10px 18px;
            font-size: 13px;
        }

        .btn-daftar {
            background-color: #00c853;
            color: white;
            font-weight: bold;
            border-radius: 20px;
            padding: 10px;
            border: none;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center py-4">

<div class="glass-card text-center">
    <h4 class="fw-bold mb-1">Pendaftaran Guru Pamong</h4>
    <p class="mb-4" style="font-size: 12px; opacity: 0.9;">Masukkan NPSN Sekolah Tempat Anda Bertugas</p>

    @if ($errors->any())
        <div class="alert alert-danger py-2 rounded-4 text-start" style="font-size: 12px;">
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
            <input type="text" name="npsn" class="form-control form-control-glass" placeholder="Contoh: 30304561" value="{{ old('npsn') }}" required>
        </div>

        <div class="mb-2">
            <label class="form-label mb-1" style="font-size: 12px;">NIP / NIK Guru Pamong *</label>
            <input type="text" name="nip_nik" class="form-control form-control-glass" placeholder="Masukkan NIP/NIK" value="{{ old('nip_nik') }}" required>
        </div>

        <div class="mb-2">
            <label class="form-label mb-1" style="font-size: 12px;">Nama Lengkap *</label>
            <input type="text" name="nama_guru_pamong" class="form-control form-control-glass" placeholder="Nama & Gelar" value="{{ old('nama_guru_pamong') }}" required>
        </div>

        <div class="mb-2">
            <label class="form-label mb-1" style="font-size: 12px;">No. WhatsApp *</label>
            <input type="text" name="no_hp" class="form-control form-control-glass" placeholder="08123456789" value="{{ old('no_hp') }}" required>
        </div>

        <div class="mb-2">
            <label class="form-label mb-1" style="font-size: 12px;">Password *</label>
            <input type="password" name="password" class="form-control form-control-glass" placeholder="Buat Password" required>
        </div>

        <div class="mb-4">
            <label class="form-label mb-1" style="font-size: 12px;">Konfirmasi Password *</label>
            <input type="password" name="password_confirmation" class="form-control form-control-glass" placeholder="Ulangi Password" required>
        </div>

        <button type="submit" class="btn btn-daftar w-100 mb-3">DAFTAR SEKARANG</button>
        <div class="text-center">
            <a href="{{ route('login') }}" class="text-white text-decoration-underline" style="font-size: 12px;">Sudah Mempunyai Akun? Login Kembali</a>
        </div>
    </form>
</div>

</body>
</html>