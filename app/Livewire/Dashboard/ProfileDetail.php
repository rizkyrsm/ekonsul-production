<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\DetailUser;

class ProfileDetail extends Component
{
    public $nama, $nik, $tgl_lahir, $alamat, $no_tlp;
    public $status_online = 'online'; // default nilai awal
    public $jenis_kelamin, $tempat_lahir, $status_pernikahan, $agama, $pekerjaan;

    public function mount()
    {
        $data = DetailUser::where('id_user', Auth::id())->first();

        if ($data) {
            $this->nama = $data->nama;
            $this->nik = $data->nik;
            $this->tgl_lahir = $data->tgl_lahir;
            $this->tempat_lahir = $data->tempat_lahir;
            $this->alamat = $data->alamat;
            $this->no_tlp = $data->no_tlp;
            $this->status_online = $data->status_online ?? 'online';
            $this->jenis_kelamin = $data->jenis_kelamin;
            $this->status_pernikahan = $data->status_pernikahan;
            $this->agama = $data->agama;
            $this->pekerjaan = $data->pekerjaan;
        } else {
            // Jika data belum ada, set default
            $this->status_online = 'online';
        }
    }

    public function updatedNik($value)
    {
        $this->nik = preg_replace('/\D/', '', $value);
    }

    public function updatedNoTlp($value)
    {
        $this->no_tlp = preg_replace('/\D/', '', $value);
    }

    public function save()
    {
        $this->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|numeric',
            'tgl_lahir' => 'required|date',
            'tempat_lahir' => 'required|string|max:255',
            'alamat' => 'required|string|max:1000',
            'no_tlp' => 'required|numeric',
            'status_online' => 'nullable|in:online,offline',
            'jenis_kelamin' => 'required|in:LAKI-LAKI,PEREMPUAN,LAINYA',
            'status_pernikahan' => 'required|in:MENIKAH,BELUM MENIKAH,TIDAK MENIKAH',
            'agama' => 'required|string|max:50',
            'pekerjaan' => 'required|string|max:255',
        ]);

        DetailUser::updateOrCreate(
            ['id_user' => Auth::id()],
            [
                'nama' => $this->nama,
                'nik' => $this->nik,
                'tgl_lahir' => $this->tgl_lahir,
                'tempat_lahir' => $this->tempat_lahir,
                'alamat' => $this->alamat,
                'no_tlp' => $this->no_tlp,
                'status_online' => $this->status_online ?: 'online',
                'jenis_kelamin' => $this->jenis_kelamin,
                'status_pernikahan' => $this->status_pernikahan,
                'agama' => $this->agama,
                'pekerjaan' => $this->pekerjaan,
            ]
        );

        return redirect()->route('dashboard')->with('success', 'Profil detail berhasil disimpan!');
    }

    public function render()
    {
        return view('livewire.settings.profile-detail');
    }
}
