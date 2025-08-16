<div
    wire:poll.5s
    x-data="{
        open: false,
        prevCount: @entangle('count').defer,
        currentCount: @entangle('count'),
        playSoundIfNew() {
            if (this.currentCount > this.prevCount) {
                const audio = document.getElementById('notifSound');
                if (audio) {
                    audio.play().catch((e) => console.warn('Play error:', e));
                }
            }
            this.prevCount = this.currentCount;
        }
    }"
    x-init="
        const audio = document.getElementById('notifSound');
        audio.play().then(() => {
            audio.pause();
            audio.muted = false;
        }).catch(() => {});
    "
    x-effect="playSoundIfNew()"
    class="fixed bottom-4 right-4 z-50"
>
    <!-- Audio Notifikasi -->
    <audio id="notifSound" src="{{ asset('storage/sounds/notif1.mp3') }}" preload="auto" muted></audio>

    <!-- Tombol WhatsApp & Notifikasi -->
    <div class="flex gap-3">
        <!-- Tombol WhatsApp -->
        <a href="https://api.whatsapp.com/send/?phone=6282323602830&text=Hai+PKBI+Jawa+Timur%0D%0ASaya+ingin+menanyakan+beberapa+informasi%0D%0A%0D%0ATerimakasih.&type=phone_number&app_absent=0"
        target="_blank"
        class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-full shadow-lg flex items-center gap-2 transition-all duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-5 h-5" viewBox="0 0 24 24">
                <path d="M20.52 3.48A11.78 11.78 0 0 0 12 .001a11.84 11.84 0 0 0-10.21 17.59L.05 24l6.53-1.7a11.84 11.84 0 0 0 17.2-10.41 11.78 11.78 0 0 0-3.26-8.41zM12 21.27a9.33 9.33 0 0 1-4.74-1.29l-.34-.2-3.88 1 1-3.76-.25-.39A9.3 9.3 0 1 1 12 21.27zm5.05-6.85c-.28-.14-1.64-.81-1.89-.9s-.44-.14-.63.14-.72.9-.89 1.08-.33.21-.61.07a7.55 7.55 0 0 1-2.21-1.37 8.33 8.33 0 0 1-1.52-1.88c-.16-.28 0-.43.12-.57.13-.14.28-.33.42-.5a1.9 1.9 0 0 0 .28-.47.51.51 0 0 0 0-.5c-.07-.14-.63-1.5-.86-2.06s-.45-.48-.63-.49H7.2a1.21 1.21 0 0 0-.88.41 3.7 3.7 0 0 0-1.14 2.75 6.44 6.44 0 0 0 1.37 3.27 14.59 14.59 0 0 0 5.6 4.7c.78.34 1.39.55 1.86.7a4.5 4.5 0 0 0 2.06.13 3.42 3.42 0 0 0 2.22-1.59 2.75 2.75 0 0 0 .19-1.59c-.07-.14-.25-.2-.52-.34z"/>
            </svg>
            <span class="inline">Info Pengaduan</span>
        </a>

        <!-- Tombol Notifikasi -->
        <button
            @click="open = !open; document.getElementById('notifSound').muted = false;"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-full shadow-lg relative focus:outline-none flex items-center gap-2"
        >
            🔔
            <span class="inline">Notifikasi</span>
            @if ($count > 0)
                <span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs rounded-full px-1">
                    {{ $count }}
                </span>
            @endif
        </button>
    </div>


    <!-- Popup Notifikasi -->
    <div
        x-show="open"
        @click.away="open = false"
        x-transition
        class="mt-2 w-80 max-w-[90vw] rounded-lg shadow-xl bg-white ring-1 ring-black ring-opacity-5 overflow-hidden text-sm"
    >
        <div class="py-3 px-4 max-h-80 overflow-y-auto text-gray-800">
            <h2 class="font-bold mb-2 flex items-center justify-between">
                <span>Belum Dibaca</span>
                @if($notifs->count() > 0)
                    <button
                        wire:click="markAllAsRead"
                        class="text-xs text-blue-600 hover:underline focus:outline-none"
                    >
                        Tandai Semua Dibaca
                    </button>
                @endif
            </h2>

            @forelse ($notifs as $notif)
                <a
                    href="{{ route('notif.redirect', ['notifId' => $notif->id]) }}"
                    class="block px-3 py-2 mb-1 bg-blue-100 hover:bg-blue-200 rounded"
                >
                    {{ $notif->keterangan }}
                </a>
            @empty
                <div class="text-gray-500">Tidak ada notifikasi baru</div>
            @endforelse


            <h2 class="font-bold mt-4 mb-2">Terbaca</h2>
            @forelse ($allnotifs as $notifal)
                <div class="px-3 py-2 mb-1 hover:bg-gray-100 rounded">
                    {{ $notifal->keterangan }}
                </div>
            @empty
                <div class="text-gray-400">Belum ada</div>
            @endforelse
        </div>
    </div>
</div>
