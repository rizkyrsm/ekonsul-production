<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Persetujuan;
use Illuminate\Support\Facades\Auth;

class TermsPopup extends Component
{
    public $showPopup = false;
    public $confirmed = false; // ✅ untuk checkbox

    public function mount()
    {
        $user = Auth::user();

        $persetujuan = Persetujuan::where('user_id', $user->id)->first();

        $this->showPopup = !$persetujuan || !$persetujuan->is_agreed;
    }

    public function agree()
    {
        if (!$this->confirmed) {
            return; // kalau belum centang, tidak lanjut
        }

        $user = Auth::user();

        Persetujuan::updateOrCreate(
            ['user_id' => $user->id],
            ['is_agreed' => true, 'agreed_at' => now()]
        );

        $this->showPopup = false;
        session()->flash('message', 'Terima kasih, Anda telah menyetujui syarat dan ketentuan.');
    }

    public function render()
    {
        return view('livewire.terms-popup');
    }
}
