<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Konsultasi;
use Illuminate\Http\Request;

class VerifikasiController extends Controller
{
    public function setujui($id)
    {
        $konsultasi = Konsultasi::findOrFail($id);

        $konsultasi->update([
            'status_validasi' => 'disetujui', 
        ]);

        return redirect()->back()->with('success', 'Konsultasi berhasil disetujui.');
    }

    public function tolak(Request $request, $id)
    {
        $konsultasi = Konsultasi::findOrFail($id);
        
        $konsultasi->update([
            'status_validasi' => 'ditolak',
            'saran_dosen' => $request->catatan ?? $konsultasi->saran_dosen,
        ]);

        return redirect()->back()->with('success', 'Konsultasi ditolak.');
    }

    public function verify($id)
    {
        // Supaya sinkron dengan rute dan fungsi setujui, arahkan ke kolom yang benar
        $konsultasi = Konsultasi::findOrFail($id);

        $konsultasi->update([
            'status_validasi' => 'disetujui', 
        ]);

        return redirect()->back()->with('success', 'Konsultasi berhasil disetujui.');
    }
}