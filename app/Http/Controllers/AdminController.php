<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\GuruPamong;
use App\Models\Mahasiswa;
use App\Models\PeriodeAkademik;
use App\Models\SekolahMitra;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\PlottingBimbingan;

class AdminController extends Controller
{

    // ------------------- PEMETAAN BIMBINGAN -------------------

    // 1. Halaman Index Pemetaan Bimbingan
    public function indexPemetaan(Request $request)
    {
        $search = $request->query('search');
        $periodeId = $request->query('periode_id');
        $jenjang = $request->query('jenjang');

        $periodeList = PeriodeAkademik::all();

        $plotting = PlottingBimbingan::with(['sekolah.guruPamong', 'dosen', 'mahasiswa', 'periode'])
            ->when($periodeId, function ($q) use ($periodeId) {
                return $q->where('periode_id', $periodeId);
            })
            ->when($jenjang, function ($q) use ($jenjang) {
                return $q->whereHas('sekolah', function ($s) use ($jenjang) {
                    $s->where('jenjang', $jenjang);
                });
            })
            ->when($search, function ($q) use ($search) {
                return $q->whereHas('sekolah', function ($s) use ($search) {
                    $s->where('nama_sekolah', 'like', "%{$search}%");
                })->orWhereHas('dosen', function ($d) use ($search) {
                    $d->where('nama_dosen', 'like', "%{$search}%");
                })->orWhereHas('mahasiswa', function ($m) use ($search) {
                    $m->where('nama_mahasiswa', 'like', "%{$search}%");
                });
            })->latest()->get();

        return view('admin.pemetaan_index', compact('plotting', 'periodeList'));
    }

    // 2. Form Tambah Pemetaan Bimbingan
    public function createPemetaan()
    {
        $periodeList = PeriodeAkademik::where('status', 'aktif')->get();
        $sekolahList = SekolahMitra::with('guruPamong')->get();
        $dosenList = Dosen::all();
        $mahasiswaList = Mahasiswa::all();

        return view('admin.pemetaan_create', compact('periodeList', 'sekolahList', 'dosenList', 'mahasiswaList'));
    }

    // 3. Simpan Pemetaan Bimbingan
    public function storePemetaan(Request $request)
    {
        $request->validate([
            'periode_id'   => 'required',
            'sekolah_id'   => 'required',
            'dosen_id'     => 'required',
            'mahasiswa_ids'=> 'required|array|min:1',
        ]);

        DB::transaction(function () use ($request) {
            $plotting = PlottingBimbingan::create([
                'periode_id' => $request->periode_id,
                'sekolah_id' => $request->sekolah_id,
                'dosen_id'   => $request->dosen_id,
            ]);

            $plotting->mahasiswa()->sync($request->mahasiswa_ids);
        });

        return redirect()->route('admin.pemetaan.index')->with('success', 'Pemetaan Bimbingan berhasil disimpan!');
    }

    // 4. Form Edit Pemetaan Bimbingan
    public function editPemetaan($id)
    {
        $plotting = PlottingBimbingan::with('mahasiswa')->findOrFail($id);
        $periodeList = PeriodeAkademik::all();
        $sekolahList = SekolahMitra::with('guruPamong')->get();
        $dosenList = Dosen::all();
        $mahasiswaList = Mahasiswa::all();

        return view('admin.pemetaan_create', compact('plotting', 'periodeList', 'sekolahList', 'dosenList', 'mahasiswaList'));
    }

    // 5. Update Pemetaan Bimbingan
    public function updatePemetaan(Request $request, $id)
    {
        $plotting = PlottingBimbingan::findOrFail($id);
        $request->validate([
            'periode_id'   => 'required',
            'sekolah_id'   => 'required',
            'dosen_id'     => 'required',
            'mahasiswa_ids'=> 'required|array|min:1',
        ]);

        DB::transaction(function () use ($request, $plotting) {
            $plotting->update([
                'periode_id' => $request->periode_id,
                'sekolah_id' => $request->sekolah_id,
                'dosen_id'   => $request->dosen_id,
            ]);

            $plotting->mahasiswa()->sync($request->mahasiswa_ids);
        });

        return redirect()->route('admin.pemetaan.index')->with('success', 'Pemetaan Bimbingan berhasil diperbarui!');
    }

