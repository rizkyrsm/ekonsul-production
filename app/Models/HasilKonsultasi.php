<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilKonsultasi extends Model
{
    protected $table = 'hasil_konsultasi'; // Nama tabel

    protected $fillable = [
        'id_order',
        'rangkuman',
        'saran',
    ];

    // Relasi ke Order
    public function order()
    {
        return $this->belongsTo(Order::class, 'id_order', 'id_order');
    }
}
