<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    protected $table = 't_penilaian';

    protected $fillable = [
        'id_order',
        'id_konselor',
        'nilai',
    ];
}
