<?php

namespace App\Http\Controllers\Pamong;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\GuruPamong;
use App\Models\SekolahMitra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    // Method untuk Menampilkan Form Registrasi (Mendukung showRegisterForm & showRegistrationForm)
    public function showRegisterForm()
    {
        return view('auth.register_pamong');
    }

    public function showRegistrationForm()
    {
        return $this->showRegisterForm();
    }

    // Method untuk Memproses Pendaftaran (Mendukung register & store)
    public function register(Request $request)
    {
        $request->validate([
            'npsn'             => 'required|exists:sekolah_mitra,npsn',
            'nip_nik'          => 'required|string|unique:users,username',
            'nama_guru_pamong' => 'required|string|max:255',
            'no_hp'            => 'required|string|max:20',
            'password'         => 'required|string|min:4|confirmed',
        ], [
            'npsn.exists'        => 'NPSN Sekolah tidak ditemukan di sistem! Silakan hubungi Admin.',
            'nip_nik.unique'     => 'NIP/NIK ini sudah terdaftar.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // Cari Sekolah berdasarkan NPSN
        $sekolah = SekolahMitra::where('npsn', $request->npsn)->first();

        DB::transaction(function () use ($request, $sekolah) {
            // 1. Buat Akun User
            $user = User::create([
                'name'     => $request->nama_guru_pamong,
                'username' => $request->nip_nik,
                'password' => Hash::make($request->password),
                'role'     => 'gupam',
            ]);

            // 2. Buat Data Guru Pamong (Otomatis terhubung ke Sekolah Mitra)
            GuruPamong::create([
                'user_id'          => $user->id,
                'sekolah_id'       => $sekolah->id,
                'nip_nik'          => $request->nip_nik,
                'nama_guru_pamong' => $request->nama_guru_pamong,
                'no_hp'            => $request->no_hp,
            ]);
        });

        return redirect()->route('login')->with('success', 'Pendaftaran Guru Pamong berhasil! Nama Anda otomatis terhubung ke sekolah. Silakan login.');
    }

    public function store(Request $request)
    {
        return $this->register($request);
    }
}