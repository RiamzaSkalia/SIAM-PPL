<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\PlottingBimbingan;
use App\Models\Konsultasi;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->first();

        $jumlahMahasiswa = 0;
        $totalLog = 0;
        $menungguVerifikasi = 0;
        $logMenunggu = collect();

        if ($dosen) {
            $mahasiswaIds = PlottingBimbingan::where('dosen_id', $dosen->id)->pluck('mahasiswa_id');
            $jumlahMahasiswa = $mahasiswaIds->count();

            $logBimbingan = Konsultasi::whereIn('mahasiswa_id', $mahasiswaIds)->with('mahasiswa')->get();
            $totalLog = $logBimbingan->count();
            
            $logMenunggu = $logBimbingan->where('status_validasi', 'pending');
            $menungguVerifikasi = $logMenunggu->count();
        }

        return view('dosen.dashboard', compact('dosen', 'jumlahMahasiswa', 'totalLog', 'menungguVerifikasi', 'logMenunggu'));
    }

    public function mahasiswaBimbingan()
    {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->first();

        $mahasiswas = collect();

        if ($dosen) {
            $mahasiswaIds = PlottingBimbingan::where('dosen_id', $dosen->id)->pluck('mahasiswa_id');
            $mahasiswas = Mahasiswa::whereIn('id', $mahasiswaIds)->get();

            foreach ($mahasiswas as $mhs) {
                $mhs->jumlah_bimbingan = Konsultasi::where('mahasiswa_id', $mhs->id)
                    ->where('status_validasi', 'disetujui')
                    ->count();
                $mhs->status_memenuhi = $mhs->jumlah_bimbingan >= 5;
            }
        }

        return view('dosen.mahasiswa_bimbingan', compact('dosen', 'mahasiswas'));
    }

    public function detailMahasiswa($id)
    {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->first();
        
        $mahasiswa = Mahasiswa::findOrFail($id);
        $konsultasis = Konsultasi::where('mahasiswa_id', $id)->orderBy('created_at', 'desc')->get();
        
        $jumlahBimbingan = $konsultasis->where('status_validasi', 'disetujui')->count();
        $statusMemenuhi = $jumlahBimbingan >= 5;

        return view('dosen.detail_mahasiswa', compact('dosen', 'mahasiswa', 'konsultasis', 'jumlahBimbingan', 'statusMemenuhi'));
    }
}