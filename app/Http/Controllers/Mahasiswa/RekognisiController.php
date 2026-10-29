<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\MatkulRekognisi;
use App\Models\PengajuanRekognisi;
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

        $pengajuanSaya = $mahasiswa 
            ? PengajuanRekognisi::where('mahasiswa_id', $mahasiswa->id)->pluck('matkul_rekognisi_id')->toArray() 
            : [];

        return view('mahasiswa.rekognisi.index', compact('availableMatkul', 'pengajuanSaya'));
    }

    public function store(Request $request)
    {
        $mahasiswa = Auth::user()->mahasiswa;

        $request->validate([
            'matkul_ids' => 'required|array',
        ]);

        // Cek apakah batas waktu rekognisi masih berlaku
        foreach ($request->matkul_ids as $matkulId) {
            $matkul = MatkulRekognisi::findOrFail($matkulId);
            
            if (now()->greaterThan($matkul->batas_waktu)) {
                return redirect()->back()->withErrors(['msg' => "Waktu pendaftaran rekognisi untuk {$matkul->nama_mk} telah ditutup."]);
            }

            PengajuanRekognisi::firstOrCreate([
                'mahasiswa_id'        => $mahasiswa->id,
                'matkul_rekognisi_id' => $matkulId,
            ]);
        }

        return redirect()->back()->with('success', 'Mata kuliah rekognisi berhasil dipilih!');
    }
}