    // 6. Hapus Pemetaan Bimbingan
    public function destroyPemetaan($id)
    {
        $plotting = PlottingBimbingan::findOrFail($id);
        $plotting->delete();

        return redirect()->route('admin.pemetaan.index')->with('success', 'Pemetaan Bimbingan berhasil dihapus!');
    }
    // =========================================================================
    // SEKOLAH MITRA & GURU PAMONG
    // =========================================================================

    // 1. Halaman Tabel Data Sekolah
    public function indexSekolah(Request $request)
    {
        $search = $request->query('search');
        $sekolah = SekolahMitra::with('guruPamong')
            ->when($search, function ($query, $search) {
                return $query->where('nama_sekolah', 'like', "%{$search}%")
                    ->orWhereHas('guruPamong', function ($q) use ($search) {
                        $q->where('nama_guru_pamong', 'like', "%{$search}%");
                    });
            })
            ->latest()
            ->get();

        return view('admin.sekolah_index', compact('sekolah'));
    }

    // 2. Form Tambah Sekolah & Guru Pamong
    public function createSekolah()
    {
        return view('admin.sekolah_create');
    }

    // 3. Simpan Data Sekolah & Guru Pamong
    public function storeSekolah(Request $request)
    {
        $request->validate([
            'nama_sekolah'     => 'required|string|max:255',
            'jenjang'          => 'required|string',
            'kuota'            => 'required|integer|min:1',
            'alamat'           => 'nullable|string',
            'nip_nik'          => 'required|unique:guru_pamong,nip_nik|unique:users,username',
            'nama_guru_pamong' => 'required|string|max:255',
            'no_hp'            => 'nullable|string|max:20',
            'password'         => 'required|string|min:4',
        ]);

        DB::transaction(function () use ($request) {
            // A. Simpan Data Sekolah Mitra
            $sekolah = SekolahMitra::create([
                'nama_sekolah' => $request->nama_sekolah,
                'jenjang'      => $request->jenjang,
                'kuota'        => $request->kuota,
                'alamat'       => $request->alamat,
            ]);

            // B. Simpan Akun User Guru Pamong
            $user = User::create([
                'name'     => $request->nama_guru_pamong,
                'username' => $request->nip_nik,
                'password' => Hash::make($request->password),
                'role'     => 'gupam',
            ]);

            // C. Simpan Detail Guru Pamong
            GuruPamong::create([
                'user_id'          => $user->id,
                'sekolah_id'       => $sekolah->id,
                'nip_nik'          => $request->nip_nik,
                'nama_guru_pamong' => $request->nama_guru_pamong,
                'no_hp'            => $request->no_hp,
            ]);
        });

        return redirect()->route('admin.sekolah.index')->with('success', 'Data Sekolah Mitra & Guru Pamong berhasil disimpan!');
    }

    // 4. Form Edit Sekolah & Guru Pamong
    public function editSekolah($id)
    {
        $sekolah = SekolahMitra::with('guruPamong.user')->findOrFail($id);
        return view('admin.sekolah_create', compact('sekolah'));
    }

    // 5. Update Data Sekolah & Guru Pamong
    public function updateSekolah(Request $request, $id)
    {
        $sekolah = SekolahMitra::with('guruPamong.user')->findOrFail($id);
        $gupam = $sekolah->guruPamong;

        $request->validate([
            'nama_sekolah'     => 'required|string|max:255',
            'jenjang'          => 'required|string',
            'kuota'            => 'required|integer|min:1',
            'alamat'           => 'nullable|string',
            'nip_nik'          => 'required|unique:guru_pamong,nip_nik,' . ($gupam ? $gupam->id : 'NULL'),
            'nama_guru_pamong' => 'required|string|max:255',
            'no_hp'            => 'nullable|string|max:20',
            'password'         => 'nullable|string|min:4',
        ]);

        DB::transaction(function () use ($request, $sekolah, $gupam) {
            $sekolah->update([
                'nama_sekolah' => $request->nama_sekolah,
                'jenjang'      => $request->jenjang,
                'kuota'        => $request->kuota,
                'alamat'       => $request->alamat,
            ]);

            if ($gupam) {
                $gupam->update([
                    'nip_nik'          => $request->nip_nik,
                    'nama_guru_pamong' => $request->nama_guru_pamong,
                    'no_hp'            => $request->no_hp,
                ]);

                if ($gupam->user) {
                    $userData = [
                        'name'     => $request->nama_guru_pamong,
                        'username' => $request->nip_nik,
                    ];
                    if ($request->filled('password')) {
                        $userData['password'] = Hash::make($request->password);
                    }
                    $gupam->user->update($userData);
                }
            }
        });

        return redirect()->route('admin.sekolah.index')->with('success', 'Data Sekolah Mitra & Guru Pamong berhasil diperbarui!');
    }

