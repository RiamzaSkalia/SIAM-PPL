<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Konsultasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KonsultasiController extends Controller
{
    // Menampilkan daftar riwayat bimbingan
    public function index()
    {
        $mahasiswa = Auth::user()->mahasiswa()->with('plottingBimbingan.konsultasi')->firstOrFail();
        
        $riwayatKonsultasi = $mahasiswa->plottingBimbingan 
            ? $mahasiswa->plottingBimbingan->konsultasi()->orderByDesc('tanggal_konsul')->get() 
            : collect();

        return view('mahasiswa.konsultasi.index', compact('riwayatKonsultasi'));
    }

    // Menampilkan halaman form tambah log bimbingan
    public function create()
    {
        return view('mahasiswa.konsultasi.create');
    }

    // Menyimpan data log bimbingan baru
    public function store(Request $request)
    {
        $request->validate([
            'tanggal_konsul' => 'required|date',
            'waktu_konsul' => 'required',
            'media_konsul' => 'required',
            'topik_dibahas' => 'required|string',
            'saran_dosen' => 'required|string',
        ]);

        $mahasiswa = Auth::user()->mahasiswa()->with('plottingBimbingan')->firstOrFail();

        // Pastikan mahasiswa sudah memiliki plotting dosen
        if (!$mahasiswa->plottingBimbingan) {
            return back()->withErrors(['error' => 'Anda belum memiliki plotting bimbingan dari admin.']);
        }

        // Simpan ke database dengan status default "menunggu" (Menunggu Verifikasi)
        Konsultasi::create([
            'plotting_bimbingan_id' => $mahasiswa->plottingBimbingan->id,
            'tanggal_konsul' => $request->tanggal_konsul,
            'waktu_konsul' => $request->waktu_konsul,
            'media_konsul' => $request->media_konsul,
            'topik_dibahas' => $request->topik_dibahas,
            'saran_dosen' => $request->saran_dosen,
            'status_validasi' => 'menunggu', 
        ]);

        return redirect()->route('mahasiswa.konsultasi.index')
            ->with('success', 'Log bimbingan berhasil disimpan dan menunggu verifikasi dosen.');
    }
}