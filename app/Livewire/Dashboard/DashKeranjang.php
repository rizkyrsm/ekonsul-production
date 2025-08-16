<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Layanan;
use App\Models\Diskon;
use App\Models\User;
use App\Models\Order;
use App\Models\Notif;
use App\Models\ChMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class DashKeranjang extends Component
{
    public $total, $payment_status;
    public $layanans;
    public $voucher;
    public $potongan = 0;
    public $hargaSetelahDiskon = [];
    public $message;
    public $konseloroff;
    public $vouchernofalid;
    public $jenispotongan;
    public $konselors;
    public $konselor;
    public $konselorsBusy = [];


    public function mount($id = null)
    {
        $this->layanans = $id ? Layanan::where('id_layanan', $id)->get() : Layanan::all();
        $this->resetHarga();
        $this->total = array_sum($this->hargaSetelahDiskon);

        // Otomatis isi voucher jika ada yang aktif
        $activeVoucher = Diskon::where('status_aktiv', 'AKTIF')->first();
        if ($activeVoucher) {
            $this->voucher = $activeVoucher->kode_voucher;
            $this->applyVoucher();
        }
    }


    public function resetHarga()
    {
        foreach ($this->layanans as $layanan) {
            $this->hargaSetelahDiskon[$layanan->id_layanan] = $layanan->harga_layanan;
        }
    }

    public function applyVoucher()
    {
        $diskon = Diskon::where('kode_voucher', $this->voucher)
            ->where('status_aktiv', 'AKTIF')
            ->first();

        if ($diskon) {
            foreach ($this->layanans as $layanan) {
                $harga = $layanan->harga_layanan;

                if ($diskon->jumlah_diskon_harga) {
                    $potongan = $diskon->jumlah_diskon_harga;
                    $this->jenispotongan = 'Rp.' . $diskon->jumlah_diskon_harga;
                } elseif ($diskon->jumlah_diskon_persen) {
                    $potongan = ($harga * $diskon->jumlah_diskon_persen) / 100;
                    $this->jenispotongan = $diskon->jumlah_diskon_persen . '%';
                } else {
                    $potongan = 0;
                    $this->jenispotongan = '';
                }

                $this->hargaSetelahDiskon[$layanan->id_layanan] = max(0, $harga - $potongan);
                $this->message = 'Voucher berhasil diterapkan!';
                $this->vouchernofalid = $diskon->kode_voucher;
            }
        } else {
            $this->resetHarga();
            $this->message = 'Voucher tidak valid!';
            $this->vouchernofalid = 'tidak valid!';
            $this->jenispotongan = '';
            $this->voucher = '';
        }

        $this->total = array_sum($this->hargaSetelahDiskon);
    }

    public function saveOrder()
    {
        if (in_array($this->konselor, $this->konselorsBusy)) {
            session()->flash('konseloroff', 'Konselor ini sedang dalam sesi aktif. Silakan pilih konselor lain.');
            return;
        }

        if (!$this->total) {
            $this->total = array_sum($this->hargaSetelahDiskon);
        }

        $this->validate([
            'konselor' => 'required',
            'voucher' => 'nullable|string',
            'total' => 'required|numeric',
        ]);

        // ✅ Tambahan: Cek jika masih ada order dengan status LUNAS
        $hasLunasOrder = Order::where('id_user', Auth::id())
            ->where('payment_status', 'LUNAS')
            ->exists();

        if ($hasLunasOrder) {
            session()->flash('message', 'Masih ada sesi konseling aktif. Selesaikan dulu sebelum memesan layanan baru.');
            return redirect()->route('konseling');
        }

        try {
            $namaLayanan = implode(', ', array_column($this->layanans->toArray(), 'nama_layanan'));

            // Cek jika sudah ada order BELUM BAYAR untuk layanan & konselor yang sama
            $existingOrder = Order::where('id_user', Auth::id())
                ->where('id_konselor', $this->konselor)
                ->where('nama_layanan', $namaLayanan)
                ->where('payment_status', 'BELUM BAYAR')
                ->orderByDesc('id_order')
                ->first();

            if ($existingOrder) {
                session()->flash('message', 'Order sudah dibuat. Silakan upload bukti bayar dan tunggu konfirmasi dari admin.');
                return redirect()->route('orders');
            }

            $paymentStatus = $this->total == 0 ? 'LUNAS' : 'BELUM BAYAR';

            $order = Order::create([
                'id_user' => Auth::id(),
                'id_konselor' => $this->konselor,
                'nama_layanan' => $namaLayanan,
                'voucher' => $this->voucher,
                'total' => $this->total,
                'payment_status' => $paymentStatus,
            ]);

            $idCabang = DB::table('detail_users')
                ->where('id_user', $this->konselor)
                ->value('id_cabang');

            $nama = auth()->user()->name;

            // Kirim notifikasi
            Notif::insert([
                [
                    'keterangan' => 'Order baru telah dibuat oleh ' . $nama,
                    'id_order' => $order->id_order,
                    'role' => 'ADMIN',
                    'id_penerima' => 1,
                    'status' => 'terkirim',
                ],
                [
                    'keterangan' => 'Order baru telah dibuat oleh ' . $nama,
                    'id_order' => $order->id_order,
                    'role' => 'CABANG',
                    'id_penerima' => $idCabang,
                    'status' => 'terkirim',
                ],
                [
                    'keterangan' => 'Siap" Memulai Konseling Order baru telah dibuat ' . $nama,
                    'id_order' => $order->id_order,
                    'role' => 'KONSELOR',
                    'id_penerima' => $this->konselor,
                    'status' => 'terkirim',
                ],
            ]);

            // Kirim pesan otomatis jika langsung LUNAS
            if ($paymentStatus == 'LUNAS') {
                ChMessage::create([
                    'id' => (string) Str::uuid(),
                    'from_id' => $order->id_konselor,
                    'to_id' => $order->id_user,
                    'body' => "Halo!,
                                Selamat datang di e-Konsul PKBI Jatim.
                                Sesi konsultasi berdurasi maks. 30 menit.
                                Jika tidak ada balasan selama 30 menit, sesi akan otomatis berakhir.
                                Ada yang bisa kami bantu?",
                    'attachment' => null,
                    'id_order' => $order->id_order,
                    'seen' => 0,
                ]);
            }

            if ($this->total == 0) {
                session()->flash('message', 'Pesanan berhasil disimpan dan status langsung LUNAS. Anda dapat memulai konseling.');
                return redirect()->route('konseling');
            } else {
                session()->flash('message', 'Pesanan berhasil disimpan, Silakan lakukan upload bukti pembayaran jika sudah melakukan pembayaran.');
                return redirect()->route('orders');
            }
        } catch (\Exception $e) {
            session()->flash('message', 'Gagal menyimpan pesanan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $now = now('Asia/Jakarta');
        $jamSekarang = $now->format('H:i');
        $batasJam21 = $now->copy()->setTime(21, 0, 0);

        // Nonaktifkan konselor yang belum update setelah jam 21:00
        if ($jamSekarang >= '21:00') {
            User::where('role', 'KONSELOR')
                ->where('status', 'ACTIVE')
                ->where(function ($query) use ($batasJam21) {
                    $query->whereNull('updated_at')
                        ->orWhere('updated_at', '<', $batasJam21);
                })
                ->update(['status' => 'NONACTIVE']);
        }

        // Ambil konselor yang masih aktif
        $this->konselors = User::select('users.*', 'detail_users.*', 'cabang.name as cabang_name')
            ->join('detail_users', 'users.id', '=', 'detail_users.id_user')
            ->leftJoin('users as cabang', 'detail_users.id_cabang', '=', 'cabang.id')
            ->where('users.role', 'KONSELOR')
            ->where('users.status', 'ACTIVE')
            ->where('detail_users.status_online', 'online')
            ->get();

        // Cari ID konselor yang sedang punya order LUNAS
        $this->konselorsBusy = Order::where('payment_status', 'LUNAS')
            ->pluck('id_konselor')
            ->unique()
            ->toArray();

        // Jika tidak ada konselor aktif setelah jam 21:00, kirim pesan
        if ($jamSekarang >= '21:00' && $this->konselors->isEmpty()) {
            session()->flash('konseloroff', 'Saat ini tidak ada konselor yang aktif. Silakan kembali lagi nanti atau hubungi info pengaduan.');
        }

        return view('Keranjang', [
            'konselors' => $this->konselors,
            'konselorsBusy' => $this->konselorsBusy,
        ]);
    }
}
