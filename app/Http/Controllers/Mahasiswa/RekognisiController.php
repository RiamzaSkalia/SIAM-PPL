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

        // Ambil data mata kuliah rekognisi yang diinput admin pada periode aktif
        $availableMatkul = $periodeAktif 
            ? MatkulRekognisi::where('periode_id', $periodeAktif->id)->get() 
            : collect();

        $pengaturan = $periodeAktif 
            ? PengaturanRekognisi::where('periode_id', $periodeAktif->id)->first() 
            : null;

        return view('mahasiswa.rekognisi.index', compact('availableMatkul', 'pengaturan'));
    }

    public function store(Request $request)
    {
        $mahasiswa = Auth::user()->mahasiswa;

        $request->validate([
            'matkul_rekognisi_id' => 'required|exists:matkul_rekognisi,id',
            'bukti_pendukung'     => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('bukti_pendukung')) {
            $path = $request->file('bukti_pendukung')->store('bukti_rekognisi', 'public');
        }

        PengajuanRekognisi::create([
            'mahasiswa_id'        => $mahasiswa->id,
            'matkul_rekognisi_id' => $request->matkul_rekognisi_id,
            'bukti_pendukung'     => $path,
            'status'              => 'Pending',
        ]);

        return redirect()->back()->with('success', 'Mata kuliah rekognisi berhasil diajukan!');
    }
}