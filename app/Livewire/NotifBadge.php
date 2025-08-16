<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Notif;
use Illuminate\Support\Facades\Auth;

class NotifBadge extends Component
{
    public $count = 0;

    public function mount()
    {
        $this->updateCount();
    }

    public function updateCount()
    {
        $this->count = Notif::where('id_penerima', Auth::id())
            ->where('status', 'terkirim')
            ->count();
    }

    public function markAllAsRead()
    {
        Notif::where('id_penerima', Auth::id())
            ->where('status', 'terkirim')
            ->update(['status' => 'terbaca']);

        $this->updateCount();
    }

    public function render()
    {
        $this->updateCount();

        return view('livewire.notif-badge', [
            'notifs' => Notif::where('id_penerima', Auth::id())
                ->where('status', 'terkirim')
                ->latest()->limit(10)->get(),
            'allnotifs' => Notif::where('id_penerima', Auth::id())
                ->where('status', 'terbaca')
                ->latest()->limit(10)->get(),
        ]);
    }
}
