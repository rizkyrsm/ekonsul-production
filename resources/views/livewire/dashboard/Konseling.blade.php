<div class="container mx-auto p-4">
    @if (session('message'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif
    <h2 class="text-2xl font-bold mb-4">History Konseling</h2>
    @if ($konselings->isEmpty())
        <div class="bg-yellow-100 text-yellow-800 p-3 rounded mb-4">
            Tidak ada konseling ditemukan.
        </div>
    @else

    @if (session()->has('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif


     <!-- Pagination -->
            <div class="mt-4">
                {{ $konselings->links() }}
            </div>
        <div class="overflow-x-auto shadow-lg rounded-lg">
            <table class="min-w-full bg-white border border-gray-300">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">ID Order</th>
                        <th class="py-3 px-6 text-left">Aksi</th>
                        <th class="py-3 px-6 text-left">Client</th>
                        <th class="py-3 px-6 text-left">Nama Layanan</th>
                        <th class="py-3 px-6 text-left">Konselor</th>
                        <th class="py-3 px-6 text-left">Status</th>
                        <th class="py-3 px-6 text-left">Aksi</th>
                        <th class="py-3 px-6 text-left">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @foreach ($konselings as $konseling)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-6">{{ $konseling->id_order }}</td>
                            <td class="py-3 px-6">
                                @canRole('ADMIN','CABANG')
                                    @php
                                        $from_id = $konseling->id_konselor;
                                        $to_id = $konseling->id_user;
                                        $id_order = $konseling->id_order;
                                    @endphp

                                    <button onclick="openChatPopup('{{ $from_id }}', '{{ $to_id }}', '{{ $id_order }}')" class="bg-green-500 text-white px-4 py-2 rounded">
                                        <i class="bi bi-chat-heart-fill"></i> Buka
                                    </button>
                                @endcanRole
                                @canRole('KONSELOR')
                                    @php
                                        $from_id = $konseling->id_konselor;
                                        $to_id = $konseling->id_user;
                                        $id_order = $konseling->id_order;
                                    @endphp

                                    @if ($konseling->payment_status == 'SELESAI')
                                        <button onclick="openChatPopup('{{ $from_id }}', '{{ $to_id }}', '{{ $id_order }}')" 
                                            class="bg-green-500 text-white px-4 py-2 rounded">
                                            <i class="bi bi-chat-heart-fill"></i> Buka
                                        </button>
                                    @else
                                            <button onclick="openStartChat('{{ $konseling->id_user }}', '{{ $id_order }}')" 
                                            class="bg-blue-500 text-white px-4 py-2 rounded"> Mulai Konsultasi
                                            <livewire:message-notif :user-id="$konseling->id_user" :order-id="$id_order" />
                                        </button>
                                    @endif
                                @endcanRole

                                @canRole('USER')
                                    @php
                                        $from_id = $konseling->id_konselor;
                                        $to_id = $konseling->id_user;
                                        $id_order = $konseling->id_order;
                                    @endphp

                                    @if ($konseling->payment_status == 'SELESAI')
                                        <button onclick="openChatPopup('{{ $from_id }}', '{{ $to_id }}', '{{ $id_order }}')" 
                                            class="bg-green-500 text-white px-4 py-2 rounded">
                                            <i class="bi bi-chat-heart-fill"></i> Buka
                                        </button>
                                    @else
                                       <button onclick="checkProfileAndStartChat('{{ $to_id }}', '{{ $from_id }}','{{ $id_order }}')"
                                            class="bg-blue-500 text-white px-4 py-2 rounded"> Mulai Konsultasi
                                            <livewire:message-notif :user-id="$konseling->id_konselor" :order-id="$id_order" />
                                        </button>
                                    @endif
                                @endcanRole

                                
                            </td>
                            {{-- <td class="py-3 px-6">{{ $konseling->user_name }}</td> --}}
                            <td class="py-3 px-6">
                                <button 
                                    onclick="showProfileModal({{ $konseling->id_user }})" 
                                    class="ml-2 text-sm text-blue-600 hover:underline"
                                >   <i class="bi bi-person-bounding-box"></i>
                                    {{-- <span class="px-5 text-white inline-flex text-xs leading-5 font-semibold rounded bg-yellow-500">{{ $konseling->user->detailUser->nama }} </span> --}}
                                    <span class="px-5 text-white inline-flex text-xs leading-5 font-semibold rounded bg-yellow-500">{{ $konseling->user->name }} </span>
                                </button>

                                
                                @canRole('ADMIN','CABANG','KONSELOR') 
                                    <a class="px-5 text-white inline-flex text-xs leading-5 font-semibold rounded bg-blue-500" href="{{ route('allChat', ['user_id' => $konseling->id_user]) }}" 
                                    target="_blank"><i class="bi bi-clock-history"></i> &nbsp; History</a>
                                @endcanRole


                            </td>
                            <td class="py-3 px-6">{{ $konseling->nama_layanan }}</td>
                            <td class="py-3 px-6">{{ $konseling->konselor->detailUser->nama }}</td>
                            <td class="py-3 px-6">
                                <div class="flex items-center gap-2">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $konseling->payment_status == 'BELUM BAYAR' ? 'bg-red-500 text-red-800' : 'bg-green-500 text-green-800' }}">
                                        {{ $konseling->payment_status == 'LUNAS' ? 'AKTIF' : 'SELESAI' }}
                                    </span>
                                </div>
                            </td>
                            <td class="py-3 px-6">
                                @canRole('KONSELOR')
                                    @if ($konseling->payment_status == 'LUNAS')
                                        <form method="POST" action="{{ route('konseling.updateStatus', $konseling->id_order) }}" class="d-inline" onsubmit="return confirm('Yakin ingin menyelesaikan sesi ini?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-xs bg-green-500 text-white px-2 py-1 rounded hover:bg-blue-600">
                                                <i class="bi bi-patch-check-fill"></i>&nbsp; Selesaikan
                                            </button>
                                        </form>
                                    @endif

                                    @if($konseling->payment_status == 'SELESAI')
                                        <button type="button" 
                                            onclick="openRangkumanPopup('{{ $konseling->id_order }}')" 
                                            class="text-xs bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">
                                            <i class="bi bi-journal-medical"></i> &nbsp; Hasil Konseling
                                        </button>
                                    @endif
                                @endcanRole

                                @canRole('USER')
                                @if($konseling->payment_status == 'SELESAI')
                                    <div class="flex items-center space-x-1">
                                        @php
                                            $nilai = $nilaiPenilaian[$konseling->id_order] ?? 0;
                                            $bolehUpdate = $bisaUpdate[$konseling->id_order] ?? false;
                                        @endphp

                                        @for ($i = 1; $i <= 5; $i++)
                                            <i 
                                                class="{{ $nilai >= $i 
                                                        ? 'bi bi-star-fill text-yellow-800 drop-shadow-md' 
                                                        : 'bi bi-star text-yellow-800' }}
                                                    text-lg transition duration-200 transform
                                                    {{ $bolehUpdate ? 'hover:text-yellow-800 hover:scale-110 cursor-pointer' : 'opacity-90 cursor-not-allowed' }}"
                                                @if($bolehUpdate)
                                                    wire:click="pilihBintang({{ $konseling->id_order }}, {{ $konseling->id_konselor }}, {{ $i }})"
                                                @endif
                                            ></i>

                                        @endfor
                                    </div>

                                    @if(!$bolehUpdate)
                                        <small class="text-red-500 block mt-1">Penilaian tidak dapat diubah setelah 3 hari.</small>
                                    @endif
                                @endif
                                @endcanRole

                            </td>

                            <td class="py-3 px-6">{{ $konseling->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- modal riwayat konseling view --}}
    <div id="chatify-popup" class="fixed bottom-10 right-4 w-100 h-[650px] bg-white shadow-xl hidden z-50">
        <div class="flex justify-between items-center text-white p-2 bg-blue-800 border-b">
            <h1 class="text-xl font-bold mb-4">Riwayat Konseling</h1>
            <flux:button onclick="closeChatPopup()" icon="x-mark" variant="danger" size="sm"></flux:button>
        </div>
        <iframe id="chatify-frame" src="" class="w-full h-full border-none"></iframe>
    </div>

    <script>
        function openChatPopup(fromId, toId, id_order) {
            const url = `/custom-chat/${fromId}/${toId}/${id_order}`;
            document.getElementById('chatify-frame').src = url;
            document.getElementById('chatify-popup').classList.remove('hidden');
        }
        
        function closeChatPopup() {
            document.getElementById('chatify-frame').src = '';
            document.getElementById('chatify-popup').classList.add('hidden');
        }
    </script>
    {{-- akhir modal riwayat konseling --}}

    {{-- Modal untuk memulai chat --}}
        <div id="chat-start-popup" style="z-index:10000;" class="fixed bottom-10 right-4 w-100 h-[650px] bg-white shadow-xl hidden z-50">
            <div class="flex justify-between items-center text-white p-2 bg-green-800 border-b">
                <h1 class="text-xl font-bold mb-4">Chat</h1>
                <flux:button onclick="closeStartChatPopup()" icon="x-mark" variant="danger" size="sm"></flux:button>
            </div>
            <iframe id="chat-start-frame" style="z-index:10000;" src="" class="w-full h-full border-none"></iframe>
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
    {{-- akhir modal memulai chat --}}

    {{-- Modal untuk user profile detail --}}
    <div id="profileModal" class="fixed inset-0 hidden z-50 flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 border rounded-lg shadow-lg w-full max-w-md p-6 relative">
            <button onclick="closeProfileModal()" class="bg-red-500 px-2 py-2 absolute top-2 right-2 text-white-500 rounded hover:text-red-800">x</button>
            <h2 class="text-xl font-bold mb-4">Detail Profil</h2>
            <div id="profileContent">
                <p>Memuat...</p>
            </div>
        </div>
    </div>

    <script>
        function showProfileModal(userId) {
            document.getElementById('profileModal').classList.remove('hidden');
            document.getElementById('profileContent').innerHTML = 'Memuat...';

            fetch(`/profile-detail-json/${userId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('profileContent').innerHTML = `
                        <p><strong>Nama:</strong> ${data.nama}</p>
                        <p><strong>NIK:</strong> ${data.nik}</p>
                        <p><strong>Tempat, Tanggal Lahir:</strong> ${data.tempat_lahir}, ${data.tgl_lahir}</p>
                        <p><strong>Alamat:</strong> ${data.alamat}</p>
                        <p><strong>No Telepon:</strong> ${data.no_tlp}</p>
                        <p><strong>Status Online:</strong> ${data.status_online}</p>
                        <p><strong>Jenis Kelamin:</strong> ${data.jenis_kelamin}</p>
                        <p><strong>Status Pernikahan:</strong> ${data.status_pernikahan}</p>
                        <p><strong>Agama:</strong> ${data.agama}</p>
                    `;
                });
        }

        function closeProfileModal() {
            document.getElementById('profileModal').classList.add('hidden');
        }
    </script>
    {{-- Akhir modal profile --}}

    {{-- CEK ISI DETAIL USER --}}
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
    

    <!-- Popup Rangkuman & Saran -->
    <div id="rangkumanPopup" class="fixed inset-0 hidden bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-lg">
            <h2 class="text-xl text-black font-bold mb-4">Rangkuman & Saran</h2>
            <form id="rangkumanForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block font-semibold">Rangkuman</label>
                    <textarea name="rangkuman" id="rangkumanText" class="w-full border text-black p-2 rounded" rows="3" required></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-black font-semibold">Saran</label>
                    <textarea name="saran" id="saranText" class="w-full text-black border p-2 rounded" rows="3" required></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeRangkumanPopup()" class="bg-gray-400 text-white px-4 py-2 rounded">Batal</button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openRangkumanPopup(id_order) {
            const popup = document.getElementById('rangkumanPopup');
            const form = document.getElementById('rangkumanForm');
            form.action = `/konseling/rangkuman/${id_order}`;

            // Kosongkan dulu
            document.getElementById('rangkumanText').value = '';
            document.getElementById('saranText').value = '';

            // Ambil data lama kalau ada
            fetch(`/konseling/rangkuman/${id_order}`)
                .then(res => res.json())
                .then(data => {
                    if (data) {
                        document.getElementById('rangkumanText').value = data.rangkuman ?? 'Belum ada rangkuman';
                        document.getElementById('saranText').value = data.saran ?? 'Belum ada saran';
                    }
                });

            popup.classList.remove('hidden');
        }

        function closeRangkumanPopup() {
            document.getElementById('rangkumanPopup').classList.add('hidden');
        }
    </script>


</div>