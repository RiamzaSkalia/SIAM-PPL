<!DOCTYPE html>
<html>
<head>
    <title>Rekap Akun Mahasiswa AM</title>
    <style>
        body { font-family: sans-serif; font-size: 10.5pt; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h3, .header h4 { margin: 2px; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table, th, td { border: 1px solid #000; padding: 8px 6px; }
        th { background-color: #f2f2f2; text-align: center; font-weight: bold; }
        .code-text { font-family: monospace; font-weight: bold; font-size: 11pt; letter-spacing: 0.5px; }
    </style>
</head>
<body>
    <div class="header">
        <h3>DAFTAR REKAP AKUN MAHASISWA ASISTENSI MENGAJAR</h3>
        <h4>PROGRAM ASISTENSI MENGAJAR {{ $activePeriode->nama_periode ?? '' }}</h4>
    </div>

    <table>
        <thead>
            <tr>
                <th width="6%">NO</th>
                <th width="34%">NAMA MAHASISWA</th>
                <th width="25%">USERNAME (NIM)</th>
                <th width="20%">PASSWORD</th>
            </tr>
        </thead>
        <tbody>
            @forelse($mahasiswaList as $idx => $m)
            <tr>
                <td style="text-align: center;">{{ $idx + 1 }}</td>
                <td><strong>{{ $m->nama_mahasiswa }}</strong></td>
                <td style="text-align: center;" class="code-text">{{ $m->nim }}</td>
                <td style="text-align: center; color: #d9534f;" class="code-text">{{ $m->plain_password ?? '123456' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center;">Belum ada data mahasiswa.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>