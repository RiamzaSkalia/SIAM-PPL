<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // 1. Ambil data mahasiswa terhubung dengan akun user
        $mahasiswa = Auth::user()->mahasiswa;

        if (!$mahasiswa) {
            abort(404, 'Data Mahasiswa tidak ditemukan untuk akun ini.');
        }

        // 2. Ambil plotting bimbingan mahasiswa (beserta Dosen, Sekolah, dan Guru Pamong)
        $plotting = $mahasiswa->plottingBimbingan()
            ->with(['dosen', 'sekolah.guruPamong', 'periode'])
            ->first();

        // 3. Ambil riwayat konsultasi dari plotting
        $riwayatKonsultasi = $plotting
            ? $plotting->konsultasi()->orderByDesc('tanggal_konsul')->get()
            : collect();

        // 4. Statistik Konsultasi
        $totalKonsultasi  = $riwayatKonsultasi->count();
        $disetujuiCount   = $riwayatKonsultasi->where('status_validasi', 'disetujui')->count();
        $menungguCount    = $riwayatKonsultasi->where('status_validasi', 'menunggu')->count();
        $ditolakCount     = $riwayatKonsultasi->where('status_validasi', 'ditolak')->count();

        return view('mahasiswa.dashboard', compact(
            'mahasiswa',
            'plotting',
            'riwayatKonsultasi',
            'totalKonsultasi',
            'disetujuiCount',
            'menungguCount',
            'ditolakCount'
        ));
    }
}