<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengaturanSistem extends Model
{
    use HasFactory;

    protected $table = 'pengaturan_sistem';

    protected $fillable = [
        'logo_path',
        'header_1',
        'header_2',
        'header_3',
        'header_4',
        'header_5',
        'ttd_jabatan',
        'ttd_nama',
        'ttd_nip',
    ];
}