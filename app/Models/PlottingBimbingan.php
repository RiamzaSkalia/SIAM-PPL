<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlottingBimbingan extends Model
{
    use HasFactory;

    protected $table = 'plotting_bimbingan';

    protected $fillable = [
        'periode_id',
        'dosen_id',
        'mahasiswa_id',
        'gupam_id',
        'sekolah_id',
    ];

    public function periode(): BelongsTo
    {
        return $this->belongsTo(PeriodeAkademik::class, 'periode_id');
    }

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }

    public function guruPamong(): BelongsTo
    {
        return $this->belongsTo(GuruPamong::class, 'gupam_id');
    }

    public function sekolahMitra(): BelongsTo
    {
        return $this->belongsTo(SekolahMitra::class, 'sekolah_id');
    }

    public function konsultasi(): HasMany
    {
        return $this->hasMany(Konsultasi::class, 'plotting_id');
    }
}
