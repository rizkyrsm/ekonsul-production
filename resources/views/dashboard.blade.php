<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        
        @if (session('success'))
            <div class="bg-green-100 text-green-800 p-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Card Statistik -->
        @canRole('KONSELOR','CABANG')
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <!-- Card Konselor (Biru) -->
            <div class="flex items-center gap-4 rounded-xl border border-blue-500 bg-blue-500 p-4 shadow-sm">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-800 text-white">
                    @svg('heroicon-o-user-group', 'w-12 h-12')
                </div>
                <div>
                    <div class="text-sm font-medium text-white">CLIENT DILAYANI</div>
                    <div class="text-5xl font-bold text-white">{{ $jumlahClient }}</div>
                </div>
            </div>

            <!-- Card User (Kuning) -->
            <div class="flex items-center gap-4 rounded-xl border border-green-500 bg-green-500 p-4 shadow-sm">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-green-700 text-white">
                    @svg('heroicon-o-chat-bubble-left-right', 'w-12 h-12')
                </div>
                <div>
                    <div class="text-sm font-medium text-white">KONSELING DISELESAIKAN</div>
                    <div class="text-5xl font-bold text-white">{{ $jumlahKonseling }}</div>
                </div>
            </div>
        @endcanRole

        @canRole('KONSELOR')
            <!-- Penilaian (Hijau) -->
            <div class="flex items-center gap-4 rounded-xl border border-yellow-500 bg-yellow-500 p-4 shadow-sm">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-yellow-700 text-white">
                    @svg('heroicon-o-star', 'w-12 h-12')
                </div>
                <div>
                    <div class="text-sm font-medium text-white">KUALITAS LAYANAN</div>
                    <div class="text-5xl font-bold text-white"> {{ $rataNilaiKonselor  }}/5</div>
                </div>
            </div>
        </div>
        @endcanRole

        @canRole('ADMIN')
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <!-- Card Konselor (Biru) -->
            <div class="flex items-center gap-4 rounded-xl border border-blue-500 bg-blue-500 p-4 shadow-sm">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-800 text-white">
                    @svg('heroicon-o-user-group', 'w-12 h-12')
                </div>
                <div>
                    <div class="text-sm font-medium text-white">JUMLAH KONSELOR</div>
                    <div class="text-5xl font-bold text-white">{{ $jumlahKonselor }}</div>
                </div>
            </div>

            <!-- Card User (Kuning) -->
            <div class="flex items-center gap-4 rounded-xl border border-yellow-500 bg-yellow-500 p-4 shadow-sm">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-yellow-700 text-white">
                    @svg('heroicon-o-user', 'w-12 h-12')
                </div>
                <div>
                    <div class="text-sm font-medium text-white">JUMLAH USER</div>
                    <div class="text-5xl font-bold text-white">{{ $jumlahUser }}</div>
                </div>
            </div>

            <!-- Card Diskon Aktif (Hijau) -->
            <div class="flex items-center gap-4 rounded-xl border border-green-500 bg-green-500 p-4 shadow-sm">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-green-700 text-white">
                    @svg('heroicon-o-tag', 'w-12 h-12')
                </div>
                <div>
                    <div class="text-sm font-medium text-white">DISKON AKTIF</div>
                    <div class="text-5xl font-bold text-white">{{ $jumlahDiskonAktif }}</div>
                </div>
            </div>
        </div>
        @endcanRole

        @canRole('KONSELOR','USER')
            <h2 class="text-2xl font-bold mb-4">Konseling Aktif</h2>

            @if ($konselings->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                    @foreach ($konselings as $konseling)
                        @php
                            $from_id = $konseling->id_konselor;
                            $to_id = $konseling->id_user;
                            $id_order = $konseling->id_order;
                            $role = auth()->user()->role;
                            $chatWithId = ($role === 'USER') ? $from_id : $to_id;
                        @endphp

                        @if ($role === 'USER')
                            <button onclick="checkProfileAndStartChat('{{ $to_id }}', '{{ $from_id }}', '{{ $id_order }}')" 
                                    class="bg-blue-500 text-white px-4 py-2 rounded shadow-md hover:bg-blue-600 transition w-full flex flex-col items-start gap-1">
                        @else
                            <button onclick="openStartChat('{{ $to_id }}', '{{ $id_order }}')" 
                                    class="bg-blue-500 text-white px-4 py-2 rounded shadow-md hover:bg-blue-600 transition w-full flex flex-col items-start gap-1">
                        @endif

                            <livewire:message-notif :user-id="$chatWithId" :order-id="$id_order" />
                            <span class="text-sm font-semibold truncate">{{ $konseling->nama_layanan }}</span>
                            <span class="text-sm font-semibold text-white-100 truncate bg-green-700 px-2 py-1 rounded">
                                @if ($role === 'USER')
                                    {{ $konseling->konselor->name ?? '-' }}
                                @else
                                    <a href="{{ route('allChat', ['user_id' => $konseling->id_user]) }}" 
                                        target="_blank">
                                        {{ $konseling->user->name ?? '-' }}
                                    </a>
                                @endif
                            </span>
                        </button>
                    @endforeach
                </div>
            @else
                <!-- Tampilan modern jika tidak ada konseling aktif -->
                <div class="flex flex-col items-center justify-center p-8 bg-gray-100 rounded-xl shadow-md">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-red-500 text-white">
                        @svg('heroicon-o-chat-bubble-bottom-center-text', 'w-12 h-12')
                    </div>
                    <p class="text-lg font-semibold text-gray-600">Tidak ada sesi konseling aktif</p>
                    <p class="text-sm text-gray-500">Mulailah sesi baru untuk memulai percakapan.</p>
                </div>
            @endif
        @endcanRole


        @canRole('USER')
        <h2 class="text-2xl font-bold mb-4">Pilih Layanan</h2>
        <div class="grid grid-cols-2 sm:grid-cols-1 md:grid-cols-3 gap-1">
            @foreach($layanans as $layanan)
                <div class="rounded-lg shadow-md bg-white p-4 hover:shadow-lg transition border border-blue-500">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-lg font-semibold text-black px-2 py-1 rounded" style="background-color: {{ $layanan->warna_layanan }};">
                            {{ $layanan->nama_layanan }}
                        </h3>
                        <span class="text-xs bg-red-500 text-black font-semibold px-2 py-1 rounded">
                            30 Menit
                        </span>
                    </div>

                    <p class="text-gray-600 line-through">Rp. {{ number_format($layanan->harga_layanan, 0, ',', '.') }} </p>
                    <p class="text-black font-bold">Potongan Diskon 100%</p>
                    <p class="text-green-600 font-bold">Gunakan Kode Voucher: PKBIJAYA</p>

                    <a href="{{ route('dashboard.keranjang', ['id' => $layanan->id_layanan]) }}" class="mt-2 w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 flex items-center justify-center gap-2">
                        Mulai Konseling
                        <flux:icon.chat-bubble-oval-left variant="solid" />
                    </a>
                </div>

            @endforeach
        </div>
        @endcanRole
        
    </div>

    {{-- CEK ISI DETAIL USER --}}
        <div id="chat-start-popup" style="z-index:9000;" class="fixed bottom-10 right-4 w-100 h-[650px] bg-white shadow-xl hidden z-50">
            <div class="flex justify-between items-center text-white p-2 bg-green-800 border-b">
                <h1 class="text-xl font-bold mb-4">Chat</h1>
                <flux:button onclick="closeStartChatPopup()" icon="x-mark" variant="danger" size="sm"></flux:button>
            </div>
            <iframe id="chat-start-frame" src="" style="z-index:10000;" class="w-full h-full border-none"></iframe>
        </div>
        <script>
            function openStartChat(userId, id_order) {
                const url = `/chatify/${userId}?id_order=${id_order}`; // atau endpoint chat yang sesuai untuk role konselor/user
                document.getElementById('chat-start-frame').src = url;
                document.getElementById('chat-start-popup').classList.remove('hidden');
            }

            function closeStartChatPopup() {
                document.getElementById('chat-start-frame').src = '';
                document.getElementById('chat-start-popup').classList.add('hidden');
            }

        </script>
        <script>
            function checkProfileAndStartChat(userId,konselorId,id_order) {
                fetch(`/check-profile/${userId}`)
                    .then(res => res.json())
                    .then(data => {
                        console.log("TEST", userId,konselorId,id_order);
                        if (data.complete) {
                            openStartChat(konselorId, id_order);
                        } else {
                            alert('Profil Anda belum lengkap. Silakan lengkapi profil terlebih dahulu.');
                            window.location.href = 'settings/profile-detail';
                        }
                    })
                    .catch(err => {
                        alert('Terjadi kesalahan. Coba lagi.');
                        console.error(err);
                    });
            }
        </script>
    {{-- AKHIR CEK DETAIL --}}

</x-layouts.app>


