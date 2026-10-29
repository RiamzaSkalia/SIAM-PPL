<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MatkulRekognisi;
use App\Models\PeriodeAkademik;
use Illuminate\Http\Request;

class RekognisiController extends Controller
{
    public function index()
    {
        $periodeAktif = PeriodeAkademik::where('status', 'aktif')->first();
        
        $matkulList = $periodeAktif 
            ? MatkulRekognisi::where('periode_id', $periodeAktif->id)->get() 
            : collect();

        return view('admin.rekognisi.index', compact('matkulList', 'periodeAktif'));
    }

    public function store(Request $request)
    {
        $periodeAktif = PeriodeAkademik::where('status', 'aktif')->first();

        if (!$periodeAktif) {
            return redirect()->back()->withErrors(['msg' => 'Tidak ada periode akademik yang aktif saat ini!']);
        }

        $request->validate([
            'kode_mk'     => 'required|string',
            'nama_mk'     => 'required|string',
            'sks'         => 'required|integer|min:1',
            'batas_waktu' => 'required|date',
        ]);

        MatkulRekognisi::create([
            'periode_id'  => $periodeAktif->id,
            'kode_mk'     => $request->kode_mk,
            'nama_mk'     => $request->nama_mk,
            'sks'         => $request->sks,
            'batas_waktu' => $request->batas_waktu,
        ]);

        return redirect()->back()->with('success', 'Mata kuliah rekognisi berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        MatkulRekognisi::destroy($id);
        return redirect()->back()->with('success', 'Mata kuliah rekognisi berhasil dihapus!');
    }
}