<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Konsultasi Asistensi Mengajar</title>
    <style>
        @page { size: A4 landscape; margin: 12mm 15mm 12mm 15mm; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 10.5pt; line-height: 1.2; color: #000; }
        
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
        .header-table td { vertical-align: middle; }
        .logo { width: 80px; height: auto; }
        .header-text { text-align: center; }
        .header-text h3 { margin: 0; font-size: 11pt; font-weight: bold; text-transform: uppercase; }
        .header-text h2 { margin: 2px 0; font-size: 12pt; font-weight: bold; text-transform: uppercase; }
        .header-text h1 { margin: 0; font-size: 13pt; font-weight: bold; text-transform: uppercase; }
        .header-text p { margin: 2px 0 0 0; font-size: 8pt; font-style: italic; }
        
        .line-double { border-top: 2px solid #000; border-bottom: 1px solid #000; height: 2px; margin-bottom: 12px; }

        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; font-size: 10pt; }
        .info-table td { padding: 2px 0; vertical-align: top; }
        
        .title-section { font-weight: bold; font-size: 10.5pt; margin-bottom: 6px; }

        .content-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; font-size: 9pt; }
        .content-table th, .content-table td { border: 1px solid #000; padding: 5px 6px; text-align: left; vertical-align: top; }
        .content-table th { background-color: #f2f2f2; text-align: center; font-weight: bold; vertical-align: middle; }
        
        .paraf-box { font-size: 8pt; text-align: center; color: #333; font-style: italic; }
    </style>
</head>
<body>

    <!-- KOP SURAT RESMI DENGAN LOGO DARI PUBLIC/IMAGES -->
    <table class="header-table">
        <tr>
            <td width="12%" style="text-align: center;">
                <img src="{{ public_path('images/logo-ulm.png') }}" class="logo" alt="Logo ULM" onerror="this.style.display='none'">
            </td>
            <td width="88%" class="header-text">
                <h3>{{ $pengaturan->header_line_1 ?? 'KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI' }}</h3>
                <h2>{{ $pengaturan->header_line_2 ?? 'UNIVERSITAS LAMBUNG MANGKURAT' }}</h2>
                <h2>{{ $pengaturan->header_line_3 ?? 'FAKULTAS KEGURUAN DAN ILMU PENDIDIKAN' }}</h2>
                <h1>{{ $pengaturan->header_line_4 ?? 'JURUSAN PENDIDIKAN KOMPUTER' }}</h1>
                <p>{{ $pengaturan->header_line_5 ?? 'Jalan Brigjen H. Hasan Basry Banjarmasin 70123 Telepon: (0511) 3304914, Laman: pilkom.ulm.ac.id, Email: pilkom@ulm.ac.id' }}</p>
            </td>
        </tr>
    </table>

    <div class="line-double"></div>

    <!-- BIODATA MAHASISWA (1 KOLOM RAPI KEBAWAH) -->
    <table class="info-table">
        <tr>
            <td width="18%"><strong>Nama Mahasiswa</strong></td>
            <td width="2%">:</td>
            <td>{{ $mahasiswa->nama_mahasiswa }}</td>
        </tr>
        <tr>
            <td><strong>NIM</strong></td>
            <td>:</td>
            <td>{{ $mahasiswa->nim }}</td>
        </tr>
        <tr>
            <td><strong>Sekolah Mitra</strong></td>
            <td>:</td>
            <td>{{ $plotting->sekolah->nama_sekolah ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Dosen Pembimbing</strong></td>
            <td>:</td>
            <td>{{ $plotting->dosen->nama_dosen ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Guru Pamong</strong></td>
            <td>:</td>
            <td>{{ $plotting->sekolah->guruPamong->nama_guru_pamong ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Periode Asistensi Mengajar</strong></td>
            <td>:</td>
            <td>{{ $plotting->periode->nama_periode ?? '2026/2027 Ganjil' }}</td>
        </tr>
    </table>

    <div class="title-section">Riwayat Bimbingan</div>

    <!-- TABEL KONSULTASI -->
    <table class="content-table">
        <thead>
            <tr>
                <th width="4%">No.</th>
                <th width="10%">Tanggal</th>
                <th width="12%">Media/Metode Konsultasi</th>
                <th width="18%">Topik yang Dibahas</th>
                <th width="18%">Refleksi Mahasiswa</th>
                <th width="18%">Saran/Umpan Balik Dosen Pembimbing</th>
                <th width="12%">Tindak Lanjut Mahasiswa</th>
                <th width="4%">Paraf Mhs</th>
                <th width="4%">Paraf Dosen</th>
            </tr>
        </thead>
        <tbody>
            @forelse($konsultasiList as $idx => $item)
            <tr>
                <td style="text-align: center;">{{ $idx + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal_konsul)->translatedFormat('d F Y') }}</td>
                <td>{{ $item->media_konsul }}</td>
                <td>{{ $item->topik_dibahas }}</td>
                <td>{{ $item->refleksi_mahasiswa ?? '-' }}</td>
                <td>{{ $item->saran_dosen ?? '-' }}</td>
                <td>{{ $item->tindak_lanjut ?? '-' }}</td>
                <td class="paraf-box">Valid</td>
                <td class="paraf-box">Acc</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align: center; padding: 20px;">Belum ada riwayat bimbingan yang divalidasi.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>