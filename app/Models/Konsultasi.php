<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Konsultasi extends Model
{
    use HasFactory;

    protected $table = 'konsultasi';

    protected $casts = [
        'tanggal_konsul' => 'date',
    ];

    protected $fillable = [
        'plotting_id',
        'tanggal_konsul',
        'waktu_konsul', // BARU: jam bimbingan, format H:i:s
        'media_konsul',
        'topik_dibahas',
        'refleksi_mahasiswa',
        'saran_dosen',
        'paraf_dosen',
        'status_validasi',
    ];

    public function plottingBimbingan(): BelongsTo
    {
        return $this->belongsTo(PlottingBimbingan::class, 'plotting_id');
    }

    public function komentarGupam(): HasMany
    {
        return $this->hasMany(KomentarGupam::class, 'konsultasi_id');
    }

    public function setujui(string $parafDosen): void
    {
        $this->update([
            'status_validasi' => 'disetujui',
            'paraf_dosen' => $parafDosen,
        ]);
    }

    public function tolak(): void
    {
        $this->update(['status_validasi' => 'ditolak']);
    }

    // Label status dalam Bahasa Indonesia, dipakai di badge tabel
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status_validasi) {
            'disetujui' => 'Terverifikasi',
            'ditolak'   => 'Ditolak',
            default     => 'Menunggu',
        };
    }

    // Waktu diformat "09.30" (titik, bukan titik dua) sesuai desain
    public function getWaktuFormatAttribute(): ?string
    {
        return $this->waktu_konsul
            ? \Carbon\Carbon::parse($this->waktu_konsul)->format('H.i')
            : null;
    }
}