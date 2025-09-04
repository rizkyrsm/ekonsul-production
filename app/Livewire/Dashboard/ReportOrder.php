<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class ReportOrder extends Component
{
    use WithPagination;

    public $start_date;
    public $end_date;

    protected $paginationTheme = 'tailwind';

    public function mount()
    {
        $this->start_date = now()->startOfMonth()->toDateString();
        $this->end_date   = now()->endOfMonth()->toDateString();
    }

    public function updatingStartDate() { $this->resetPage(); }
    public function updatingEndDate()   { $this->resetPage(); }

    public function search()
    {
        $this->resetPage();
    }

    public function exportExcel()
    {
        $fileName = "report_order_" . now()->format('Ymd_His') . ".xls";

        $user = auth()->user();

        $orders = DB::table('orders as o')
            ->join('users as u', 'o.id_user', '=', 'u.id')
            ->join('detail_users as du', 'du.id_user', '=', 'u.id')
            ->join('users as kons', 'o.id_konselor', '=', 'kons.id')
            ->join('detail_users as dk', 'dk.id_user', '=', 'kons.id') // ambil id_cabang dari detail_user konselor
            ->join('layanans as l', 'o.nama_layanan', '=', 'l.nama_layanan')
            ->select(
                'o.id_user',
                'o.id_order',
                'du.nama as nama_user',
                'du.nik',
                'du.tgl_lahir',
                'du.tempat_lahir',
                'du.alamat',
                'du.no_tlp',
                'du.status_online',
                'du.jenis_kelamin',
                'du.status_pernikahan',
                'du.agama',
                'du.pekerjaan',
                'kons.name as nama_konselor',
                'l.nama_layanan',
                'o.created_at as mulai_konseling',
                'o.updated_at as selesai_konseling'
            )
            ->where('o.payment_status', 'SELESAI')
            ->whereBetween('o.created_at', [
                $this->start_date . ' 00:00:00',
                $this->end_date   . ' 23:59:59'
            ]);

        // 🔹 Filter sesuai role login
        if ($user->role === 'CABANG') {
            $orders->where('dk.id_cabang', $user->id_cabang);
        } elseif ($user->role === 'KONSELOR') {
            $orders->where('o.id_konselor', $user->id);
        }

        $orders = $orders->orderBy('o.created_at', 'desc')->get();

        $headers = [
            "Content-type"        => "application/vnd.ms-excel",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Expires"             => "0"
        ];

        $content = '<table border="1">
            <thead>
                <tr>
                    <th>ID Order</th><th>Nama User</th><th>NIK</th><th>Tgl Lahir</th>
                    <th>Tempat Lahir</th><th>Alamat</th><th>No Tlp</th><th>Status Online</th>
                    <th>Jenis Kelamin</th><th>Status Pernikahan</th><th>Agama</th><th>Pekerjaan</th>
                    <th>Nama Konselor</th><th>Nama Layanan</th>
                    <th>Waktu Mulai</th><th>Waktu Selesai</th>
                </tr>
            </thead><tbody>';

        foreach ($orders as $row) {
            $content .= '<tr>
                <td>'.$row->id_order.'</td>
                <td>'.$row->nama_user.'</td>
                <td>'.$row->nik.'</td>
                <td>'.$row->tgl_lahir.'</td>
                <td>'.$row->tempat_lahir.'</td>
                <td>'.$row->alamat.'</td>
                <td>'.$row->no_tlp.'</td>
                <td>'.$row->status_online.'</td>
                <td>'.$row->jenis_kelamin.'</td>
                <td>'.$row->status_pernikahan.'</td>
                <td>'.$row->agama.'</td>
                <td>'.$row->pekerjaan.'</td>
                <td>'.$row->nama_konselor.'</td>
                <td>'.$row->nama_layanan.'</td>
                <td>'.$row->mulai_konseling.'</td>
                <td>'.$row->selesai_konseling.'</td>
            </tr>';
        }

        $content .= '</tbody></table>';

        return response($content, 200, $headers);
    }

    public function render()
    {
        $user = auth()->user();

        $orders = DB::table('orders as o')
            ->join('users as u', 'o.id_user', '=', 'u.id')
            ->join('detail_users as du', 'du.id_user', '=', 'u.id')
            ->join('users as kons', 'o.id_konselor', '=', 'kons.id')
            ->join('detail_users as dk', 'dk.id_user', '=', 'kons.id')
            ->join('layanans as l', 'o.nama_layanan', '=', 'l.nama_layanan')
            ->select(
                'o.id_user',
                'o.id_order',
                'du.nama as nama_user',
                'du.nik',
                'du.tgl_lahir',
                'du.tempat_lahir',
                'du.alamat',
                'du.no_tlp',
                'du.status_online',
                'du.jenis_kelamin',
                'du.status_pernikahan',
                'du.agama',
                'du.pekerjaan',
                'kons.name as nama_konselor',
                'l.nama_layanan',
                'o.created_at as mulai_konseling',
                'o.updated_at as selesai_konseling'
            )
            ->where('o.payment_status', 'SELESAI')
            ->whereBetween('o.created_at', [
                $this->start_date . ' 00:00:00',
                $this->end_date   . ' 23:59:59'
            ]);

        if ($user->role === 'CABANG') {
            $orders->where('dk.id_cabang', $user->id_cabang);
        } elseif ($user->role === 'KONSELOR') {
            $orders->where('o.id_konselor', $user->id);
        }

        $orders = $orders->orderBy('o.created_at', 'desc')->paginate(10);

        return view('livewire.dashboard.report-order', compact('orders'));
    }
}
