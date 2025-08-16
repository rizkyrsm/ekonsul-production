<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChMessage;
use App\Models\User;

class UserMessageController extends Controller
{
    public function index($user_id)
    {
        // Ambil data user
        $user = User::findOrFail($user_id);

        // Ambil pesan berdasarkan from_id atau to_id
        $messages = ChMessage::where('from_id', $user_id)
            ->orWhere('to_id', $user_id)
            ->orderBy('id_order')
            ->orderBy('created_at')
            ->get();

        // Kelompokkan pesan berdasarkan id_order
        $grouped = $messages->groupBy('id_order');

        return view('allChat', compact('user', 'grouped'));
    }
}

?>