<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $table = "z_evote_trs_vote";

    public $timestamps = false;

    protected $fillable = [
        'id_ms_presiden_bem',
        'id_ms_hima',
        'periode',
        'nim',
        'created_at',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, "nim", "nim");
    }

    public function presiden_bem()
    {
        return $this->belongsTo(PresidenBem::class, "id_ms_presiden_bem");
    }

    public function hima()
    {
        return $this->belongsTo(Hima::class, "id_ms_hima");
    }
}
