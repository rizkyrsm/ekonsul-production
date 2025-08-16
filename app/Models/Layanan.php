<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    // app/Models/Layanan.php
    protected $primaryKey = 'id_layanan';
    public $incrementing = true;
    protected $fillable = ['nama_layanan', 'warna_layanan', 'harga_layanan'];
}
