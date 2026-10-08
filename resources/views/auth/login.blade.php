<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIAM</title>
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
            padding: 40px 30px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            color: white;
        }

        .form-control-glass {
            background: rgba(255, 255, 255, 0.7);
            border: none;
            border-radius: 20px;
            padding: 12px 20px;
            font-size: 14px;
            color: #333;
        }

        .form-control-glass::placeholder { color: #777; }

        .btn-masuk {
            background-color: #f7bb53;
            color: #222;
            font-weight: bold;
            border-radius: 20px;
            padding: 10px;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-masuk:hover { background-color: #e2a83e; }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center">

<div class="glass-card text-center">
    {{-- Logo & Header SIAM --}}
    <div class="mb-4">
        <img src="{{ asset('images/logo-siam.png') }}" alt="Logo SIAM" style="height: 80px;" class="mb-2" onerror="this.src='https://cdn-icons-png.flaticon.com/512/2997/2997313.png'">
        <h2 class="fw-bold m-0" style="letter-spacing: 2px;">SIAM</h2>
        <small class="d-block" style="font-size: 13px; opacity: 0.9;">Sistem Informasi Asistensi Mengajar</small>
    </div>

    @if(session('success'))
        <div class="alert alert-success py-2 rounded-4 text-center" style="font-size: 13px;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->has('username'))
        <div class="alert alert-danger py-2 rounded-4 text-center" style="font-size: 13px;">
            {{ $errors->first('username') }}
        </div>
    @endif

    {{-- Form Login --}}
    <form action="{{ route('login.post') }}" method="POST" class="text-start">
        @csrf
        <div class="mb-3">
            <input type="text" name="username" class="form-control form-control-glass" placeholder="NIP / NIM / Username" value="{{ old('username') }}" required>
        </div>

        <div class="mb-4">
            <input type="password" name="password" class="form-control form-control-glass" placeholder="Password" required>
        </div>

        <button type="submit" class="btn btn-masuk w-100 mb-3">MASUK</button>
    </form>

    <div class="mt-3 pt-2 border-top border-light-subtle" style="font-size: 12px; opacity: 0.9;">
        <p class="mb-1">Guru Pamong Belum Punya Akun?</p>
        <a href="{{ route('pamong.register') }}" class="btn btn-sm btn-outline-light rounded-pill px-3 fw-bold">Sign In / Daftar Guru Pamong</a>
        <div class="mt-3 text-white-50" style="font-size: 11px;">
            Akun Admin, Dosen & Mahasiswa dibuatkan oleh Admin.<br>
            Hubungi Koordinator jika bermasalah.
        </div>
    </div>
</div>

</body>
</html>