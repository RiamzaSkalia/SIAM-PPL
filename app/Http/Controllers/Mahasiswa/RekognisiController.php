<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\MatkulRekognisi;
use App\Models\PengajuanRekognisi;
use App\Models\PengaturanRekognisi;
use App\Models\PeriodeAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RekognisiController extends Controller
{
    public function index()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $periodeAktif = PeriodeAkademik::where('status', 'aktif')->first();

        $availableMatkul = $periodeAktif 
            ? MatkulRekognisi::where('periode_id', $periodeAktif->id)->get() 
            : collect();

        $pengaturan = $periodeAktif 
            ? PengaturanRekognisi::where('periode_id', $periodeAktif->id)->first() 
            : null;

        // Pengecekan Ketersediaan Pengajuan
        $isClosedManual = $pengaturan ? !$pengaturan->is_open : false;
        $isExpired = ($pengaturan && $pengaturan->tenggat_waktu) ? now()->greaterThan($pengaturan->tenggat_waktu) : false;
        $isLocked = $isClosedManual || $isExpired;

        $pengajuanSaya = $mahasiswa 
            ? PengajuanRekognisi::where('mahasiswa_id', $mahasiswa->id)->pluck('matkul_rekognisi_id')->toArray() 
            : [];

        return view('mahasiswa.rekognisi.index', compact('availableMatkul', 'pengajuanSaya', 'pengaturan', 'isLocked', 'isExpired', 'isClosedManual'));
    }

    public function store(Request $request)
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $periodeAktif = PeriodeAkademik::where('status', 'aktif')->first();
        $pengaturan = $periodeAktif ? PengaturanRekognisi::where('periode_id', $periodeAktif->id)->first() : null;

        // Security Validation Server-Side
        if ($pengaturan && (!$pengaturan->is_open || ($pengaturan->tenggat_waktu && now()->greaterThan($pengaturan->tenggat_waktu)))) {
            return redirect()->back()->with('error', 'Gagal mengajukan! Jadwal pengajuan rekognisi telah ditutup oleh Admin.');
        }

        $request->validate([
            'matkul_ids' => 'required|array|min:1',
        ]);

        foreach ($request->matkul_ids as $matkulId) {
            PengajuanRekognisi::firstOrCreate([
                'mahasiswa_id'        => $mahasiswa->id,
                'matkul_rekognisi_id' => $matkulId,
            ]);
        }

        return redirect()->back()->with('success', 'Mata kuliah rekognisi berhasil diajukan!');
    }
}