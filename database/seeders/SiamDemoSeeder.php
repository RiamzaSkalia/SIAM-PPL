<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\GuruPamong;
use App\Models\Konsultasi;
use App\Models\Mahasiswa;
use App\Models\PeriodeAkademik;
use App\Models\PlottingBimbingan;
use App\Models\SekolahMitra;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SiamDemoSeeder extends Seeder
{
    public function run(): void
    {
        // 1. User + Mahasiswa dibuat PALING AWAL supaya dapat id 1
        //    (dipakai oleh route /preview-dashboard: Auth::loginUsingId(1))
        $userMahasiswa = User::create([
            'name'     => 'Budi Mahasiswa',
            'username' => 'budimhs',
            'email'    => 'mahasiswa@siam.test',
            'password' => Hash::make('password'),
            'role'     => 'mahasiswa',
        ]);

        $userDosen = User::create([
            'name'     => 'Dr. Siti Rahma, M.Kom.',
            'username' => 'sitidosen',
            'email'    => 'dosen@siam.test',
            'password' => Hash::make('password'),
            'role'     => 'dosen',
        ]);

        $userGupam = User::create([
            'name'     => 'Andi Guru Pamong, S.Pd.',
            'username' => 'andigupam',
            'email'    => 'gupam@siam.test',
            'password' => Hash::make('password'),
            'role'     => 'gupam', // sesuai enum di migration: admin, dosen, mahasiswa, gupam
        ]);

        // 2. Data pendukung
        $sekolah = SekolahMitra::create([
            'nama_sekolah' => 'SMA Negeri 1 Banjarmasin',
            'alamat'       => 'Jl. Pangeran Antasari No. 1, Banjarmasin',
        ]);

        $periode = PeriodeAkademik::create([
            'nama_periode'     => 'Ganjil 2026/2027',
            'tanggal_mulai'    => now()->subMonths(2),
            'tanggal_selesai'  => now()->addMonths(4),
            'status'           => 'aktif',
        ]);

        $dosen = Dosen::create([
            'user_id'    => $userDosen->id,
            'nip'        => '198501012010011001',
            'nama_dosen' => $userDosen->name,
            'email'      => $userDosen->email,
        ]);

        $gupam = GuruPamong::create([
            'user_id'          => $userGupam->id,
            'nip_nik'          => '3271000000000001',
            'nama_guru_pamong' => $userGupam->name,
            'no_hp'            => '081234567890',
        ]);

        $mahasiswa = Mahasiswa::create([
            'user_id'        => $userMahasiswa->id,
            'nim'            => '2201001',
            'nama_mahasiswa' => $userMahasiswa->name,
            'prodi'          => 'Pendidikan Teknik Informatika dan Komputer',
            'semester'       => '7',
        ]);

        // 3. Pasangkan mahasiswa dengan dosen, guru pamong, dan sekolah
        $plotting = PlottingBimbingan::create([
            'periode_id'   => $periode->id,
            'dosen_id'     => $dosen->id,
            'mahasiswa_id' => $mahasiswa->id,
            'gupam_id'     => $gupam->id,
            'sekolah_id'   => $sekolah->id,
        ]);

        // 4. Riwayat konsultasi contoh: 3 disetujui, 1 pending, 1 ditolak
        $contohKonsultasi = [
            [30, 'disetujui', 'Diskusi RPP pertemuan pertama'],
            [24, 'disetujui', 'Evaluasi praktik mengajar minggu 1'],
            [17, 'disetujui', 'Revisi media pembelajaran'],
            [10, 'pending',   'Konsultasi kendala kelas'],
            [3,  'ditolak',   'Draft laporan akhir (perlu revisi)'],
        ];

        foreach ($contohKonsultasi as [$hariKe, $status, $topik]) {
            Konsultasi::create([
                'plotting_id'        => $plotting->id,
                'tanggal_konsul'     => now()->subDays($hariKe),
                'media_konsul'       => 'Tatap Muka',
                'topik_dibahas'      => $topik,
                'refleksi_mahasiswa' => 'Refleksi contoh untuk data dummy.',
                'saran_dosen'        => $status === 'disetujui' ? 'Lanjutkan, sudah baik.' : null,
                'paraf_dosen'        => $status === 'disetujui' ? $dosen->nama_dosen : null,
                'status_validasi'    => $status,
            ]);
        }

        $this->command->info('Seeder SIAM selesai. Login mahasiswa: mahasiswa@siam.test / password (user id: ' . $userMahasiswa->id . ')');
    }
}
