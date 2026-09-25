<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\PeriodeAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // Halaman Input Dosen
    public function createDosen()
    {
        return view('admin.dosen_create');
    }

    // Simpan Data Dosen & Buat Akun User
    public function storeDosen(Request $request)
    {
        $request->validate([
            'nip' => 'required|unique:dosen,nip',
            'nama_dosen' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'nullable|string|min:4',
        ]);

        DB::transaction(function () use ($request) {
            $pass = $request->password ?: $request->nip; // Default password = NIP

            $user = User::create([
                'username' => $request->nip,
                'password' => Hash::make($pass),
                'role' => 'dosen',
            ]);

            Dosen::create([
                'user_id' => $user->id,
                'nip' => $request->nip,
                'nama_dosen' => $request->nama_dosen,
                'email' => $request->email,
            ]);
        });

        return redirect()->back()->with('success', 'Data Dosen berhasil disimpan!');
    }

    // Halaman Input Mahasiswa
    public function createMahasiswa()
    {
        $periode = PeriodeAkademik::where('status', 'aktif')->get();
        return view('admin.mahasiswa_create', compact('periode'));
    }

    // Simpan Data Mahasiswa & Buat Akun User
    public function storeMahasiswa(Request $request)
    {
        $request->validate([
            'nim' => 'required|unique:mahasiswa,nim',
            'nama_mahasiswa' => 'required|string|max:255',
            'periode_id' => 'required',
            'password' => 'nullable|string|min:4',
        ]);

        DB::transaction(function () use ($request) {
            $pass = $request->password ?: $request->nim; // Default password = NIM

            $user = User::create([
                'username' => $request->nim,
                'password' => Hash::make($pass),
                'role' => 'mahasiswa',
            ]);

            Mahasiswa::create([
                'user_id' => $user->id,
                'nim' => $request->nim,
                'nama_mahasiswa' => $request->nama_mahasiswa,
            ]);
        });

        return redirect()->back()->with('success', 'Data Mahasiswa berhasil disimpan!');
    }
}