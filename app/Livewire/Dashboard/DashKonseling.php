<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;
use App\Models\HasilKonsultasi;
use App\Models\Notif;
use App\Models\Penilaian;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashKonseling extends Component
{
    use WithPagination;

    public $nilaiPenilaian = [];
    public $perPage = 10;

    /**
     * Memilih bintang untuk penilaian
     */
    public function pilihBintang($id_order, $id_konselor, $nilai)
    {
        $penilaian = Penilaian::where('id_order', $id_order)
            ->where('id_konselor', $id_konselor)
            ->first();

        if ($penilaian) {
            // Cek apakah sudah lewat 3×24 jam
            if ($penilaian->created_at->lt(now()->subDays(3))) {
                session()->flash('error', 'Penilaian tidak dapat diubah setelah 3 hari.');
                return;
            }
        }

        Penilaian::updateOrCreate(
            [
                'id_order'    => $id_order,
                'id_konselor' => $id_konselor,
            ],
            [
                'nilai' => $nilai,
            ]
        );

        $this->nilaiPenilaian[$id_order] = $nilai;

        session()->flash('success', 'Terima kasih atas penilaian Anda.');
    }


    /**
     * Ambil rangkuman konsultasi
     */
    public function getRangkuman($id_order)
    {
        $hasil = HasilKonsultasi::where('id_order', $id_order)->first();
        return response()->json($hasil);
    }

    /**
     * Simpan rangkuman & saran konsultasi
     */
    public function simpanRangkuman(Request $request, $id_order)
    {
        $request->validate([
            'rangkuman' => 'required|string',
            'saran'     => 'required|string',
        ]);

        HasilKonsultasi::updateOrCreate(
            ['id_order' => $id_order],
            [
                'rangkuman' => $request->rangkuman,
                'saran'     => $request->saran,
            ]
        );

        // Notifikasi ke User
        Notif::create([
            'keterangan'   => 'Konsultasi Diselesaikan #' . $id_order . ', Terimakasih ',
            'id_order'     => $id_order,
            'role'         => 'USER',
            'id_penerima'  => $konseling->id_user ?? null,
            'status'       => 'terkirim',
        ]);

        // Notifikasi ke Admin
        Notif::create([
            'keterangan'   => 'Konsultasi Diselesaikan #' . $id_order,
            'id_order'     => $id_order,
            'role'         => 'ADMIN',
            'id_penerima'  => 1,
            'status'       => 'terkirim',
        ]);

        return back()->with('success', 'Rangkuman & Saran berhasil disimpan.');
    }

    /**
     * Update status konsultasi menjadi SELESAI
     */
    public function updateStatus($id)
    {
        $konseling = Order::findOrFail($id);

        if ($konseling->payment_status === 'LUNAS') {
            $konseling->payment_status = 'SELESAI';
            $konseling->save();
        }

        // Notifikasi ke User
        Notif::create([
            'keterangan'   => 'Konsultasi Diselesaikan #' . $konseling->id_order . ', Terimakasih ',
            'id_order'     => $konseling->id_order,
            'role'         => 'USER',
            'id_penerima'  => $konseling->id_user,
            'status'       => 'terkirim',
        ]);

        // Notifikasi ke Admin
        Notif::create([
            'keterangan'   => 'Konsultasi Diselesaikan #' . $konseling->id_order,
            'id_order'     => $konseling->id_order,
            'role'         => 'ADMIN',
            'id_penerima'  => 1,
            'status'       => 'terkirim',
        ]);

        return back()->with('success', 'Status berhasil diperbarui menjadi SELESAI.');
    }

    /**
     * Menampilkan pesan user di chat
     */
    public function showUserMessages($selectedUserId)
    {
        $authId = Auth::id();

        $messages = DB::table('ch_messages')
            ->where(function ($query) use ($authId, $selectedUserId) {
                $query->where('from_id', $authId)
                    ->where('to_id', $selectedUserId);
            })
            ->orWhere(function ($query) use ($authId, $selectedUserId) {
                $query->where('from_id', $selectedUserId)
                    ->where('to_id', $authId);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        $user = \App\Models\User::findOrFail($selectedUserId);

        return view('chatify.user-messages', compact('messages', 'user'));
    }

    /**
     * Render halaman
     */
    public function render()
    {
        $konselings = $this->getOrdersByRole(Auth::user()->role, Auth::id());

        // Ambil semua penilaian terkait order yang sedang ditampilkan
        $penilaianList = Penilaian::whereIn('id_order', $konselings->pluck('id_order'))->get();

        $this->nilaiPenilaian = [];
        $bisaUpdate = [];

        foreach ($konselings as $konseling) {
            $penilaian = $penilaianList->firstWhere('id_order', $konseling->id_order);

            if ($penilaian) {
                    $this->nilaiPenilaian[$konseling->id_order] = $penilaian->nilai;
                    $bisaUpdate[$konseling->id_order] = $penilaian->created_at->gte(now()->subDays(3));
                } else {
                    $this->nilaiPenilaian[$konseling->id_order] = 0;
                    $bisaUpdate[$konseling->id_order] = true;
                }

        }

        return view('livewire.dashboard.Konseling', [
            'konselings' => $konselings,
            'nilaiPenilaian' => $this->nilaiPenilaian,
            'bisaUpdate' => $bisaUpdate
        ]);
    }


    /**
     * Ambil data order berdasarkan role user
     */
    private function getOrdersByRole($role, $userId)
    {
        $konselings = Order::with([
            'user.detailUser.user',
            'konselor.detailUser.cabang',
        ]);

        switch ($role) {
            case 'KONSELOR':
                $konselings->where('id_konselor', $userId);
                break;
            case 'USER':
                $konselings->where('id_user', $userId);
                break;
            case 'CABANG':
                $konselings->whereHas('konselor.detailUser', function ($q) use ($userId) {
                    $q->where('id_cabang', $userId);
                });
                break;
        }

        return $konselings
            ->whereIn('orders.payment_status', ['LUNAS', 'SELESAI'])
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);
    }
}
