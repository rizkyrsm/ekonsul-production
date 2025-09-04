<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportOrderExportController extends Controller
{
    public function export(Request $request)
    {
        $fileName = "report_order_" . now()->format('Ymd_His') . ".xls";
        $user = Auth::user();

        $start_date = $request->get('start_date', now()->startOfMonth()->toDateString());
        $end_date   = $request->get('end_date', now()->endOfMonth()->toDateString());

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
            ->whereBetween('o.created_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);

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
                    <th>Konselor</th><th>Layanan</th>
                    <th>Mulai Konseling</th><th>Selesai Konseling</th><th>History</th>
                </tr>
            </thead><tbody>';

        foreach ($orders as $row) {
            $content .= '<tr>
                <td>' . $row->id_order . '</td>
                <td>' . $row->nama_user . '</td>
                <td>' . "'" . $row->nik . '</td>
                <td>' . ($row->tgl_lahir ? date('d-m-Y', strtotime($row->tgl_lahir)) : '-') . '</td>
                <td>' . $row->tempat_lahir . '</td>
                <td>' . $row->alamat . '</td>
                <td>' . "'" . $row->no_tlp . '</td>
                <td>' . $row->status_online . '</td>
                <td>' . $row->jenis_kelamin . '</td>
                <td>' . $row->status_pernikahan . '</td>
                <td>' . $row->agama . '</td>
                <td>' . $row->pekerjaan . '</td>
                <td>' . $row->nama_konselor . '</td>
                <td>' . $row->nama_layanan . '</td>
                <td>' . date('d-m-Y H:i', strtotime($row->mulai_konseling)) . '</td>
                <td>' . date('d-m-Y H:i', strtotime($row->selesai_konseling)) . '</td>
                <td>' ."https://ekonsul.pkbi-jatim.or.id/allChat/" . $row->id_user .'</td>
            </tr>';
        }

        $content .= '</tbody></table>';

        return response($content, 200, $headers);
    }
}
