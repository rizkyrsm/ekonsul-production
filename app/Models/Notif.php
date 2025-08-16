<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notif extends Model
{
    use HasFactory;

    protected $table = 'notifs'; // Nama tabel (opsional kalau sesuai konvensi)

    protected $fillable = [
        'keterangan',
        'id_order',
        'role',
        'id_penerima',
        'status',
    ];

    // Relasi ke tabel orders
    public function order()
    {
        return $this->belongsTo(Order::class, 'id_order');
    }

    // Relasi ke user penerima notifikasi
    public function penerima()
    {
        return $this->belongsTo(User::class, 'id_penerima');
    }

}
