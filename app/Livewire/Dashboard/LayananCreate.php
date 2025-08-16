<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Layanan;

class LayananCreate extends Component
{
    public $layananId;
    public $nama_layanan;
    public $warna_layanan;
    public $harga_layanan;
    public $search = '';

    public $updateMode = false;
    public $confirmingDeleteId = null;

    protected $listeners = ['deleteConfirmed'];

    public function render()
    {
        $layanans = Layanan::query()
            ->where('nama_layanan', 'like', '%' . $this->search . '%')
            ->orWhere('warna_layanan', 'like', '%' . $this->search . '%')
            ->orderByDesc('id_layanan')
            ->get();

        return view('livewire.dashboard.layanan-create', [
            'layanans' => $layanans,
        ]);
    }

    public function resetForm()
    {
        $this->layananId = null;
        $this->nama_layanan = '';
        $this->warna_layanan = '';
        $this->harga_layanan = '';
        $this->updateMode = false;
    }

    public function store()
    {
        $this->validate([
            'nama_layanan' => 'required|string',
            'warna_layanan' => 'required|string',
            'harga_layanan' => 'required|numeric',
        ]);

        Layanan::create([
            'nama_layanan' => $this->nama_layanan,
            'warna_layanan' => $this->warna_layanan,
            'harga_layanan' => $this->harga_layanan,
        ]);

        session()->flash('message', 'Layanan berhasil ditambahkan.');
        $this->resetForm();
    }

    public function edit($id)
    {
        $layanan = Layanan::findOrFail($id);

        $this->layananId = $layanan->id_layanan;
        $this->nama_layanan = $layanan->nama_layanan;
        $this->warna_layanan = $layanan->warna_layanan;
        $this->harga_layanan = $layanan->harga_layanan;
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
            'nama_layanan' => 'required|string',
            'warna_layanan' => 'required|string',
            'harga_layanan' => 'required|numeric',
        ]);

        $layanan = Layanan::findOrFail($this->layananId);

        $layanan->update([
            'nama_layanan' => $this->nama_layanan,
            'warna_layanan' => $this->warna_layanan,
            'harga_layanan' => $this->harga_layanan,
        ]);

        session()->flash('message', 'Layanan berhasil diperbarui.');
        $this->resetForm();
    }

    public function delete($id)
    {
        Layanan::find($id)->delete();
        session()->flash('message', 'Layanan berhasil dihapus.');
        $this->resetForm();
    }
}
