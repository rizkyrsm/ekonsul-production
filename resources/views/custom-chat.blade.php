<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Obrolan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="p-4">
        <div class="bg-white p-4 rounded shadow max-h-[550px] overflow-y-auto space-y-4">
            @php
                $messages = \DB::table('ch_messages')
                    ->where(function ($query) use ($from_id, $to_id, $id_order) {
                        $query->where('from_id', $from_id)
                              ->where('to_id', $to_id)
                              ->where('id_order', $id_order);
                    })
                    ->orWhere(function ($query) use ($from_id, $to_id, $id_order) {
                        $query->where('from_id', $to_id)
                              ->where('to_id', $from_id)
                              ->where('id_order', $id_order);
                    })
                    ->orderBy('created_at', 'asc')
                    ->get();

                $users = \App\Models\User::whereIn('id', [$from_id, $to_id])
                    ->pluck('name', 'id')
                    ->toArray();
            @endphp

            @forelse ($messages as $msg)
                <div class="flex {{ $msg->from_id == $from_id ? 'justify-end' : 'justify-start' }}">
                    <div class="flex items-end space-x-2 {{ $msg->from_id == $from_id ? 'flex-row-reverse' : 'flex-row' }}">
                        {{-- Pesan dan Nama --}}
                        <div class="max-w-[100%] px-4 py-2 rounded-lg text-white {{ $msg->from_id == $from_id ? 'bg-blue-500' : 'bg-red-400' }}">
                            <div>
                                {{ $msg->body }}
                            </div>
                            <div class="flex justify-between items-center mt-2 text-xs text-gray-100">
                                <div class="flex items-left text-gray-100 font-semibold">
                                    {{ $users[$msg->from_id] ?? 'User Tidak Dikenal' }}
                                </div>
                                <div>
                                    &nbsp;&nbsp;{{ \Carbon\Carbon::parse($msg->created_at)->format('d M Y H:i') }}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                @if (!empty($msg->attachment))
                    <div class="mt-2">
                        @php
                            $attachmentPath = asset('public/FileMessage/' . $msg->attachment);
                            $ext = pathinfo($msg->attachment, PATHINFO_EXTENSION);
                        @endphp

                        @if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                            <img src="{{ $attachmentPath }}" alt="attachment" class="mt-1 rounded max-w-[250px]" onclick="event.preventDefault(); window.open('{{ $attachmentPath }}', '_blank');">
                        @else
                            <a href="{{ $attachmentPath }}" target="_blank" class="underline text-sm text-white">
                                📎 Lihat Lampiran
                            </a>
                        @endif
                    </div>
                @endif

            @empty
                <p class="text-gray-500">Belum ada pesan.</p>
            @endforelse
        </div>
    </div>
</body>
</html>
