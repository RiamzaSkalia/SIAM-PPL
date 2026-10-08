<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\SekolahMitra;
use App\Models\PeriodeAkademik;
use App\Models\PlottingBimbingan;
use App\Models\Konsultasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // Helper Ambil Periode Aktif Saat Ini
    private function getActivePeriode()
    {
        return PeriodeAkademik::where('status', 'aktif')->first();
    }

    // ==========================================
    // DASHBOARD ADMIN (REALTIME BERDASARKAN PERIODE AKTIF)
    // ==========================================
    public function dashboard()
    {
        $activePeriode = $this->getActivePeriode();
        $periodeId = $activePeriode ? $activePeriode->id : null;

        $totalMahasiswa = Mahasiswa::where('periode_id', $periodeId)->count();
        $totalDosen = Dosen::where('periode_id', $periodeId)->count();
        $totalSekolah = SekolahMitra::where('periode_id', $periodeId)->count();
        
        $totalKonsultasi = Konsultasi::whereHas('plotting', function($q) use ($periodeId) {
            $q->where('periode_id', $periodeId);
        })->count();

        // Hitung kelayakan cetak kartu (Minimal 5 sesi validasi)
        $mahasiswaLayak = Mahasiswa::where('periode_id', $periodeId)
            ->whereHas('plotting.konsultasi', function($q) {
                $q->where('status_validasi', 'disetujui');
            }, '>=', 5)->count();

        $mahasiswaBelumLayak = max(0, $totalMahasiswa - $mahasiswaLayak);
        $persenLayak = $totalMahasiswa > 0 ? round(($mahasiswaLayak / $totalMahasiswa) * 100) : 0;
        $persenBelumLayak = $totalMahasiswa > 0 ? (100 - $persenLayak) : 0;

        // Peringatan sistem khusus periode aktif
        $sekolahTanpaPamong = SekolahMitra::where('periode_id', $periodeId)->doesntHave('guruPamong')->get();
        $mhsBelumPlot = Mahasiswa::where('periode_id', $periodeId)->doesntHave('plotting')->get();

        // Aktivitas konsultasi terakhir
        $konsultasiTerakhir = Konsultasi::whereHas('plotting', function($q) use ($periodeId) {
            $q->where('periode_id', $periodeId);
        })->with(['plotting.mahasiswa', 'plotting.sekolah', 'plotting.dosen'])
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
    // MODUL DOSEN (ISOLASI PERIODE)
    // ==========================================
    public function indexDosen(Request $request)
    {
        $activePeriode = $this->getActivePeriode();
        $search = $request->query('search');

        $dosen = Dosen::where('periode_id', $activePeriode->id ?? null)
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
                'periode_id' => $activePeriode->id ?? null,
                'nip'        => $request->nip,
                'nama_dosen' => $request->nama_dosen,
                'email'      => $request->email,
            ]);
        });

        return redirect()->route('admin.dosen.index')->with('success', 'Data Dosen berhasil disimpan!');
    }

    // ==========================================
    // MODUL MAHASISWA (ISOLASI PERIODE)
    // ==========================================
    public function indexMahasiswa(Request $request)
    {
        $activePeriode = $this->getActivePeriode();
        $search = $request->query('search');

        $mahasiswa = Mahasiswa::where('periode_id', $activePeriode->id ?? null)
            ->with(['plotting.sekolah', 'plotting.dosen'])
            ->when($search, function ($query, $search) {
                return $query->where('nama_mahasiswa', 'like', "%{$search}%")
                             ->orWhere('nim', 'like', "%{$search}%");
            })->latest()->get();

        return view('admin.mahasiswa_index', compact('mahasiswa'));
    }

    public function storeMahasiswa(Request $request)
    {
        $activePeriode = $this->getActivePeriode();
        $request->validate([
            'nim'            => 'required|unique:mahasiswa,nim|unique:users,username',
            'nama_mahasiswa' => 'required|string|max:255',
            'password'       => 'required|string|min:4',
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
                'periode_id'     => $activePeriode->id ?? null,
                'nim'            => $request->nim,
                'nama_mahasiswa' => $request->nama_mahasiswa,
                'no_hp'          => $request->no_hp,
            ]);
        });

        return redirect()->route('admin.mahasiswa.index')->with('success', 'Data Mahasiswa berhasil disimpan!');
    }

    // ==========================================
    // MODUL SEKOLAH MITRA (ISOLASI PERIODE)
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

    public function storeSekolah(Request $request)
    {
        $activePeriode = $this->getActivePeriode();
        $request->validate([
            'npsn'         => 'required|string',
            'nama_sekolah' => 'required|string|max:255',
            'jenjang'      => 'required|string',
            'kuota'        => 'required|integer|min:1',
        ]);

        SekolahMitra::create([
            'periode_id'   => $activePeriode->id ?? null,
            'npsn'         => $request->npsn,
            'nama_sekolah' => $request->nama_sekolah,
            'jenjang'      => $request->jenjang,
            'kuota'        => $request->kuota,
            'alamat'       => $request->alamat,
        ]);

        return redirect()->route('admin.sekolah.index')->with('success', 'Data Sekolah Mitra berhasil disimpan!');
    }
    // ==========================================
    // MODUL PEMETAAN BIMBINGAN
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
        $sekolahList = SekolahMitra::where('periode_id', $activePeriode->id ?? null)->with('guruPamong')->get();
        $dosenList = Dosen::where('periode_id', $activePeriode->id ?? null)->get();
        $mahasiswaList = Mahasiswa::where('periode_id', $activePeriode->id ?? null)->get();

        return view('admin.pemetaan_create', compact('periodeList', 'sekolahList', 'dosenList', 'mahasiswaList'));
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

    public function editPemetaan($id)
    {
        $activePeriode = $this->getActivePeriode();
        $plotting = PlottingBimbingan::with('mahasiswa')->findOrFail($id);
        $periodeList = PeriodeAkademik::all();
        $sekolahList = SekolahMitra::where('periode_id', $activePeriode->id ?? null)->with('guruPamong')->get();
        $dosenList = Dosen::where('periode_id', $activePeriode->id ?? null)->get();
        $mahasiswaList = Mahasiswa::where('periode_id', $activePeriode->id ?? null)->get();

        return view('admin.pemetaan_create', compact('plotting', 'periodeList', 'sekolahList', 'dosenList', 'mahasiswaList'));
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
}