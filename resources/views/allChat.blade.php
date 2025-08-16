<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pesan dari {{ $user->name }}</title>

    <!-- Bootstrap 5.3.3 via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        .order-box {
            max-height: 550px;
            overflow-y: auto;
            padding: 1rem;
            background-color: #ecff1b;
            border-radius: 0.75rem;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }
        
        .chat-box {
            max-height: 550px;
            overflow-y: auto;
            padding: 1rem;
            background-color: #fff;
            border-radius: 0.75rem;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }

        .chat-bubble {
            padding: 0.75rem 1rem;
            max-width: 75%;
            word-break: break-word;
            border-radius: 1rem;
            color: #fff;
        }

        .chat-left {
            background-color: #dc3545; /* Red bubble */
            border-bottom-left-radius: 0;
        }

        .chat-right {
            background-color: #0d6efd; /* Blue bubble */
            border-bottom-right-radius: 0;
        }

        .chat-img {
            max-width: 250px;
            border-radius: 0.5rem;
            margin-top: 0.5rem;
            cursor: pointer;
        }

        .chat-meta {
            font-size: 0.75rem;
            margin-top: 0.5rem;
            color: #e0e0e0;
        }

        .attachment-link {
            font-size: 0.9rem;
            color: #fff;
            text-decoration: underline;
        }

        .attachment-link:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body class="bg-light py-4">

    <div class="container">
        <h2 class="mb-4 text-center">Pesan dari: {{ $user->name }}</h2>

        @forelse($grouped as $id_order => $messages)
            <div class="mb-5">
                <div class="bg-white border rounded shadow-sm p-3 mb-3">
                    <h5 class="mb-0">🧾 Sesi Konsultasi ID Order: {{ $id_order }}</h5>
                </div>

                {{-- Tampilkan rangkuman & saran jika ada --}}
                @php
                    $hasil = \App\Models\HasilKonsultasi::where('id_order', $id_order)->first();
                @endphp

                @if($hasil)
                    <div class="order-box">
                        <h6 class="font-bold text-lg mb-2">📄 Rangkuman</h6>
                        <p class="whitespace-pre-line mb-3">{{ $hasil->rangkuman }}</p>

                        <h6 class="font-bold text-lg mb-2">💡 Saran</h6>
                        <p class="whitespace-pre-line">{{ $hasil->saran }}</p>
                    </div>
                @else
                    <div class="order-box">
                        Belum ada rangkuman & saran.
                    </div>
                @endif


                <div class="chat-box">
                    @foreach ($messages as $msg)
                        @php
                            $isSender = $msg->from_id == $user->id;
                            $sender = \App\Models\User::find($msg->from_id);
                            $isImage = $msg->attachment && \Illuminate\Support\Str::endsWith($msg->attachment, ['.png', '.jpg', '.jpeg', '.gif', '.webp']);
                            $statusIcon = $msg->seen ? '✅' : '⏳';
                            $attachmentPath = asset('public/FileMessage/' . $msg->attachment);
                            $ext = pathinfo($msg->attachment, PATHINFO_EXTENSION);
                        @endphp

                        <div class="d-flex mb-3 {{ $isSender ? 'justify-content-end' : 'justify-content-start' }}">
                            <div class="chat-bubble {{ $isSender ? 'chat-right' : 'chat-left' }}">
                                {{-- Teks Pesan --}}
                                @if ($msg->body)
                                    <div>{{ $msg->body }}</div>
                                @endif

                                {{-- Lampiran --}}
                                @if ($msg->attachment)
                                    @if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                        <img src="{{ $attachmentPath }}" alt="Gambar" class="chat-img" onclick="window.open('{{ $attachmentPath }}', '_blank');">
                                    @else
                                        <div class="mt-2">
                                            <a href="{{ $attachmentPath }}" target="_blank" class="attachment-link">📎 Lihat Lampiran</a>
                                        </div>
                                    @endif
                                @endif

                                {{-- Info Pengirim dan Waktu --}}
                                <div class="chat-meta d-flex justify-content-between">
                                    <div><strong>{{ $isSender ? $user->name : ($sender->name ?? 'Unknown') }}</strong></div>
                                    <div>&nbsp;&nbsp;&nbsp;&nbsp;{{ $msg->created_at->format('d M Y, H:i') }} @if ($isSender) <span class="ms-1">{{ $statusIcon }}</span> @endif</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <p class="text-muted text-center">Tidak ada pesan ditemukan.</p>
        @endforelse
    </div>

</body>
</html>
