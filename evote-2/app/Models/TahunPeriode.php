<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TahunPeriode extends Model
{
    protected $table = "z_evote_mst_tahun_periode";

    public $timestamps = false;

    protected $fillable = [
        'periode',
        'is_aktif',
        'created_at',
    ];

    // override delete supaya tidak bisa
    public function delete()
    {
        throw new \Exception("This model is read-only.");
    }
}