    // 6. Hapus Data Sekolah & Guru Pamong
    public function destroySekolah($id)
    {
        $sekolah = SekolahMitra::with('guruPamong.user')->findOrFail($id);

        DB::transaction(function () use ($sekolah) {
            if ($sekolah->guruPamong && $sekolah->guruPamong->user) {
                $sekolah->guruPamong->user->delete(); // Otomatis hapus gupam & sekolah via CASCADE
            } else {
                $sekolah->delete();
            }
        });

        return redirect()->route('admin.sekolah.index')->with('success', 'Data Sekolah & Guru Pamong berhasil dihapus!');
    }

    // =========================================================================
    // DOSEN
    // =========================================================================

    // 1. Halaman Tabel Data Dosen
    public function indexDosen(Request $request)
    {
        $search = $request->query('search');
        
        // Memuat relasi plotting & mahasiswa yang dibimbing
        $dosen = Dosen::with(['plotting.mahasiswa'])
            ->when($search, function ($query, $search) {
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
            'password'   => 'required|string|min:4',
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

    // 4. Halaman Form Edit Dosen
    public function editDosen($id)
    {
        $dosen = Dosen::findOrFail($id);
        return view('admin.dosen_create', compact('dosen'));
    }

    // 5. Update Data Dosen
    public function updateDosen(Request $request, $id)
    {
        $dosen = Dosen::findOrFail($id);
        $request->validate([
            'nip'        => 'required|unique:dosen,nip,' . $dosen->id,
            'nama_dosen' => 'required|string|max:255',
            'email'      => 'required|email',
            'password'   => 'nullable|string|min:4',
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

    // 6. Hapus Data Dosen
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

    // =========================================================================
    // MAHASISWA
    // =========================================================================

    // 1. Halaman Tabel Data Mahasiswa
    public function indexMahasiswa(Request $request)
    {
        $search = $request->query('search');
        
        // Memuat relasi plotting ke sekolah & dosen pembimbing
        $mahasiswa = Mahasiswa::with(['plotting.sekolah', 'plotting.dosen'])
            ->when($search, function ($query, $search) {
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
            'no_hp'          => 'required|string|max:20',
            'periode_id'     => 'required',
            'password'       => 'required|string|min:4',
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
                'no_hp'          => $request->no_hp,
            ]);
        });

        return redirect()->route('admin.mahasiswa.index')->with('success', 'Data Mahasiswa berhasil disimpan!');
    }

    // 4. Halaman Form Edit Mahasiswa
    public function editMahasiswa($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $periode = PeriodeAkademik::where('status', 'aktif')->get();
        return view('admin.mahasiswa_create', compact('mahasiswa', 'periode'));
    }

    // 5. Update Data Mahasiswa
    public function updateMahasiswa(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $request->validate([
            'nim'            => 'required|unique:mahasiswa,nim,' . $mahasiswa->id,
            'nama_mahasiswa' => 'required|string|max:255',
            'no_hp'          => 'required|string|max:20',
            'periode_id'     => 'required',
            'password'       => 'nullable|string|min:4',
        ]);

        DB::transaction(function () use ($request, $mahasiswa) {
            $mahasiswa->update([
                'nim'            => $request->nim,
                'nama_mahasiswa' => $request->nama_mahasiswa,
                'no_hp'          => $request->no_hp,
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

    // 6. Hapus Data Mahasiswa
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