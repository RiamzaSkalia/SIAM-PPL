<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuruPamong extends Model
{
    protected $table = 'guru_pamong';

    protected $fillable = [
        'user_id',
        'nip_nik',
        'nama_guru_pamong',
        'no_hp',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}