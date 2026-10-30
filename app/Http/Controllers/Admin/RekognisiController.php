<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MatkulRekognisi;
use App\Models\PengaturanRekognisi;
use App\Models\PeriodeAkademik;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class RekognisiController extends Controller
{
    public function index()
    {
        $periodeAktif = PeriodeAkademik::where('status', 'aktif')->first();

        // Mengambil matkul beserta mahasiswa yang mengajukannya
        $matkulList = $periodeAktif 
            ? MatkulRekognisi::with('pengajuan.mahasiswa')
                ->where('periode_id', $periodeAktif->id)
                ->get() 
            : collect();

        $pengaturan = $periodeAktif 
            ? PengaturanRekognisi::firstOrCreate(['periode_id' => $periodeAktif->id])
            : null;

        return view('admin.rekognisi.index', compact('matkulList', 'periodeAktif', 'pengaturan'));
    }

    public function store(Request $request)
    {
        $periodeAktif = PeriodeAkademik::where('status', 'aktif')->first();

        if (!$periodeAktif) {
            return redirect()->back()->withErrors(['msg' => 'Tidak ada periode akademik yang aktif!']);
        }

        $request->validate([
            'kode_mk' => 'required|string',
            'nama_mk' => 'required|string',
            'sks'     => 'required|integer|min:1',
        ]);

        MatkulRekognisi::create([
            'periode_id' => $periodeAktif->id,
            'kode_mk'    => $request->kode_mk,
            'nama_mk'    => $request->nama_mk,
            'sks'        => $request->sks,
        ]);

        return redirect()->back()->with('success', 'Mata kuliah rekognisi berhasil ditambahkan!');
    }

    public function updateJadwal(Request $request)
    {
        $periodeAktif = PeriodeAkademik::where('status', 'aktif')->first();

        if (!$periodeAktif) {
            return redirect()->back()->withErrors(['msg' => 'Tidak ada periode akademik yang aktif!']);
        }

        $pengaturan = PengaturanRekognisi::firstOrCreate(['periode_id' => $periodeAktif->id]);

        $request->validate([
            'tenggat_waktu' => 'nullable|date',
        ]);

        $pengaturan->update([
            'tenggat_waktu' => $request->tenggat_waktu,
            'is_open'       => $request->has('is_open') ? 1 : 0,
        ]);

        return redirect()->back()->with('success', 'Jadwal & Status Rekognisi berhasil diperbarui!');
    }

    public function destroy($id)
    {
        MatkulRekognisi::destroy($id);
        return redirect()->back()->with('success', 'Mata kuliah rekognisi berhasil dihapus!');
    }

    // Export Rekap Matkul Rekognisi ke PDF
    public function exportPdf()
    {
        $periodeAktif = PeriodeAkademik::where('status', 'aktif')->first();

        $matkulList = $periodeAktif 
            ? MatkulRekognisi::with('pengajuan.mahasiswa')
                ->where('periode_id', $periodeAktif->id)
                ->get() 
            : collect();

        $pdf = Pdf::loadView('admin.rekognisi.pdf', compact('matkulList', 'periodeAktif'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('Rekap_Mata_Kuliah_Rekognisi_AM.pdf');
    }
}