<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Layanan; // pastikan User model di-import

class ControllerBeranda extends Controller
{
    public function listlayanan()
    {
        $layanans = Layanan::all(); // ambil semua data user
        return view('landing', compact('layanans')); // kirim data ke view
    }
}
