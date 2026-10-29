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

        // Ubah 'status' menjadi 'status_validasi' dan nilainya jadi huruf kecil 'disetujui'
        $konsultasi->update([
            'status_validasi' => 'disetujui', 
        ]);

        return redirect()->back()->with('success', 'Konsultasi berhasil disetujui.');
    }

    public function tolak(Request $request, $id)
    {
        $konsultasi = Konsultasi::findOrFail($id);
        
        // Ubah 'status' menjadi 'status_validasi' dan nilainya jadi huruf kecil 'ditolak'
        $konsultasi->update([
            'status_validasi' => 'ditolak',
            'saran_dosen' => $request->catatan ?? $konsultasi->saran_dosen, // Sesuaikan jika kolom catatan di tabel bernama saran_dosen
        ]);

        return redirect()->back()->with('success', 'Konsultasi ditolak.');
    }
}