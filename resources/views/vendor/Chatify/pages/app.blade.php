@include('Chatify::layouts.headLinks')
<div class="messenger">
    {{-- ----------------------Users/Groups lists side---------------------- --}}
    <div class="messenger-listView {{ !!$id ? 'conversation-active' : '' }}">
        {{-- Header and search bar --}}
        <div class="m-header">
            <nav>
                <input type="hidden" name="id_order" id="id_order" value="{{ request('id_order') }}">
                <a href="#"><i class="fas fa-inbox"></i> <span class="messenger-headTitle">EKONSUL</span> </a>
                {{-- header buttons --}}
                <nav class="m-header-right">
                    <a href="#"><i class="fas fa-cog settings-btn"></i></a>
                    <a href="#" class="listView-x"><i class="fas fa-times"></i></a>
                </nav>
            </nav>
            {{-- Search input --}}
            @canRole('ADMIN','KONSELOR','CABANG')
                <input type="text" class="messenger-search" placeholder="Search" />
            @endcanRole
            {{-- Tabs --}}
            {{-- <div class="messenger-listView-tabs">
                <a href="#" class="active-tab" data-view="users">
                    <span class="far fa-user"></span> Contacts</a>
            </div> --}}
        </div>
        {{-- tabs and lists --}}
        <div class="m-body contacts-container">
           {{-- Lists [Users/Group] --}}
           {{-- ---------------- [ User Tab ] ---------------- --}}
           <div class="show messenger-tab users-tab app-scroll" data-view="users">
               {{-- Favorites --}}
               <div class="favorites-section">
                <p class="messenger-title"><span>Favorites</span></p>
                <div class="messenger-favorites app-scroll-hidden"></div>
               </div>
               {{-- Saved Messages --}}
               {{-- <p class="messenger-title"><span>Your Space</span></p>
               {!! view('Chatify::layouts.listItem', ['get' => 'saved']) !!} --}}
               {{-- Contact --}}
               @canRole('ADMIN','KONSELOR','CABANG')
               <p class="messenger-title"><span>All Messages</span></p>
               <div class="listOfContacts" style="width: 100%;height: calc(100% - 272px);position: relative;"></div>
               @endcanRole
           </div>
             {{-- ---------------- [ Search Tab ] ---------------- --}}
           <div class="messenger-tab search-tab app-scroll" data-view="search">
                {{-- items --}}
                <p class="messenger-title"><span>Search</span></p>
                <div class="search-records">
                    <p class="message-hint center-el"><span>Type to search..</span></p>
                </div>
             </div>
        </div>
    </div>

    {{-- ----------------------Messaging side---------------------- --}}
    <div class="messenger-messagingView">
        {{-- header title [conversation name] amd buttons --}}
        <div class="m-header m-header-messaging">
            <nav class="chatify-d-flex chatify-justify-content-between chatify-align-items-center">
                {{-- header back button, avatar and user name --}}
                <div class="chatify-d-flex chatify-justify-content-between chatify-align-items-center">
                    {{-- <a href="#" class="show-listView"><i class="fas fa-arrow-left"></i></a> --}}
                    <div class="avatar av-s header-avatar" style="margin: 0px 10px; margin-top: -5px; margin-bottom: -5px;">
                    </div>
                    {{-- <a href="#" class="user-name">{{ config('chatify.name') }}</a> --}}

                    @php
                        $chatUser = \App\Models\User::with('detailUser')->find($id);
                    @endphp
                    <a href="#" class="">{{ $chatUser->detailUser->nama ?? $chatUser->name }}</a>| 
                    @php
                        use App\Models\ChMessage;
                        $firstMsg = \App\Models\ChMessage::where('id_order', request('id_order'))->orderBy('created_at')->first();
                        $firstTimestamp = optional($firstMsg)->created_at ? optional($firstMsg)->created_at->timestamp : null;
                    @endphp

                    <p>
                        Layanan: {{ request('id_order') }} |
                        <span id="countdownTimer" style="background-color: #ef4444; color: white; border-radius: 9999px; padding: 2px 8px; font-size: 12px; font-weight: 600;">--:--</span>
                    </p>
                </div>
                {{-- header buttons --}}
                <nav class="m-header-right">
                    @canRole('ADMIN')
                    <a href="#" class="add-to-favorite"><i class="fas fa-star"></i></a>
                    <a href="/"><i class="fas fa-times"></i></a>
                    <a href="#" class="show-infoSide"><i class="fas fa-info-circle"></i></a>
                    @endcanRole
                </nav>
            </nav>
            {{-- Internet connection --}}
            <div class="internet-connection">
                <span class="ic-connected">Connected</span>
                <span class="ic-connecting">Connecting...</span>
                <span class="ic-noInternet">No internet access</span>
            </div>
        </div>

        {{-- Messaging area --}}
        <div class="m-body messages-container app-scroll">
            <div class="messages">
                <p class="message-hint center-el"><span>Please select a chat to start messaging</span></p>
            </div>
            {{-- Typing indicator --}}
            <div class="typing-indicator">
                <div class="message-card typing">
                    <div class="message">
                        <span class="typing-dots">
                            <span class="dot dot-1"></span>
                            <span class="dot dot-2"></span>
                            <span class="dot dot-3"></span>
                        </span>
                    </div>
                </div>
            </div>

        </div>
        {{-- Send Message Form --}}
        @include('Chatify::layouts.sendForm')
    </div>
    {{-- ---------------------- Info side ---------------------- --}}
    @canRole('ADMIN')
    <div class="messenger-infoView app-scroll">
        {{-- nav actions --}}
        <nav>
            <p>User Details</p>
            <a href="#"><i class="fas fa-times"></i></a>
        </nav>
        {!! view('Chatify::layouts.info')->render() !!}
    </div>
    @endcanRole
</div>

@include('Chatify::layouts.modals')

<script>
    const firstMessageTimestamp = {{ $firstTimestamp ?? 'null' }};
    const idOrder = "{{ request('id_order') }}";

    if (firstMessageTimestamp && idOrder) {
        let hasSent25MinMessage = false;
        let hasUpdatedOrder = false;

        const countdownInterval = setInterval(() => {
            const now = Math.floor(Date.now() / 1000);
            const elapsed = now - firstMessageTimestamp;

            const remaining = 1800 - elapsed; // 30 menit
            const minutes = Math.floor(remaining / 60).toString().padStart(2, '0');
            const seconds = (remaining % 60).toString().padStart(2, '0');

            const display = remaining > 0 ? `${minutes}:${seconds}` : '00:00';
            const countdownEl = document.getElementById('countdownTimer');
            if (countdownEl) {
                countdownEl.innerText = display;
            }

            // Kirim pesan otomatis setelah 25 menit
            if (elapsed >= 1500 && !hasSent25MinMessage) {
                hasSent25MinMessage = true;

                fetch("/auto-finish-message", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ id_order: idOrder })
                })
                .then(res => res.json())
                .then(data => {
                    console.log("25min message sent:", data);
                });
            }

            // Update status order dan redirect setelah 30 menit
            if (elapsed >= 1800 && !hasUpdatedOrder) {
                hasUpdatedOrder = true;
                clearInterval(countdownInterval);

                fetch("/auto-finish-order", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ id_order: idOrder })
                })
                .then(res => res.json())
                .then(data => {
                    console.log("Order status updated:", data);
                    // Redirect otomatis ke dashboard setelah 2 detik
                    setTimeout(() => {
                        (window.top || window).location.href = "/konseling";
                    }, 2000);
                });
            }

        }, 1000);
    }
</script>



@include('Chatify::layouts.footerLinks')
