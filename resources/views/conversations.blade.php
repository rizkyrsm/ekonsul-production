<x-layouts.app :title="__('Conversations')">
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-4">Semua Percakapan</h2>

        @if($conversations->isEmpty())
            <p class="text-gray-500">Belum ada percakapan.</p>
        @else
            <div class="grid gap-4">
                @foreach($conversations as $conversation)
                    @php
                        $user = \App\Models\User::find($conversation->user_id);
                    @endphp
                    <div class="p-4 border rounded-lg shadow hover:bg-gray-100">
                        <a href="{{ route('chatify', ['id' => $user->id]) }}" class="flex items-center gap-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-gray-300 rounded-full">
                                <!-- Avatar Placeholder -->
                            </div>
                            <div>
                                <p class="text-lg font-semibold">{{ $user->name }}</p>
                                <p class="text-gray-500 text-sm">Klik untuk membuka percakapan</p>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-layouts.app>
