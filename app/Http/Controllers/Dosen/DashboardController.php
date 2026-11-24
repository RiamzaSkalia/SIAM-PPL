<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Konsultasi;
use App\Models\Mahasiswa;
use App\Models\PlottingBimbingan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

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
        $logDisetujui = 0;
        $mahasiswaLayak = 0;
        $mahasiswaBelumLayak = 0;
        $logMenunggu = collect();

        if ($dosen) {
            $plottingIds = PlottingBimbingan::where('dosen_id', $dosen->id)->pluck('id');

            $pivotRows = DB::table('plotting_mahasiswa')
                ->whereIn('plotting_id', $plottingIds)
                ->get(['mahasiswa_id', 'plotting_id']);

            $jumlahMahasiswa = $pivotRows->pluck('mahasiswa_id')->unique()->count();

            $logBimbingan = Konsultasi::whereIn('plotting_id', $plottingIds)
                ->latest()
                ->get();

            $totalLog = $logBimbingan->count();
            $logMenunggu = $logBimbingan->where('status_validasi', 'pending');
            $menungguVerifikasi = $logMenunggu->count();
            $logDisetujui = $logBimbingan->where('status_validasi', 'disetujui')->count();

            $plottingByMahasiswa = $pivotRows
                ->groupBy('mahasiswa_id')
                ->map(fn ($items) => $items->pluck('plotting_id')->unique());

            $disetujuiByPlotting = $logBimbingan
                ->where('status_validasi', 'disetujui')
                ->groupBy('plotting_id')
                ->map->count();

            foreach ($plottingByMahasiswa as $mhsPlottingIds) {
                $sesiDisetujuiMhs = $mhsPlottingIds->sum(
                    fn ($pid) => (int) ($disetujuiByPlotting->get($pid, 0))
                );

                if ($sesiDisetujuiMhs >= 5) {
                    $mahasiswaLayak++;
                } else {
                    $mahasiswaBelumLayak++;
                }
            }
        }

        return view('dosen.dashboard', compact(
            'dosen',
            'jumlahMahasiswa',
            'totalLog',
            'menungguVerifikasi',
            'logDisetujui',
            'mahasiswaLayak',
            'mahasiswaBelumLayak',
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

        $mahasiswaList = $mahasiswas;

        return view('dosen.mahasiswa_bimbingan', compact('dosen', 'mahasiswas', 'mahasiswaList'));
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