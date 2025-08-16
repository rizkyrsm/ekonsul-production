<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\ChMessage;

class MessageNotif extends Component
{
    public $userId;
    public $orderId;
    public $onclickFunction;
    public $count = 0;

    public function mount($userId, $orderId, $onclickFunction = 'checkProfileAndStartChat')
    {
        $this->userId = $userId;
        $this->orderId = $orderId;
        $this->onclickFunction = $onclickFunction;
        $this->updateCount();
    }

    public function updateCount()
    {
        $this->count = ChMessage::where('to_id', Auth::id())
            ->where('from_id', $this->userId)
            ->where('id_order', $this->orderId)
            ->where('seen', 0)
            ->count();
    }

    public function render()
    {
        $this->updateCount();

        return view('livewire.message-notif');
    }
}
