<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Konsultasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KonsultasiController extends Controller
{
    public function index()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        
        $plotting = $mahasiswa ? $mahasiswa->plottingBimbingan->first() : null;

        $konsultasi = $plotting
            ? $plotting->konsultasi()->orderBy('tanggal_konsul', 'desc')->get()
            : collect();

        return view('mahasiswa.konsultasi.index', compact('konsultasi'));
    }

    public function create()
    {
        return view('mahasiswa.konsultasi.create');
    }

    public function store(Request $request)
    {
        $mahasiswa = Auth::user()->mahasiswa;
        
        if (!$mahasiswa) {
            return redirect()->back()->withErrors(['msg' => 'Data mahasiswa tidak ditemukan.']);
        }

        $plotting = $mahasiswa->plottingBimbingan->first();

        if (!$plotting) {
            return redirect()->back()->withErrors(['msg' => 'Anda belum di-plotting ke sekolah & dosen pembimbing oleh Admin.']);
        }

        $request->validate([
            'tanggal_konsul'    => 'required|date',
            'waktu_konsul'      => 'required',
            'media_konsul'      => 'required|string',
            'topik_dibahas'     => 'required|string',
            'refleksi_mahasiswa'=> 'required|string',
            'tindak_lanjut'     => 'nullable|string',
            'saran_dosen'       => 'nullable|string',
        ]);

        Konsultasi::create([
            'plotting_id'       => $plotting->id,
            'tanggal_konsul'    => $request->tanggal_konsul,
            'waktu_konsul'      => $request->waktu_konsul,
            'media_konsul'      => $request->media_konsul,
            'topik_dibahas'     => $request->topik_dibahas,
            'refleksi_mahasiswa'=> $request->refleksi_mahasiswa,
            'tindak_lanjut'     => $request->tindak_lanjut,
            'saran_dosen'       => $request->saran_dosen,
            'status_validasi'   => 'menunggu',
        ]);

        return redirect()->route('mahasiswa.konsultasi.index')->with('success', 'Log bimbingan berhasil ditambahkan dan menunggu verifikasi Dosen!');
    }
}