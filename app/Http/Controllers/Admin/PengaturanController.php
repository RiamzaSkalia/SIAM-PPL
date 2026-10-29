<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PeriodeAkademik;
use App\Models\PengaturanSistem;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    public function index()
    {
        $periodeList = PeriodeAkademik::orderBy('id', 'desc')->get();
        $periodeAktif = PeriodeAkademik::where('status', 'aktif')->first();
        $pengaturan = PengaturanSistem::first() ?? new PengaturanSistem();

        return view('admin.pengaturan_index', compact('periodeList', 'periodeAktif', 'pengaturan'));
    }

    public function setPeriodeAktif(Request $request)
    {
        $request->validate(['periode_id' => 'required|exists:periode_akademik,id']);

        PeriodeAkademik::query()->update(['status' => 'nonaktif']);
        PeriodeAkademik::where('id', $request->periode_id)->update(['status' => 'aktif']);

        return redirect()->back()->with('success', 'Periode akademik aktif berhasil diubah!');
    }

    public function storePeriode(Request $request)
    {
        $request->validate([
            'nama_periode'    => 'required|string|max:255',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date',
        ]);

        PeriodeAkademik::query()->update(['status' => 'nonaktif']);

        PeriodeAkademik::create([
            'nama_periode'    => $request->nama_periode,
            'tanggal_mulai'   => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status'          => 'aktif',
        ]);

        return redirect()->back()->with('success', 'Periode akademik baru berhasil ditambahkan dan langsung diaktifkan!');
    }

    public function updatePengaturan(Request $request)
    {
        $request->validate([
            'header_1' => 'required|string',
            'header_2' => 'required|string',
            'header_3' => 'required|string',
            'header_4' => 'required|string',
            'header_5' => 'required|string',
        ]);

        $pengaturan = PengaturanSistem::first() ?? new PengaturanSistem();

        $pengaturan->header_1 = $request->header_1;
        $pengaturan->header_2 = $request->header_2;
        $pengaturan->header_3 = $request->header_3;
        $pengaturan->header_4 = $request->header_4;
        $pengaturan->header_5 = $request->header_5;
        $pengaturan->save();

        return redirect()->back()->with('success', 'Template Kop Surat berhasil disimpan!');
    }

    public function destroyPeriode($id)
    {
        $periode = PeriodeAkademik::findOrFail($id);
        $wasActive = $periode->status == 'aktif';

        $periode->delete();

        if ($wasActive) {
            $nextPeriode = PeriodeAkademik::first();
            if ($nextPeriode) {
                $nextPeriode->update(['status' => 'aktif']);
            }
        }

        return redirect()->back()->with('success', 'Periode akademik berhasil dihapus!');
    }
}