<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $mahasiswa = Auth::user()
            ->mahasiswa()
            ->with(['plottingBimbingan.dosen', 'plottingBimbingan.guruPamong', 'plottingBimbingan.sekolahMitra'])
            ->firstOrFail();

        $plotting = $mahasiswa->plottingBimbingan;

        // Jika mahasiswa belum di-plotting ke dosen/gupam/sekolah oleh admin,
        // belum ada konsultasi sama sekali.
        $riwayatKonsultasi = $plotting
            ? $plotting->konsultasi()->orderByDesc('tanggal_konsul')->get()
            : collect();

        // --- Logika perhitungan dinamis ---
        $totalKonsultasi  = $riwayatKonsultasi->count();
        $totalDisetujui   = $riwayatKonsultasi->where('status_validasi', 'disetujui')->count();
        $totalPending     = $riwayatKonsultasi->where('status_validasi', 'pending')->count();
        $totalDitolak     = $riwayatKonsultasi->where('status_validasi', 'ditolak')->count();

        // Syarat minimal dihitung dari yang statusnya SUDAH DISETUJUI saja
        $syaratTerpenuhi  = $totalDisetujui >= Mahasiswa::MINIMAL_KONSULTASI;
        $sisaMenujuSyarat = max(Mahasiswa::MINIMAL_KONSULTASI - $totalDisetujui, 0);

        return view('mahasiswa.dashboard', [
            'mahasiswa'          => $mahasiswa,
            'plotting'           => $plotting,
            'riwayatKonsultasi'  => $riwayatKonsultasi,
            'totalKonsultasi'    => $totalKonsultasi,
            'totalDisetujui'     => $totalDisetujui,
            'totalPending'       => $totalPending,
            'totalDitolak'       => $totalDitolak,
            'minimalKonsultasi'  => Mahasiswa::MINIMAL_KONSULTASI,
            'syaratTerpenuhi'    => $syaratTerpenuhi,
            'sisaMenujuSyarat'   => $sisaMenujuSyarat,
        ]);
    }
}
