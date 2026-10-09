<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PeriodeAkademik;
use App\Models\PengaturanSistem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengaturanController extends Controller
{
    public function index()
    {
        $periodeList = PeriodeAkademik::orderBy('id', 'desc')->get();
        $periodeAktif = PeriodeAkademik::where('status', 'aktif')->first();
        $pengaturan = PengaturanSistem::first() ?? new PengaturanSistem();

        return view('admin.pengaturan_index', compact('periodeList', 'periodeAktif', 'pengaturan'));
    }

    // Set Periode Mana yang Aktif
    public function setPeriodeAktif(Request $request)
    {
        $request->validate(['periode_id' => 'required|exists:periode_akademik,id']);

        PeriodeAkademik::query()->update(['status' => 'nonaktif']);
        PeriodeAkademik::where('id', $request->periode_id)->update(['status' => 'aktif']);

        return redirect()->back()->with('success', 'Periode akademik aktif berhasil diubah!');
    }

    // Tambah Periode Baru (Ganjil/Genap)
    // Tambah Periode Baru (Otomatis Set Sebagai Periode Aktif)
    public function storePeriode(Request $request)
    {
        $request->validate([
            'nama_periode'    => 'required|string|max:255',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date',
        ]);

        // 1. Ubah status semua periode sebelumnya menjadi 'nonaktif'
        PeriodeAkademik::query()->update(['status' => 'nonaktif']);

        // 2. Buat periode baru dengan status 'aktif'
        PeriodeAkademik::create([
            'nama_periode'    => $request->nama_periode,
            'tanggal_mulai'   => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status'          => 'aktif', // Otomatis Aktif
        ]);

        return redirect()->back()->with('success', 'Periode akademik baru berhasil ditambahkan dan langsung diaktifkan!');
    }

    // Simpan Konfigurasi Kop & TTD Lembar Pengesahan
    public function updatePengaturan(Request $request)
    {
        $request->validate([
            'logo'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'header_1'    => 'required|string',
            'header_2'    => 'required|string',
            'header_3'    => 'required|string',
            'header_4'    => 'required|string',
            'header_5'    => 'required|string',
            'ttd_jabatan' => 'required|string',
            'ttd_nama'    => 'required|string',
            'ttd_nip'     => 'required|string',
        ]);

        $pengaturan = PengaturanSistem::first() ?? new PengaturanSistem();

        if ($request->hasFile('logo')) {
            if ($pengaturan->logo_path && Storage::exists('public/' . $pengaturan->logo_path)) {
                Storage::delete('public/' . $pengaturan->logo_path);
            }
            $logoPath = $request->file('logo')->store('uploads/logo', 'public');
            $pengaturan->logo_path = $logoPath;
        }

        $pengaturan->header_1 = $request->header_1;
        $pengaturan->header_2 = $request->header_2;
        $pengaturan->header_3 = $request->header_3;
        $pengaturan->header_4 = $request->header_4;
        $pengaturan->header_5 = $request->header_5;
        $pengaturan->ttd_jabatan = $request->ttd_jabatan;
        $pengaturan->ttd_nama = $request->ttd_nama;
        $pengaturan->ttd_nip = $request->ttd_nip;
        $pengaturan->save();

        return redirect()->back()->with('success', 'Template Kop Surat dan Lembar Pengesahan berhasil disimpan!');
    }
    // Hapus Periode Akademik
    public function destroyPeriode($id)
    {
        $periode = PeriodeAkademik::findOrFail($id);
        $wasActive = $periode->status == 'aktif';

        $periode->delete();

        // Jika yang dihapus adalah periode aktif, otomatis aktifkan periode lain yang tersisa
        if ($wasActive) {
            $nextPeriode = PeriodeAkademik::first();
            if ($nextPeriode) {
                $nextPeriode->update(['status' => 'aktif']);
            }
        }

        return redirect()->back()->with('success', 'Periode akademik beserta seluruh data di dalamnya berhasil dihapus!');
    }
}