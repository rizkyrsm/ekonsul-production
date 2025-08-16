<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\ChMessage;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use Carbon\Carbon;


class ExtendedMessageController extends Controller
{

    public function autoFinishOrder(Request $request)
    {
        $orderId = $request->input('id_order');
        $order = \App\Models\Order::where('id_order', $orderId)->first();

        if (!$order) {
            return response()->json(['status' => 'not_found'], 404);
        }

        $order->payment_status = 'SELESAI';
        $order->save();

        // Cek apakah pesan akhir otomatis sudah dikirim sebelumnya
        $alreadySent = \App\Models\ChMessage::where('id_order', $orderId)
            ->where('body', 'like', '%percakapan ini akan diakhiri secara otomatis%')
            ->exists();

        if (!$alreadySent) {
            // Ambil waktu pesan pertama
            $firstMessage = \App\Models\ChMessage::where('id_order', $orderId)
                ->orderBy('created_at', 'asc')
                ->first();

            $autoMessageTime = now(); // default jika pesan pertama tidak ditemukan
            if ($firstMessage) {
                $autoMessageTime = \Carbon\Carbon::parse($firstMessage->created_at)->addMinutes(30);
            }

            \App\Models\ChMessage::create([
                'id' => (string) Str::uuid(),
                'from_id' => $order->id_konselor,
                'to_id' => $order->id_user,
                'body' => 'Mohon maaf, karena Konselor tidak menerima respon dari Anda, dengan sangat menyesal percakapan ini akan diakhiri secara otomatis. Silahkan bisa memulai mendaftar dengan memilih layanan yang dibutuhkan. Terimakasih.',
                'id_order' => $orderId,
                'seen' => 0,
                'created_at' => $autoMessageTime,
            ]);

            // Kirim notifikasi
            \App\Models\Notif::insert([
                [
                    'keterangan' => 'Sesi konseling di selesaikan otomatis ID:#' . $order->id_order,
                    'id_order' => $order->id_order,
                    'role' => 'USER',
                    'id_penerima' => $order->id_user,
                    'status' => 'terkirim',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'keterangan' => 'Sesi konseling di selesaikan otomatis ID:#' . $order->id_order,
                    'id_order' => $order->id_order,
                    'role' => 'KONSELOR',
                    'id_penerima' => $order->id_konselor,
                    'status' => 'terkirim',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

        return response()->json(['status' => 'updated']);
    }



    public function send(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'message' => 'nullable|string',
            'id_order' => 'nullable|integer',
            'file' => 'nullable|file|max:10240', // max 10MB
        ]);

        $fromId = Auth::id();
        $toId = $request->input('id');
        $messageBody = trim($request->input('message'));
        $idOrder = $request->input('id_order');
        $newName = null;

        // Upload file jika ada
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $newName = Str::uuid() . '.' . $file->getClientOriginalExtension();

            // Simpan ke public/dataupload
            $destinationPath = public_path('FileMessage');
            $file->move($destinationPath, $newName);
        }

        // Simpan pesan ke database
        $message = ChMessage::create([
            'id' => (string) Str::uuid(),
            'from_id' => $fromId,
            'to_id' => $toId,
            'body' => $messageBody,
            'attachment' => $newName,
            'id_order' => $idOrder,
            'seen' => 0,
        ]);

        // Render pesan untuk pengirim
        $renderedMessageSender = view('vendor.Chatify.layouts.messageCard', [
            'id' => $message->id,
            'id_order' => $message->id_order,
            'fromId' => $message->from_id,
            'toId' => $message->to_id,
            'message' => $message->body,
            'attachment' => $message->attachment,
            'created_at' => $message->created_at,
            'timeAgo' => $message->created_at->diffForHumans(),
            'isSender' => true,
            'seen' => 0,
        ])->render();

        // Render pesan untuk penerima
        $renderedMessageReceiver = view('vendor.Chatify.layouts.messageCard', [
            'id' => $message->id,
            'id_order' => $message->id_order,
            'fromId' => $message->from_id,
            'toId' => $message->to_id,
            'message' => $message->body,
            'attachment' => $message->attachment,
            'created_at' => $message->created_at,
            'timeAgo' => $message->created_at->diffForHumans(),
            'isSender' => false,
            'seen' => 0,
        ])->render();

        // Kirim realtime via Pusher
        try {
            $pusher = new \Pusher\Pusher(
                config('broadcasting.connections.pusher.key'),
                config('broadcasting.connections.pusher.secret'),
                config('broadcasting.connections.pusher.app_id'),
                config('broadcasting.connections.pusher.options')
            );

            $pusher->trigger("private-chatify.{$toId}", 'messaging', [
                'from_id' => $fromId,
                'to_id' => $toId,
                'message' => $renderedMessageReceiver,
            ]);
        } catch (\Exception $e) {
            Log::error("Pusher trigger failed: " . $e->getMessage());
        }

        return response()->json([
            'status' => 'success',
            'message' => $renderedMessageSender,
            'tempID' => $request->input('temporaryMsgId'),
        ]);
    }

    public function fetch(Request $request)
    {
        $userId = Auth::id();
        $contactId = $request->input('id');
        $idOrder = $request->input('id_order');

        if (!$contactId || !$idOrder) {
            return response()->json(['messages' => '']);
        }

        $messages = ChMessage::where('id_order', $idOrder)
            ->where(function ($q) use ($userId, $contactId) {
                $q->where(function ($query) use ($userId, $contactId) {
                    $query->where('from_id', $userId)->where('to_id', $contactId);
                })->orWhere(function ($query) use ($userId, $contactId) {
                    $query->where('from_id', $contactId)->where('to_id', $userId);
                });
            })
            ->orderBy('created_at')
            ->get();

        $response = '';
        foreach ($messages as $msg) {
            $response .= view('vendor.Chatify.layouts.messageCard', [
                'id' => $msg->id,
                'id_order' => $msg->id_order,
                'fromId' => $msg->from_id,
                'toId' => $msg->to_id,
                'message' => $msg->body,
                'attachment' => $msg->attachment,
                'created_at' => $msg->created_at,
                'timeAgo' => $msg->created_at->diffForHumans(),
                'isSender' => $msg->from_id == $userId,
                'seen' => $msg->seen,
            ])->render();
        }

        return response()->json(['messages' => $response]);
    }
}
