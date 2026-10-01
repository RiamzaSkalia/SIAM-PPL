@extends('layouts.admin')

@section('content')
<h3 class="fw-bold mb-4" style="color: #2b3990;">
    {{ isset($plotting) ? 'Edit Pemetaan Bimbingan' : 'Pemetaan Bimbingan' }}
</h3>

<div class="form-card p-4" style="background-color: #f7ede2; border-radius: 12px;">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <h5 class="fw-bold m-0" style="color: #2b3990;">
            🔗 {{ isset($plotting) ? 'Edit Alokasi Penempatan Bimbingan' : 'Alokasi Penempatan Bimbingan Baru' }}
        </h5>
        <a href="{{ route('admin.pemetaan.index') }}" class="text-decoration-none text-dark fs-5 fw-bold">&times;</a>
    </div>

    <form action="{{ isset($plotting) ? route('admin.pemetaan.update', $plotting->id) : route('admin.pemetaan.store') }}" method="POST">
        @csrf
        @if(isset($plotting))
            @method('PUT')
        @endif

        {{-- Step 1: Pilih Periode --}}
        <div class="mb-3">
            <label class="fw-bold mb-1 fs-6">1. Pilih Periode Akademik <span class="text-danger">*</span></label>
            <select name="periode_id" class="form-select rounded-3 p-2" required>
                <option value="">-- Pilih Periode --</option>
                @foreach($periodeList as $p)
                    <option value="{{ $p->id }}" {{ (old('periode_id', $plotting->periode_id ?? '') == $p->id) ? 'selected' : '' }}>
                        {{ $p->nama_periode }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Step 2: Pilih Sekolah & Pamong --}}
        <div class="mb-3">
            <label class="fw-bold mb-1 fs-6">2. Pilih Sekolah Mitra & Guru Pamong <span class="text-danger">*</span></label>
            <select name="sekolah_id" class="form-select rounded-3 p-2" required>
                <option value="">-- Pilih Sekolah Mitra --</option>
                @foreach($sekolahList as $s)
                    <option value="{{ $s->id }}" {{ (old('sekolah_id', $plotting->sekolah_id ?? '') == $s->id) ? 'selected' : '' }}>
                        {{ $s->nama_sekolah }} ({{ $s->jenjang }}) - Pamong: {{ $s->guruPamong->nama_guru_pamong ?? 'Belum ada Pamong' }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Step 3: Pilih DPL --}}
        <div class="mb-3">
            <label class="fw-bold mb-1 fs-6">3. Pilih Dosen Pembimbing (DPL) <span class="text-danger">*</span></label>
            <select name="dosen_id" class="form-select rounded-3 p-2" required>
                <option value="">-- Pilih Dosen Pembimbing --</option>
                @foreach($dosenList as $d)
                    <option value="{{ $d->id }}" {{ (old('dosen_id', $plotting->dosen_id ?? '') == $d->id) ? 'selected' : '' }}>
                        {{ $d->nama_dosen }} (NIP: {{ $d->nip }})
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Step 4: Checklist Mahasiswa --}}
        <div class="mb-4">
            <label class="fw-bold mb-2 fs-6">4. Pilih Mahasiswa (Checklist Mahasiswa yang Ditempatkan di Sekolah Ini) <span class="text-danger">*</span></label>
            
            @php
                $selectedMhs = isset($plotting) ? $plotting->mahasiswa->pluck('id')->toArray() : [];
            @endphp

            <div class="p-3 bg-white border rounded-3" style="max-height: 250px; overflow-y: auto;">
                @forelse($mahasiswaList as $mhs)
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="mahasiswa_ids[]" value="{{ $mhs->id }}" id="mhs_{{ $mhs->id }}"
                            {{ in_array($mhs->id, old('mahasiswa_ids', $selectedMhs)) ? 'checked' : '' }}>
                        <label class="form-check-label" for="mhs_{{ $mhs->id }}">
                            <strong>{{ $mhs->nim }}</strong> - {{ $mhs->nama_mahasiswa }} 
                            @if($mhs->no_hp)
                                <span class="text-muted">(WA: {{ $mhs->no_hp }})</span>
                            @endif
                        </label>
                    </div>
                @empty
                    <p class="text-muted m-0">Belum ada data mahasiswa terdaftar.</p>
                @endforelse
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
            <a href="{{ route('admin.pemetaan.index') }}" class="btn btn-secondary rounded-pill px-4 fw-bold">Batal</a>
            <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold">💾 Simpan Pemetaan</button>
        </div>
    </form>
</div>
@endsection