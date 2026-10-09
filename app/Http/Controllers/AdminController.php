<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\SekolahMitra;
use App\Models\GuruPamong;
use App\Models\PeriodeAkademik;
use App\Models\PlottingBimbingan;
use App\Models\Konsultasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    /**
     * Helper Ambil Periode Aktif Saat Ini
     */
    private function getActivePeriode()
    {
        return PeriodeAkademik::where('status', 'aktif')->first();
    }

    // ==========================================
    // 0. DASHBOARD ADMIN (REALTIME PERIODE AKTIF)
    // ==========================================
    public function dashboard()
    {
        $activePeriode = $this->getActivePeriode();
        $periodeId = $activePeriode ? $activePeriode->id : null;

        // Hitung Mahasiswa & Sekolah
        $totalMahasiswa = Mahasiswa::where(function($q) use ($periodeId) {
            $q->where('periode_id', $periodeId)->orWhereNull('periode_id');
        })->count();

        // Hitung Dosen (Termasuk yang periode_id masih NULL)
        $totalDosen = Dosen::where(function($q) use ($periodeId) {
            $q->where('periode_id', $periodeId)->orWhereNull('periode_id');
        })->count();

        // Hitung Sekolah Mitra
        $totalSekolah = SekolahMitra::where(function($q) use ($periodeId) {
            $q->where('periode_id', $periodeId)->orWhereNull('periode_id');
        })->count();
        
        $totalKonsultasi = Konsultasi::whereHas('plottingBimbingan', function($q) use ($periodeId) {
            $q->where('periode_id', $periodeId);
        })->count();

        // Hitung kelayakan cetak kartu
        $mahasiswaLayak = Mahasiswa::where(function($q) use ($periodeId) {
                $q->where('periode_id', $periodeId)->orWhereNull('periode_id');
            })
            ->whereHas('plottingBimbingan.konsultasi', function($q) {
                $q->where('status_validasi', 'disetujui');
            }, '>=', 5)->count();

        $mahasiswaBelumLayak = max(0, $totalMahasiswa - $mahasiswaLayak);
        $persenLayak = $totalMahasiswa > 0 ? round(($mahasiswaLayak / $totalMahasiswa) * 100) : 0;
        $persenBelumLayak = $totalMahasiswa > 0 ? (100 - $persenLayak) : 0;

        // Peringatan sistem khusus
        $sekolahTanpaPamong = SekolahMitra::where(function($q) use ($periodeId) {
            $q->where('periode_id', $periodeId)->orWhereNull('periode_id');
        })->doesntHave('guruPamong')->get();

        $mhsBelumPlot = Mahasiswa::where(function($q) use ($periodeId) {
            $q->where('periode_id', $periodeId)->orWhereNull('periode_id');
        })->doesntHave('plottingBimbingan')->get();

        // Aktivitas konsultasi terakhir
        $konsultasiTerakhir = Konsultasi::whereHas('plottingBimbingan', function($q) use ($periodeId) {
            $q->where('periode_id', $periodeId);
        })->with(['plottingBimbingan.mahasiswa', 'plottingBimbingan.sekolah', 'plottingBimbingan.dosen'])
          ->latest()
          ->take(5)
          ->get();

        return view('admin.dashboard', compact(
            'activePeriode', 'totalMahasiswa', 'totalDosen', 'totalSekolah', 'totalKonsultasi',
            'mahasiswaLayak', 'mahasiswaBelumLayak', 'persenLayak', 'persenBelumLayak',
            'sekolahTanpaPamong', 'mhsBelumPlot', 'konsultasiTerakhir'
        ));
    }

    // ==========================================
    // 1. MODUL DOSEN
    // ==========================================
    // ==========================================
    // 1. MODUL DOSEN
    // ==========================================
    public function indexDosen(Request $request)
    {
        $activePeriode = $this->getActivePeriode();
        $search = $request->query('search');

        // Tampilkan dosen di periode aktif ATAU dosen yang belum punya periode_id
        $dosen = Dosen::when($activePeriode, function ($query) use ($activePeriode) {
                return $query->where(function($q) use ($activePeriode) {
                    $q->where('periode_id', $activePeriode->id)
                      ->orWhereNull('periode_id');
                });
            })
            ->with(['plotting.mahasiswa'])
            ->when($search, function ($query, $search) {
                return $query->where('nama_dosen', 'like', "%{$search}%")
                             ->orWhere('nip', 'like', "%{$search}%");
            })->latest()->get();

        return view('admin.dosen_index', compact('dosen'));
    }

    public function storeDosen(Request $request)
    {
        $activePeriode = $this->getActivePeriode();

        $request->validate([
            'nip'        => 'required|unique:dosen,nip|unique:users,username',
            'nama_dosen' => 'required|string|max:255',
            'email'      => 'required|email',
            'password'   => 'required|string|min:4',
        ]);

        DB::transaction(function () use ($request, $activePeriode) {
            $user = User::create([
                'name'     => $request->nama_dosen,
                'username' => $request->nip,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'dosen',
            ]);

            Dosen::create([
                'user_id'    => $user->id,
                'periode_id' => $activePeriode ? $activePeriode->id : null,
                'nip'        => $request->nip,
                'nama_dosen' => $request->nama_dosen,
                'email'      => $request->email,
            ]);
        });

        return redirect()->route('admin.dosen.index')->with('success', 'Data Dosen berhasil disimpan!');
    }

    public function createDosen()
    {
        return view('admin.dosen_create');
    }


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

    // ==========================================
    // 2. MODUL MAHASISWA
    // ==========================================
    public function indexMahasiswa(Request $request)
    {
        $activePeriode = $this->getActivePeriode();
        $search = $request->query('search');

        $mahasiswa = Mahasiswa::where('periode_id', $activePeriode->id ?? null)
            ->with(['plottingBimbingan.sekolah', 'plottingBimbingan.dosen'])
            ->when($search, function ($query, $search) {
                return $query->where('nama_mahasiswa', 'like', "%{$search}%")
                             ->orWhere('nim', 'like', "%{$search}%");
            })->latest()->get();

        return view('admin.mahasiswa_index', compact('mahasiswa'));
    }

    public function createMahasiswa()
    {
        return view('admin.mahasiswa_create');
    }

    public function storeMahasiswa(Request $request)
    {
        $activePeriode = $this->getActivePeriode();

        if (!$activePeriode) {
            return redirect()->route('admin.pengaturan.index')
                ->with('error', 'Silakan buat/aktifkan periode akademik terlebih dahulu sebelum menambah data!');
        }

        $request->validate([
            'nim'            => 'required|unique:mahasiswa,nim|unique:users,username',
            'nama_mahasiswa' => 'required|string|max:255',
            'password'       => 'required|string|min:4',
            'no_hp'          => 'nullable|string|max:20',
        ]);

        DB::transaction(function () use ($request, $activePeriode) {
            $user = User::create([
                'name'     => $request->nama_mahasiswa,
                'username' => $request->nim,
                'password' => Hash::make($request->password),
                'role'     => 'mahasiswa',
            ]);

            Mahasiswa::create([
                'user_id'        => $user->id,
                'periode_id'     => $activePeriode->id,
                'nim'            => $request->nim,
                'nama_mahasiswa' => $request->nama_mahasiswa,
                'no_hp'          => $request->no_hp,
            ]);
        });

        return redirect()->route('admin.mahasiswa.index')->with('success', 'Data Mahasiswa berhasil disimpan!');
    }

    public function editMahasiswa($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        return view('admin.mahasiswa_create', compact('mahasiswa'));
    }

    public function updateMahasiswa(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $request->validate([
            'nim'            => 'required|unique:mahasiswa,nim,' . $mahasiswa->id,
            'nama_mahasiswa' => 'required|string|max:255',
            'no_hp'          => 'nullable|string|max:20',
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

    // ==========================================
    // 3. MODUL SEKOLAH MITRA
    // ==========================================
    public function indexSekolah(Request $request)
    {
        $activePeriode = $this->getActivePeriode();
        $search = $request->query('search');

        $sekolah = SekolahMitra::where('periode_id', $activePeriode->id ?? null)
            ->when($search, function ($query, $search) {
                return $query->where('nama_sekolah', 'like', "%{$search}%")
                             ->orWhere('npsn', 'like', "%{$search}%");
            })->latest()->get();

        return view('admin.sekolah_index', compact('sekolah'));
    }

    public function createSekolah()
    {
        return view('admin.sekolah_create');
    }

    public function storeSekolah(Request $request)
    {
        $activePeriode = $this->getActivePeriode();

        if (!$activePeriode) {
            return redirect()->route('admin.pengaturan.index')
                ->with('error', 'Silakan buat/aktifkan periode akademik terlebih dahulu sebelum menambah data!');
        }

        $request->validate([
            'npsn'         => 'required|string',
            'nama_sekolah' => 'required|string|max:255',
            'jenjang'      => 'required|string',
            'kuota'        => 'required|integer|min:1',
        ]);

        SekolahMitra::create([
            'periode_id'   => $activePeriode->id,
            'npsn'         => $request->npsn,
            'nama_sekolah' => $request->nama_sekolah,
            'jenjang'      => $request->jenjang,
            'kuota'        => $request->kuota,
            'alamat'       => $request->alamat,
        ]);

        return redirect()->route('admin.sekolah.index')->with('success', 'Data Sekolah Mitra berhasil disimpan!');
    }

    public function editSekolah($id)
    {
        $sekolah = SekolahMitra::findOrFail($id);
        return view('admin.sekolah_create', compact('sekolah'));
    }

    public function updateSekolah(Request $request, $id)
    {
        $sekolah = SekolahMitra::findOrFail($id);
        $request->validate([
            'npsn'         => 'required|string',
            'nama_sekolah' => 'required|string|max:255',
            'jenjang'      => 'required|string',
            'kuota'        => 'required|integer|min:1',
        ]);

        $sekolah->update([
            'npsn'         => $request->npsn,
            'nama_sekolah' => $request->nama_sekolah,
            'jenjang'      => $request->jenjang,
            'kuota'        => $request->kuota,
            'alamat'       => $request->alamat,
        ]);

        return redirect()->route('admin.sekolah.index')->with('success', 'Data Sekolah Mitra berhasil diperbarui!');
    }

    public function destroySekolah($id)
    {
        $sekolah = SekolahMitra::findOrFail($id);
        $sekolah->delete();

        return redirect()->route('admin.sekolah.index')->with('success', 'Data Sekolah Mitra berhasil dihapus!');
    }

    // ==========================================
    // 4. MODUL PEMETAAN BIMBINGAN
    // ==========================================
    public function indexPemetaan(Request $request)
    {
        $activePeriode = $this->getActivePeriode();
        $search = $request->query('search');
        $periodeId = $request->query('periode_id', $activePeriode->id ?? null);
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

    public function createPemetaan()
    {
        $activePeriode = $this->getActivePeriode();
        $periodeList = PeriodeAkademik::all();

        // Tampilkan data yang sesuai periode aktif ATAU yang periode_id nya NULL/kosong
        $sekolahList = SekolahMitra::when($activePeriode, function ($q) use ($activePeriode) {
            return $q->where('periode_id', $activePeriode->id)->orWhereNull('periode_id');
        })->with('guruPamong')->get();

        $dosenList = Dosen::when($activePeriode, function ($q) use ($activePeriode) {
            return $q->where('periode_id', $activePeriode->id)->orWhereNull('periode_id');
        })->get();

        $mahasiswaList = Mahasiswa::when($activePeriode, function ($q) use ($activePeriode) {
            return $q->where('periode_id', $activePeriode->id)->orWhereNull('periode_id');
        })->get();

        return view('admin.pemetaan_create', compact('periodeList', 'sekolahList', 'dosenList', 'mahasiswaList'));
    }

    public function editPemetaan($id)
    {
        $activePeriode = $this->getActivePeriode();
        $plotting = PlottingBimbingan::with('mahasiswa')->findOrFail($id);
        $periodeList = PeriodeAkademik::all();

        $sekolahList = SekolahMitra::when($activePeriode, function ($q) use ($activePeriode) {
            return $q->where('periode_id', $activePeriode->id)->orWhereNull('periode_id');
        })->with('guruPamong')->get();

        $dosenList = Dosen::when($activePeriode, function ($q) use ($activePeriode) {
            return $q->where('periode_id', $activePeriode->id)->orWhereNull('periode_id');
        })->get();

        $mahasiswaList = Mahasiswa::when($activePeriode, function ($q) use ($activePeriode) {
            return $q->where('periode_id', $activePeriode->id)->orWhereNull('periode_id');
        })->get();

        return view('admin.pemetaan_create', compact('plotting', 'periodeList', 'sekolahList', 'dosenList', 'mahasiswaList'));
    }

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

    public function destroyPemetaan($id)
    {
        $plotting = PlottingBimbingan::findOrFail($id);
        $plotting->delete();

        return redirect()->route('admin.pemetaan.index')->with('success', 'Pemetaan Bimbingan berhasil dihapus!');
    }

    public function exportPemetaanPdf()
    {
        $activePeriode = $this->getActivePeriode();
        $plotting = PlottingBimbingan::where('periode_id', $activePeriode->id ?? null)
            ->with(['sekolah.guruPamong', 'dosen', 'mahasiswa'])
            ->get();
        
        $pdf = Pdf::loadView('admin.pemetaan_pdf', compact('plotting'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download('Penempatan_AM_PILKOM.pdf');
    }
}