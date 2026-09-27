<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PeriodeAkademik extends Model
{
    use HasFactory;

    protected $table = 'periode_akademik';

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    protected $fillable = [
        'nama_periode',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
    ];

    public function plottingBimbingan(): HasMany
    {
        return $this->hasMany(PlottingBimbingan::class, 'periode_id');
    }

    // Periode akademik yang sedang berjalan, dipakai saat admin membuat plotting baru
    public static function aktif(): ?self
    {
        return static::where('status', 'aktif')->latest('tanggal_mulai')->first();
    }
}
