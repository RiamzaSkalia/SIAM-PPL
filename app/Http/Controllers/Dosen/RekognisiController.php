<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\PengajuanRekognisi;
use Illuminate\Support\Facades\Auth;

class RekognisiController extends Controller
{
    public function index()
    {
        $dosen = Auth::user()->dosen;

        // Mengambil mahasiswas yang dibimbing oleh DPL ini
        $plottingIds = $dosen ? $dosen->plottingBimbingan->pluck('id') : [];

        $pengajuanList = PengajuanRekognisi::with(['mahasiswa', 'matkulRekognisi'])
            ->whereHas('mahasiswa.plottingBimbingan', function ($q) use ($plottingIds) {
                $q->whereIn('plotting_bimbingan.id', $plottingIds);
            })->get();

        return view('dosen.rekognisi.index', compact('pengajuanList'));
    }
}