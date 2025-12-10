<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Periode extends Model
{
    protected $table = "ms_periode";

    protected $primaryKey = "periode";

    protected $keyType = "string";

    public $timestamps = false;

    protected $fillable = [
        'aktif_pemilih',
    ];

    // override delete supaya tidak bisa
    public function delete()
    {
        throw new \Exception("This model is read-only.");
    }
}
