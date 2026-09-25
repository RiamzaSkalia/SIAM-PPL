<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodeAkademik extends Model
{
    protected $table = 'periode_akademik';
    protected $fillable = ['nama_periode', 'tanggal_mulai', 'tanggal_selesai', 'status'];
}