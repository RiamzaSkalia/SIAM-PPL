<!DOCTYPE html>
<html>
<head>
    <title>Penempatan Mahasiswa Asistensi Mengajar</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10px; }
        .text-center { text-align: center; }
        .header { font-weight: bold; font-size: 12px; margin-bottom: 15px; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 5px; vertical-align: top; }
        th { background-color: #f2f2f2; text-align: center; }
    </style>
</head>
<body>

<div class="text-center header">
    FORM PENEMPATAN MAHASISWA ASISTENSI MENGAJAR DI SEKOLAH DAN LEMBAGA PRAKTEK<br>
    PROGRAM STUDI PENDIDIKAN ILMU KOMPUTER (PILKOM ULM)
</div>

<table>
    <thead>
        <tr>
            <th width="3%">NO</th>
            <th width="20%">NAMA SEKOLAH</th>
            <th width="10%">JENJANG</th>
            <th width="6%">KUOTA MHS</th>
            <th width="8%">JUMLAH DITEMPATKAN</th>
            <th width="15%">NIM</th>
            <th width="18%">NAMA MAHASISWA</th>
            <th width="12%">NO. HP/WA MAHASISWA</th>
            <th width="18%">NAMA DOSEN PEMBIMBING</th>
        </tr>
    </thead>
    <tbody>
        @foreach($plotting as $index => $item)
        <tr>
            <td class="text-center">{{ $index + 1 }}</td>
            <td>{{ $item->sekolah->nama_sekolah ?? '-' }}</td>
            <td class="text-center">{{ $item->sekolah->jenjang ?? '-' }}</td>
            <td class="text-center">{{ $item->sekolah->kuota ?? 0 }}</td>
            <td class="text-center">{{ $item->mahasiswa->count() }}</td>
            <td>
                @foreach($item->mahasiswa as $idx => $mhs)
                    {{ $idx + 1 }}. {{ $mhs->nim }}<br>
                @endforeach
            </td>
            <td>
                @foreach($item->mahasiswa as $idx => $mhs)
                    {{ $idx + 1 }}. {{ $mhs->nama_mahasiswa }}<br>
                @endforeach
            </td>
            <td>
                @foreach($item->mahasiswa as $idx => $mhs)
                    {{ $idx + 1 }}. {{ $mhs->no_hp ?? '-' }}<br>
                @endforeach
            </td>
            <td>{{ $item->dosen->nama_dosen ?? '-' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>