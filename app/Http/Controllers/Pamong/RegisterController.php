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
    public function showRegisterForm()
    {
        return view('auth.register_pamong');
    }

    public function register(Request $request)
    {
        $request->validate([
            'npsn'              => 'required|exists:sekolah_mitra,npsn',
            'nip_nik'           => 'required|unique:guru_pamong,nip_nik|unique:users,username',
            'nama_guru_pamong'  => 'required|string|max:255',
            'no_hp'             => 'required|string|max:20',
            'password'          => 'required|string|min:4|confirmed',
        ], [
            'npsn.exists'       => 'NPSN Sekolah Mitra tidak terdaftar di sistem! Hubungi Koordinator.',
            'nip_nik.unique'    => 'NIP/NIK ini sudah terdaftar sebagai akun.',
            'password.confirmed'=> 'Konfirmasi password tidak cocok.',
        ]);

        $sekolah = SekolahMitra::where('npsn', $request->npsn)->first();

        DB::transaction(function () use ($request, $sekolah) {
            $user = User::create([
                'name'     => $request->nama_guru_pamong,
                'username' => $request->nip_nik,
                'password' => Hash::make($request->password),
                'role'     => 'gupam',
            ]);

            GuruPamong::create([
                'user_id'          => $user->id,
                'sekolah_id'       => $sekolah->id,
                'nip_nik'          => $request->nip_nik,
                'nama_guru_pamong' => $request->nama_guru_pamong,
                'no_hp'            => $request->no_hp,
            ]);
        });

        return redirect()->route('login')->with('success', 'Pendaftaran Guru Pamong berhasil! Silakan login.');
    }
}