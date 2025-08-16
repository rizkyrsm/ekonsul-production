<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\User;
use App\Models\Diskon;
use App\Models\Layanan;
use App\Models\Order;
use App\Models\Penilaian;
use Illuminate\Support\Facades\Auth;

class DashController extends Component
{

    public function index()
    {
        $user = Auth::user();

        // Default
        $jumlahClient = 0;
        $jumlahKonseling = 0;
        $rataNilaiKonselor = null;

        if ($user->role === 'KONSELOR') {
            // Client dilayani
            $jumlahClient = Order::where('id_konselor', $user->id)
                ->distinct('id_user')
                ->count('id_user');

            // Konseling diselesaikan
            $jumlahKonseling = Order::where('id_konselor', $user->id)
                ->where('payment_status', 'SELESAI')
                ->count();

            // Rata-rata nilai konselor
            $rataNilaiKonselor = Penilaian::where('id_konselor', $user->id)
                ->avg('nilai');
            // Bisa dibulatkan kalau mau
            $rataNilaiKonselor = $rataNilaiKonselor ? round($rataNilaiKonselor, 2) : 0;
        }

        // Orders Lunas
        $ordersLunas = collect();
        if ($user->role === 'USER') {
            $ordersLunas = Order::where('id_user', $user->id)
                ->where('payment_status', 'LUNAS')
                ->get();
        } elseif ($user->role === 'KONSELOR') {
            $ordersLunas = Order::where('id_konselor', $user->id)
                ->where('payment_status', 'LUNAS')
                ->get();
        }

        return view('dashboard', [
            'jumlahKonselor' => User::where('role', 'KONSELOR')->count(),
            'jumlahUser' => User::where('role', 'USER')->count(),
            'jumlahDiskonAktif' => Diskon::where('status_aktiv', 'AKTIF')->count(),
            'layanans' => Layanan::all(),
            'konselings' => $ordersLunas,
            'jumlahClient' => $jumlahClient,
            'jumlahKonseling' => $jumlahKonseling,
            'rataNilaiKonselor' => $rataNilaiKonselor,
        ]);
    }


    public function toggleStatus()
    {
        $user = Auth::user();
        $user->status = $user->status === 'ACTIVE' ? 'NONACTIVE' : 'ACTIVE';
        $user->save();

        return redirect()->back();
    }


    public function redirectNotif($notifId)
    {
        $user = Auth::user();
        $notif = \App\Models\Notif::find($notifId);

        if ($notif && $notif->id_penerima == $user->id) {
            $notif->status = 'terbaca';
            $notif->save();

            // Tentukan rute tujuan berdasarkan role atau isi notifikasi
            if ($user->role === 'KONSELOR' || stripos($notif->keterangan, 'LUNAS') !== false) {
                return redirect()->route('konseling', ['id' => $notif->id_order]);
            }

            if (stripos($notif->keterangan, 'otomatis') !== false) {
                return redirect()->route('konseling', ['id' => $notif->id_order]);
            }

            return redirect()->route('dashboard');
        }

        // Jika tidak valid, kembalikan ke dashboard
        return redirect()->route('dashboard');
    }
}
