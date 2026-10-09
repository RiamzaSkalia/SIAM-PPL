<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIAM</title>
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
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 24px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            padding: 40px 30px;
            width: 100%;
            max-width: 420px;
        }
        .form-control-glass {
            background: rgba(255, 255, 255, 0.85) !important;
            border: none;
            border-radius: 14px;
            padding: 12px 18px;
            font-size: 14px;
            color: #222;
        }
        .form-control-glass:focus {
            background: rgba(255, 255, 255, 0.98) !important;
            box-shadow: 0 0 0 3px rgba(247, 187, 83, 0.5);
        }
        .btn-login {
            background-color: #f7bb53;
            color: #1e1b4b;
            font-weight: bold;
            border-radius: 14px;
            padding: 12px;
            border: none;
            transition: all 0.2s ease;
        }
        .btn-login:hover {
            background-color: #e2a83e;
            color: #1e1b4b;
        }
        .btn-pamong {
            background: rgba(255, 255, 255, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.6);
            color: white;
            border-radius: 20px;
            padding: 8px 20px;
            font-weight: 600;
            font-size: 13px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.2s ease;
        }
        .btn-pamong:hover {
            background: rgba(255, 255, 255, 0.4);
            color: white;
        }
        .toggle-password {
            cursor: pointer;
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            z-index: 10;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center py-4">

<div class="glass-card text-center">
    <!-- Logo & Title -->
    <div class="mb-4">
        <div class="d-inline-block p-2 rounded-circle mb-2" style="background: rgba(30, 27, 75, 0.8);">
            <i class="bi bi-mortarboard-fill text-white fs-2 px-1"></i>
        </div>
        <h3 class="fw-bold text-white mb-0" style="letter-spacing: 1px;">SIAM</h3>
        <small class="text-white-50" style="font-size: 12px;">Sistem Informasi Asistensi Mengajar</small>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger rounded-3 py-2 px-3 mb-3 text-start" style="font-size: 13px;">
            {{ $errors->first() }}
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success rounded-3 py-2 px-3 mb-3 text-start" style="font-size: 13px;">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('login.post') }}" method="POST">
        @csrf
        <div class="mb-3 text-start">
            <input type="text" name="username" class="form-control form-control-glass" placeholder="NIP / NIM / Username" value="{{ old('username') }}" required>
        </div>

        <div class="mb-4 text-start position-relative">
            <input type="password" name="password" id="loginPassword" class="form-control form-control-glass pe-5" placeholder="Password" required>
            <i class="bi bi-eye-slash-fill toggle-password" id="toggleLoginPassword" onclick="togglePasswordVisibility('loginPassword', 'toggleLoginPassword')"></i>
        </div>

        <button type="submit" class="btn btn-login w-100 mb-4 fs-6">MASUK</button>

        <div class="pt-3 border-top border-white border-opacity-25">
            <p class="text-white-50 mb-2" style="font-size: 12px;">Guru Pamong Belum Punya Akun?</p>
            <a href="{{ route('pamong.register') }}" class="btn-pamong">Sign In / Daftar Guru Pamong</a>
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