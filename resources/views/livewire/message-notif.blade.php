<div
    wire:poll.5s="$refresh"
    x-data="{
        prevCount: {{ $count }},
        currentCount: {{ $count }},
        playSoundIfNew() {
            if (this.currentCount > this.prevCount) {
                const audio = document.getElementById('chatNotifSound');
                if (audio) {
                    audio.play().catch(() => {});
                }
            }
            this.prevCount = this.currentCount;
        }
    }"
    x-init="$watch('$wire.count', value => { currentCount = value; playSoundIfNew(); })"
>
    <!-- Audio notification -->
    <audio id="chatNotifSound" src="{{ asset('storage/sounds/notif2.mp3') }}" preload="auto"></audio>

    <!-- Badge Icon -->
    <div class="relative inline-block">
        <i class="bi bi-chat-dots-fill text-white-500 text-2xl"></i>

        @if ($count > 0)
            <span
                class="absolute -top-1 -right-1 bg-red-600 text-white text-xs rounded-full px-1.5"
                x-text="currentCount = {{ $count }}">
                {{ $count }}
            </span>
        @endif
    </div>
</div>
