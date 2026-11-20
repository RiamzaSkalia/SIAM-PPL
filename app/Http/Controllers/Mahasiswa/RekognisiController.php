<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\MatkulRekognisi;
use App\Models\PengajuanRekognisi;
use App\Models\PengaturanRekognisi;
use App\Models\PeriodeAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RekognisiController extends Controller
{
    /**
     * 1. Halaman Daftar Mata Kuliah Rekognisi yang sudah diajukan mahasiswa
     */
    public function index()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $periodeAktif = PeriodeAkademik::where('status', 'aktif')->first();

        // Ambil daftar pengajuan rekognisi yang sudah dilakukan oleh mahasiswa ini
        $pengajuanList = PengajuanRekognisi::with('matkulRekognisi')
            ->where('mahasiswa_id', $mahasiswa->id)
            ->get();

        // Hitung total SKS dari mata kuliah yang diajukan
        $totalSks = $pengajuanList->sum(function ($item) {
            // Sesuaikan nama kolom SKS di model MatkulRekognisi Anda (misal: 'sks')
            return $item->matkulRekognisi->sks ?? 0; 
        });

        return view('mahasiswa.rekognisi.index', compact('pengajuanList', 'totalSks', 'periodeAktif'));
    }

    /**
     * 2. Halaman Form Input / Tambah Pengajuan Rekognisi (Sesuai Screenshot Anda)
     */
    public function create()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $periodeAktif = PeriodeAkademik::where('status', 'aktif')->first();

        // Ambil data mata kuliah rekognisi yang diinput admin pada periode aktif
        $availableMatkul = $periodeAktif 
            ? MatkulRekognisi::where('periode_id', $periodeAktif->id)->get() 
            : collect();

        $pengaturan = $periodeAktif 
            ? PengaturanRekognisi::where('periode_id', $periodeAktif->id)->first() 
            : null;

        return view('mahasiswa.rekognisi.create', compact('availableMatkul', 'pengaturan'));
    }

    /**
     * 3. Menyimpan Data Pengajuan Baru ke Database
     */
    public function store(Request $request)
    {
        // 1. Pastikan data mahasiswa dari user yang login benar-benar ada
        $user = Auth::user();
        if (!$user || !$user->mahasiswa) {
            return redirect()->back()->with('error', 'Data profil mahasiswa tidak ditemukan untuk akun ini.');
        }
        $mahasiswa = $user->mahasiswa;

        // 2. Validasi input dengan penanganan jika gagal
        try {
            $request->validate([
                'matkul_rekognisi_id' => 'required|exists:matkul_rekognisi,id',
                'bukti_pendukung'     => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Jika validasi gagal, kembalikan dengan pesan error validasinya
            return redirect()->back()->withErrors($e->validator)->withInput();
        }

        $path = null;
        if ($request->hasFile('bukti_pendukung')) {
            $file = $request->file('bukti_pendukung');
            
            // Mengambil nama asli file
            $originalName = $file->getClientOriginalName();
            
            // Beri angka unik di depan agar tidak bentrok
            $filename = time() . '_' . $originalName;
            
            // Simpan file ke folder 'public/bukti_rekognisi'
            $path = $file->storeAs('bukti_rekognisi', $filename, 'public');
        }

        // 3. Simpan data ke database
        PengajuanRekognisi::create([
            'mahasiswa_id'        => $mahasiswa->id,
            'matkul_rekognisi_id' => $request->matkul_rekognisi_id,
            'bukti_pendukung'     => $path,
            'status'              => 'Pending',
        ]);

        return redirect()->route('mahasiswa.rekognisi.index')
            ->with('success', 'Mata kuliah rekognisi beserta bukti pendukung berhasil diajukan!');
    }
    /**
     * 4. Membatalkan / Menghapus Pengajuan Rekognisi
     */
    public function destroy($id)
    {
        $mahasiswa = Auth::user()->mahasiswa;

        $pengajuan = PengajuanRekognisi::where('mahasiswa_id', $mahasiswa->id)->findOrFail($id);

        // Hapus file fisik bukti pendukung jika ada
        if ($pengajuan->bukti_pendukung && Storage::disk('public')->exists($pengajuan->bukti_pendukung)) {
            Storage::disk('public')->delete($pengajuan->bukti_pendukung);
        }

        $pengajuan->delete();

        return redirect()->route('mahasiswa.rekognisi.index')
            ->with('success', 'Pengajuan mata kuliah rekognisi berhasil dibatalkan.');
    }
}