<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Diskon;

class DiskonCreate extends Component
{
    public $diskonId;
    public $nama_diskon;
    public $kode_voucher;
    public $jumlah_diskon_harga;
    public $jumlah_diskon_persen;
    public $status_aktiv = 'AKTIF';

    public $search = '';
    public $updateMode = false;

    public function render()
    {
        $diskons = Diskon::query()
            ->where('nama_diskon', 'like', '%' . $this->search . '%')
            ->orWhere('kode_voucher', 'like', '%' . $this->search . '%')
            ->orderByDesc('id_diskon')
            ->get();

        return view('livewire.dashboard.diskon-create', [
            'diskons' => $diskons,
        ]);
    }

    public function resetForm()
    {
        $this->diskonId = null;
        $this->nama_diskon = '';
        $this->kode_voucher = '';
        $this->jumlah_diskon_harga = '';
        $this->jumlah_diskon_persen = '';
        $this->status_aktiv = 'AKTIF';
        $this->updateMode = false;
    }

    public function store()
    {
        $this->validate([
            'nama_diskon' => 'required|string',
            'kode_voucher' => 'required|string|unique:diskons,kode_voucher',
            'status_aktiv' => 'required|string',
        ]);

        if ($this->jumlah_diskon_harga && $this->jumlah_diskon_persen) {
            $this->addError('jumlah_diskon_harga', 'Pilih hanya salah satu jenis diskon.');
            $this->addError('jumlah_diskon_persen', 'Pilih hanya salah satu jenis diskon.');
            return;
        }

        Diskon::create([
            'nama_diskon' => $this->nama_diskon,
            'kode_voucher' => $this->kode_voucher,
            'jumlah_diskon_harga' => $this->jumlah_diskon_harga ?: null,
            'jumlah_diskon_persen' => $this->jumlah_diskon_persen ?: null,
            'status_aktiv' => $this->status_aktiv,
        ]);

        session()->flash('message', 'Diskon berhasil ditambahkan.');
        $this->resetForm();
    }

    public function edit($id)
    {
        $diskon = Diskon::findOrFail($id);

        $this->diskonId = $diskon->id_diskon;
        $this->nama_diskon = $diskon->nama_diskon;
        $this->kode_voucher = $diskon->kode_voucher;
        $this->jumlah_diskon_harga = $diskon->jumlah_diskon_harga;
        $this->jumlah_diskon_persen = $diskon->jumlah_diskon_persen;
        $this->status_aktiv = $diskon->status_aktiv;
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
            'nama_diskon' => 'required|string',
            'kode_voucher' => 'required|string|unique:diskons,kode_voucher,' . $this->diskonId . ',id_diskon',
            'status_aktiv' => 'required|string',
        ]);

        if ($this->jumlah_diskon_harga && $this->jumlah_diskon_persen) {
            $this->addError('jumlah_diskon_harga', 'Pilih hanya salah satu jenis diskon.');
            $this->addError('jumlah_diskon_persen', 'Pilih hanya salah satu jenis diskon.');
            return;
        }

        $diskon = Diskon::findOrFail($this->diskonId);

        $diskon->update([
            'nama_diskon' => $this->nama_diskon,
            'kode_voucher' => $this->kode_voucher,
            'jumlah_diskon_harga' => $this->jumlah_diskon_harga ?: null,
            'jumlah_diskon_persen' => $this->jumlah_diskon_persen ?: null,
            'status_aktiv' => $this->status_aktiv,
        ]);

        session()->flash('message', 'Diskon berhasil diperbarui.');
        $this->resetForm();
    }

    public function delete($id)
    {
        Diskon::find($id)?->delete();
        session()->flash('message', 'Diskon berhasil dihapus.');
        $this->resetForm();
    }
}
