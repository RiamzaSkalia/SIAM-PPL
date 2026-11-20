<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PengajuanRekognisi;
use App\Models\PlottingBimbingan;
use App\Models\Mahasiswa;

class RekognisiController extends Controller
{
    // 1. Menampilkan daftar mahasiswa bimbingan yang mengajukan rekognisi (dikelompokkan per mahasiswa)
    // 1. Menampilkan daftar mahasiswa bimbingan yang mengajukan rekognisi (dikelompokkan per mahasiswa)
    public function index()
    {
        $dosen = Auth::user()->dosen;

        if (!$dosen) {
            $mahasiswaList = collect();
            return view('dosen.rekognisi.index', compact('mahasiswaList'));
        }

        // Ambil ID plotting bimbingan dosen
        $plottingIds = PlottingBimbingan::where('dosen_id', $dosen->id)->pluck('id');

        // Ambil daftar mahasiswa bimbingan yang memiliki pengajuan rekognisi
        $mahasiswaList = Mahasiswa::whereHas('plottingBimbingan', function ($q) use ($plottingIds) {
                $q->whereIn('plotting_bimbingan.id', $plottingIds);
            })
            ->whereHas('pengajuanRekognisi')
            ->with(['pengajuanRekognisi.matkulRekognisi'])
            ->get();

        return view('dosen.rekognisi.index', compact('mahasiswaList'));
    }

    // 2. Menampilkan detail mata kuliah dan form pengaturan batas SKS untuk 1 mahasiswa tertentu
    // 2. Menampilkan detail mata kuliah dan form pengaturan batas SKS untuk 1 mahasiswa tertentu
    public function show($mahasiswa_id)
    {
        $mahasiswa = Mahasiswa::with(['pengajuanRekognisi.matkulRekognisi'])->findOrFail($mahasiswa_id);
        
        // Hitung total SKS yang sudah disetujui dengan mengambil bobot SKS dari relasi matkulRekognisi atau kolom sks
        $totalSksDisetujui = $mahasiswa->pengajuanRekognisi()
            ->where('status', 'Disetujui')
            ->get()
            ->sum(function ($item) {
                // Ambil SKS dari master data mata kuliah jika ada, fallback ke kolom pengajuan
                return $item->matkulRekognisi->sks ?? $item->sks;
            });

        return view('dosen.rekognisi.show', compact('mahasiswa', 'totalSksDisetujui'));
    }

    // 3. Menyimpan/Memperbarui batas maksimal total akumulasi SKS untuk mahasiswa tersebut
    public function updateBatasSks(Request $request, $mahasiswa_id)
    {
        $request->validate([
            'maksimal_sks' => 'required|integer|min:1|max:24',
        ]);

        $mahasiswa = Mahasiswa::findOrFail($mahasiswa_id);
        
        // Menyimpan batas maksimal SKS ke tabel mahasiswa 
        // (Pastikan tabel 'mahasiswas' memiliki kolom 'max_sks_rekognisi')
        $mahasiswa->update([
            'max_sks_rekognisi' => $request->maksimal_sks,
        ]);

        return redirect()->back()->with('success', 'Batas maksimal total SKS rekognisi untuk mahasiswa ini berhasil diperbarui.');
    }

    // 4. Memverifikasi / Menyetujui mata kuliah rekognisi mahasiswa berdasarkan batas SKS
    public function update(Request $request, $id)
    {
        $request->validate([
            'sks' => 'required|integer|min:1|max:6',
        ]);

        $pengajuan = PengajuanRekognisi::findOrFail($id);
        $mahasiswaId = $pengajuan->mahasiswa_id;

        // Ambil batas maksimal SKS mahasiswa (default 20 jika belum diatur)
        $mahasiswa = Mahasiswa::findOrFail($mahasiswaId);
        $maksimalSks = $mahasiswa->max_sks_rekognisi ?? 20; 

        // Hitung total SKS yang sudah disetujui sebelumnya (kecuali data yang sedang diedit)
        $totalSksDisetujui = PengajuanRekognisi::where('mahasiswa_id', $mahasiswaId)
            ->where('status', 'Disetujui')
            ->where('id', '!=', $id)
            ->sum('sks');

        // Cek apakah jika ditambah SKS baru ini melebihi batas maksimal
        $totalAkhir = $totalSksDisetujui + $request->sks;

        if ($totalAkhir > $maksimalSks) {
            return redirect()->back()->with('error', "Batas maksimal SKS rekognisi untuk mahasiswa ini adalah {$maksimalSks} SKS. Saat ini sudah disetujui {$totalSksDisetujui} SKS.");
        }

        // Jika aman, simpan SKS dan setujui
        $pengajuan->update([
            'sks'    => $request->sks,
            'status' => 'Disetujui',
        ]);

        return redirect()->back()->with('success', 'Jumlah SKS berhasil ditentukan dan pengajuan disetujui.');
    }
}