<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DokumenController extends Controller
{
    public function index()
    {
        return view('mahasiswa.dokumen.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_dokumen' => 'required|string',
            'file'          => 'required|file|mimes:pdf,doc,docx|max:5120', // Maksimal 5MB
            'catatan'       => 'nullable|string',
        ]);

        $mahasiswa = Auth::user()->mahasiswa;

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('dokumen_mahasiswa', 'public');
            
            // Catatan: Jika tabel database untuk menyimpan dokumen sudah dibuat, 
            // kamu bisa menyimpannya ke model di sini (misal: DokumenMahasiswa::create(...))
        }

        return redirect()->back()->with('success', 'Dokumen pendukung berhasil diupload!');
    }
}