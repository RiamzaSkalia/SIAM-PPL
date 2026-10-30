<!DOCTYPE html>
<html>
<head>
    <title>Rekap Mata Kuliah Rekognisi AM</title>
    <style>
        body { font-family: sans-serif; font-size: 11pt; }
        .header { text-align: center; margin-bottom: 20px; text-transform: uppercase; }
        .header h3, .header h4 { margin: 2px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table, th, td { border: 1px solid black; }
        th { background-color: #f2f2f2; padding: 8px; text-align: center; }
        td { padding: 6px; }
    </style>
</head>
<body>
    <div class="header">
        <h3>REKAPITULASI PENGAJUAN MATA KULIAH REKOGNISI</h3>
        <h4>PROGRAM ASISTENSI MENGAJAR - PERIODE {{ $periodeAktif->nama_periode ?? '-' }}</h4>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">NO</th>
                <th width="15%">KODE MK</th>
                <th width="30%">NAMA MATA KULIAH</th>
                <th width="10%">SKS</th>
                <th width="40%">MAHASISWA MENGAJUKAN (NIM)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($matkulList as $idx => $m)
            <tr>
                <td style="text-align: center;">{{ $idx + 1 }}</td>
                <td style="text-align: center;">{{ $m->kode_mk }}</td>
                <td>{{ $m->nama_mk }}</td>
                <td style="text-align: center;">{{ $m->sks }}</td>
                <td>
                    @if($m->pengajuan->count() > 0)
                        <ol style="margin: 0; padding-left: 15px;">
                            @foreach($m->pengajuan as $p)
                                @if($p->mahasiswa)
                                    <li>{{ $p->mahasiswa->nama_mahasiswa }} ({{ $p->mahasiswa->nim }})</li>
                                @endif
                            @endforeach
                        </ol>
                    @else
                        <em>Belum ada pengajuan</em>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center;">Tidak ada data mata kuliah rekognisi.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>