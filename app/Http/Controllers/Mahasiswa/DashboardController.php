<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Konsultasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;

        // Ambil plotting bimbingan mahasiswa aktif
        $plotting = $mahasiswa ? $mahasiswa->plottingBimbingan()
            ->with(['dosen', 'sekolah.guruPamong', 'periode'])
            ->first() : null;

        // Ambil riwayat bimbingan jika ada plotting
        $konsultasiQuery = $plotting ? $plotting->konsultasi() : Konsultasi::query()->whereRaw('1 = 0');

        // Perhitungan Sesi & Progress
        $totalSesiValid = (clone $konsultasiQuery)->where('status_validasi', 'disetujui')->count();
        $totalMenunggu  = (clone $konsultasiQuery)->where('status_validasi', 'menunggu')->count();
        $totalDitolak   = (clone $konsultasiQuery)->where('status_validasi', 'ditolak')->count();

        // Persentase progress bimbingan (Target minimum 5 sesi)
        $persenProgress = min(100, round(($totalSesiValid / 5) * 100));
        $isEligible = $totalSesiValid >= 5;

        // Riwayat Konsultasi Terakhir (Max 5 Data)
        $konsultasiTerakhir = (clone $konsultasiQuery)->orderBy('tanggal_konsul', 'desc')->take(5)->get();

        return view('mahasiswa.dashboard', compact(
            'mahasiswa', 'plotting', 'totalSesiValid', 'totalMenunggu', 'totalDitolak',
            'persenProgress', 'isEligible', 'konsultasiTerakhir'
        ));
    }
    public function halamanKartu()
    {
        $mahasiswa = Auth::user()->mahasiswa;

        if (!$mahasiswa) {
            return redirect()->back()->withErrors(['msg' => 'Data Mahasiswa tidak ditemukan.']);
        }

        $plotting = $mahasiswa->plottingBimbingan()
            ->with(['dosen', 'sekolah.guruPamong', 'periode'])
            ->first();

        // Mengambil Bimbingan yang Divalidasi / Disetujui
        $riwayatKonsultasi = $plotting
            ? $plotting->konsultasi()->where('status_validasi', 'disetujui')->orderBy('tanggal_konsul', 'asc')->get()
            : collect();

        $totalDisetujui = $riwayatKonsultasi->count();
        $isEligible = $totalDisetujui >= 5; // Syarat minimal 5x bimbingan terverifikasi

        return view('mahasiswa.kartu_konsultasi_index', compact(
            'mahasiswa', 'plotting', 'riwayatKonsultasi', 'totalDisetujui', 'isEligible'
        ));
    }

    // Export PDF Kartu Konsultasi
    public function cetakKartu()
    {
        $mahasiswa = Auth::user()->mahasiswa;

        if (!$mahasiswa) {
            return redirect()->back()->withErrors(['msg' => 'Data Mahasiswa tidak ditemukan.']);
        }

        $plotting = $mahasiswa->plottingBimbingan()
            ->with(['dosen', 'sekolah.guruPamong', 'periode'])
            ->first();

        $konsultasiList = $plotting
            ? $plotting->konsultasi()->where('status_validasi', 'disetujui')->orderBy('tanggal_konsul', 'asc')->get()
            : collect();

        if ($konsultasiList->count() < 5) {
            return redirect()->route('mahasiswa.kartu')
                ->with('error', 'Gagal mencetak! Anda harus memiliki minimal 5 bimbingan yang telah disetujui dosen.');
        }

        // Mengambil data Pengaturan Sistem terbaru dari database
        $pengaturan = \App\Models\PengaturanSistem::first() ?? new \App\Models\PengaturanSistem();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('mahasiswa.kartu_konsultasi_pdf', compact(
            'mahasiswa', 'plotting', 'konsultasiList', 'pengaturan'
        ))->setPaper('a4', 'landscape');

        return $pdf->stream('Kartu_Konsultasi_' . $mahasiswa->nim . '.pdf');
    }
}