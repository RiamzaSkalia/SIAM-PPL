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
            ? $mahasiswa->plottingBimbingan->konsultasi()->orderByDesc('tanggal_konsul')->orderByDesc('waktu_konsul')->get()
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
            'tanggal_konsul'          => 'required|date',
            'waktu_konsul'            => 'required',
            'media_konsul'            => 'required',
            'topik_dibahas'           => 'required|string',
            'refleksi_mahasiswa'      => 'required|string',
            'tindak_lanjut_mahasiswa' => 'nullable|string',
            'paraf_mahasiswa'         => 'nullable|string|max:100',
        ]);

        $mahasiswa = Auth::user()->mahasiswa()->with('plottingBimbingan')->firstOrFail();

        // Pastikan mahasiswa sudah memiliki plotting dosen
        if (!$mahasiswa->plottingBimbingan) {
            return back()->withErrors(['error' => 'Anda belum memiliki plotting bimbingan dari admin.']);
        }

        // Simpan ke database dengan status default "pending" (Menunggu Verifikasi)
        Konsultasi::create([
            'plotting_id'             => $mahasiswa->plottingBimbingan->id,
            'tanggal_konsul'          => $request->tanggal_konsul,
            'waktu_konsul'            => $request->waktu_konsul,
            'media_konsul'            => $request->media_konsul,
            'topik_dibahas'           => $request->topik_dibahas,
            'refleksi_mahasiswa'      => $request->refleksi_mahasiswa,
            'saran_dosen'             => null, // Diisi oleh dosen saat verifikasi
            'tindak_lanjut_mahasiswa' => $request->tindak_lanjut_mahasiswa,
            'paraf_mahasiswa'         => $request->paraf_mahasiswa,
            'status_validasi'         => 'pending',
        ]);

        return redirect()->route('mahasiswa.konsultasi.index')
            ->with('success', 'Log bimbingan berhasil disimpan dan menunggu verifikasi dosen.');
    }
}