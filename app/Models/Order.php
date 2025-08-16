<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_order';
    protected $fillable = [
        'id_order',
        'id_user',
        'id_konselor',
        'nama_layanan',
        'voucher',
        'total',
        'payment_status',
        'bukti_transfer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function konselor()
    {
        return $this->belongsTo(User::class, 'id_konselor');
    }

    public function hasilKonsultasi()
    {
        return $this->hasOne(HasilKonsultasi::class, 'id_order', 'id_order');
    }
}
