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
    // ------------------- DOSEN -------------------
    
    // 1. Halaman Tabel Data Dosen
    public function indexDosen(Request $request)
    {
        $search = $request->query('search');
        $dosen = Dosen::when($search, function ($query, $search) {
            return $query->where('nama_dosen', 'like', "%{$search}%")
                         ->orWhere('nip', 'like', "%{$search}%");
        })->latest()->get();

        return view('admin.dosen_index', compact('dosen'));
    }

    // 2. Halaman Form Tambah Dosen
    public function createDosen()
    {
        return view('admin.dosen_create');
    }

    // 3. Simpan Data Dosen (PASSWORD WAJIB)
    public function storeDosen(Request $request)
    {
        $request->validate([
            'nip'        => 'required|unique:dosen,nip|unique:users,username',
            'nama_dosen' => 'required|string|max:255',
            'email'      => 'required|email',
            'password'   => 'required|string|min:4', // WAJIB DIISI
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name'     => $request->nama_dosen,
                'username' => $request->nip,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'dosen',
            ]);

            Dosen::create([
                'user_id'    => $user->id,
                'nip'        => $request->nip,
                'nama_dosen' => $request->nama_dosen,
                'email'      => $request->email,
            ]);
        });

        return redirect()->route('admin.dosen.index')->with('success', 'Data Dosen berhasil disimpan!');
    }

    // --- EDIT & UPDATE DOSEN ---
    public function editDosen($id)
    {
        $dosen = Dosen::findOrFail($id);
        return view('admin.dosen_create', compact('dosen'));
    }

    public function updateDosen(Request $request, $id)
    {
        $dosen = Dosen::findOrFail($id);
        $request->validate([
            'nip'        => 'required|unique:dosen,nip,' . $dosen->id,
            'nama_dosen' => 'required|string|max:255',
            'email'      => 'required|email',
            'password'   => 'nullable|string|min:4', // Opsional saat edit
        ]);

        DB::transaction(function () use ($request, $dosen) {
            $dosen->update([
                'nip'        => $request->nip,
                'nama_dosen' => $request->nama_dosen,
                'email'      => $request->email,
            ]);

            if ($dosen->user) {
                $userData = [
                    'name'     => $request->nama_dosen,
                    'username' => $request->nip,
                    'email'    => $request->email,
                ];

                if ($request->filled('password')) {
                    $userData['password'] = Hash::make($request->password);
                }

                $dosen->user->update($userData);
            }
        });

        return redirect()->route('admin.dosen.index')->with('success', 'Data Dosen berhasil diperbarui!');
    }

    // --- HAPUS DOSEN ---
    public function destroyDosen($id)
    {
        $dosen = Dosen::findOrFail($id);
        if ($dosen->user) {
            $dosen->user->delete();
        } else {
            $dosen->delete();
        }

        return redirect()->route('admin.dosen.index')->with('success', 'Data Dosen berhasil dihapus!');
    }


    // ------------------- MAHASISWA -------------------

    // 1. Halaman Tabel Data Mahasiswa
    public function indexMahasiswa(Request $request)
    {
        $search = $request->query('search');
        $mahasiswa = Mahasiswa::when($search, function ($query, $search) {
            return $query->where('nama_mahasiswa', 'like', "%{$search}%")
                         ->orWhere('nim', 'like', "%{$search}%");
        })->latest()->get();

        return view('admin.mahasiswa_index', compact('mahasiswa'));
    }

    // 2. Halaman Form Tambah Mahasiswa
    public function createMahasiswa()
    {
        $periode = PeriodeAkademik::where('status', 'aktif')->get();
        return view('admin.mahasiswa_create', compact('periode'));
    }

    // 3. Simpan Data Mahasiswa (PASSWORD WAJIB)
    public function storeMahasiswa(Request $request)
    {
        $request->validate([
            'nim'            => 'required|unique:mahasiswa,nim|unique:users,username',
            'nama_mahasiswa' => 'required|string|max:255',
            'periode_id'     => 'required',
            'password'       => 'required|string|min:4', // WAJIB DIISI
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name'     => $request->nama_mahasiswa,
                'username' => $request->nim,
                'password' => Hash::make($request->password),
                'role'     => 'mahasiswa',
            ]);

            Mahasiswa::create([
                'user_id'        => $user->id,
                'nim'            => $request->nim,
                'nama_mahasiswa' => $request->nama_mahasiswa,
            ]);
        });

        return redirect()->route('admin.mahasiswa.index')->with('success', 'Data Mahasiswa berhasil disimpan!');
    }

    // --- EDIT & UPDATE MAHASISWA ---
    public function editMahasiswa($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $periode = PeriodeAkademik::where('status', 'aktif')->get();
        return view('admin.mahasiswa_create', compact('mahasiswa', 'periode'));
    }

    public function updateMahasiswa(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $request->validate([
            'nim'            => 'required|unique:mahasiswa,nim,' . $mahasiswa->id,
            'nama_mahasiswa' => 'required|string|max:255',
            'periode_id'     => 'required',
            'password'       => 'nullable|string|min:4', // Opsional saat edit
        ]);

        DB::transaction(function () use ($request, $mahasiswa) {
            $mahasiswa->update([
                'nim'            => $request->nim,
                'nama_mahasiswa' => $request->nama_mahasiswa,
            ]);

            if ($mahasiswa->user) {
                $userData = [
                    'name'     => $request->nama_mahasiswa,
                    'username' => $request->nim,
                ];

                if ($request->filled('password')) {
                    $userData['password'] = Hash::make($request->password);
                }

                $mahasiswa->user->update($userData);
            }
        });

        return redirect()->route('admin.mahasiswa.index')->with('success', 'Data Mahasiswa berhasil diperbarui!');
    }

    // --- HAPUS MAHASISWA ---
    public function destroyMahasiswa($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        if ($mahasiswa->user) {
            $mahasiswa->user->delete();
        } else {
            $mahasiswa->delete();
        }

        return redirect()->route('admin.mahasiswa.index')->with('success', 'Data Mahasiswa berhasil dihapus!');
    }
}