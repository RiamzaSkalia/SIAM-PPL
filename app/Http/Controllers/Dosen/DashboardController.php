<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\PlottingBimbingan;
use App\Models\Konsultasi;

class DashboardController extends Controller
{
    private function getDosen(): ?Dosen
    {
        return Dosen::where('user_id', Auth::id())->first();
    }

    public function index(): View
    {
        $dosen = $this->getDosen();

        $jumlahMahasiswa = 0;
        $totalLog = 0;
        $menungguVerifikasi = 0;
        $logMenunggu = collect();

        if ($dosen) {
            // 1. Ambil seluruh ID plotting milik dosen ini
            $plottingIds = PlottingBimbingan::where('dosen_id', $dosen->id)->pluck('id');

            // 2. Hitung jumlah mahasiswa bimbingan dari tabel pivot 'plotting_mahasiswa'
            $jumlahMahasiswa = DB::table('plotting_mahasiswa')
                ->whereIn('plotting_id', $plottingIds)
                ->count();

            // 3. Ambil log konsultasi berdasarkan 'plotting_id'
            $logBimbingan = Konsultasi::whereIn('plotting_id', $plottingIds)
                ->latest()
                ->get();
                
            $totalLog = $logBimbingan->count();
            $logMenunggu = $logBimbingan->where('status_validasi', 'pending');
            $menungguVerifikasi = $logMenunggu->count();
        }

        return view('dosen.dashboard', compact(
            'dosen',
            'jumlahMahasiswa',
            'totalLog',
            'menungguVerifikasi',
            'logMenunggu'
        ));
    }

    public function mahasiswaBimbingan(): View
    {
        $dosen = $this->getDosen();
        $mahasiswas = collect();

        if ($dosen) {
            $plottingIds = PlottingBimbingan::where('dosen_id', $dosen->id)->pluck('id');

            $mahasiswaIds = DB::table('plotting_mahasiswa')
                ->whereIn('plotting_id', $plottingIds)
                ->pluck('mahasiswa_id');

            $mahasiswas = Mahasiswa::whereIn('id', $mahasiswaIds)->get();

            foreach ($mahasiswas as $mhs) {
                $mhsPlottingIds = DB::table('plotting_mahasiswa')
                    ->where('mahasiswa_id', $mhs->id)
                    ->pluck('plotting_id');

                $mhs->jumlah_bimbingan = Konsultasi::whereIn('plotting_id', $mhsPlottingIds)
                    ->where('status_validasi', 'disetujui')
                    ->count();
                $mhs->status_memenuhi = $mhs->jumlah_bimbingan >= 5;
            }
        }

        return view('dosen.mahasiswa_bimbingan', compact('dosen', 'mahasiswas'));
    }

    public function detailMahasiswa($id): View
    {
        $dosen = $this->getDosen();
        $mahasiswa = Mahasiswa::findOrFail($id);

        $mhsPlottingIds = DB::table('plotting_mahasiswa')
            ->where('mahasiswa_id', $id)
            ->pluck('plotting_id');
        
        $konsultasis = Konsultasi::whereIn('plotting_id', $mhsPlottingIds)
            ->latest()
            ->get();
        
        $jumlahBimbingan = $konsultasis->where('status_validasi', 'disetujui')->count();
        $statusMemenuhi = $jumlahBimbingan >= 5;

        return view('dosen.detail_mahasiswa', compact(
            'dosen',
            'mahasiswa',
            'konsultasis',
            'jumlahBimbingan',
            'statusMemenuhi'
        ));
    }
}