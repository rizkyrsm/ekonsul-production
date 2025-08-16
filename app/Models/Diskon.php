<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diskon extends Model
{
    use HasFactory;
    protected $table = 'diskons'; // optional, tapi aman ditulis
    protected $primaryKey = 'id_diskon'; // <-- tambahkan ini
    protected $fillable = [
        'nama_diskon', 'kode_voucher', 'jumlah_diskon_harga', 'jumlah_diskon_persen', 'status_aktiv',
    ];

    // Jika ingin menambahkan relasi atau mutator, bisa ditambahkan di sini.
}
