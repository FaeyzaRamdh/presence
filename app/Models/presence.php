<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presence extends Model
{
    protected $fillable = [
        'user_id',
        'tanggal_absen',
        'jam_masuk',
        'jam_keluar',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

