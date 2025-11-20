<div>
    <h3 class="text-lg font-semibold text-grey">Pilih Konselor</h3>
    <div class="grid gap-4 grid-cols-1 md:grid-cols-4">
        @if($konseloroff)
            <p class="text-red-600">{{ $konseloroff }}</p>
        @endif
        
        <!-- Daftar Konselor -->
        @if($konselors->count() === 0)
            <div class="text-center text-red-600 italic py-4">Belum ada konselor tersedia saat ini</div>
        @else
            @foreach($konselors as $konselor)
                @php
                    // Cek apakah konselor sedang busy (ada order LUNAS)
                    $isBusy = in_array($konselor->id, $konselorsBusy);
                @endphp

                <label class="rounded-lg shadow-md bg-white p-4 hover:shadow-lg transition border border-blue-500 flex items-center space-x-4 {{ $isBusy ? 'opacity-60 cursor-not-allowed' : '' }}">
                    <input type="radio"
                        wire:model="konselor"
                        value="{{ $konselor->id }}"
                        class="h-5 w-5 text-blue-600 focus:ring-2 focus:ring-green-500"
                        {{ $isBusy ? 'disabled' : '' }} />

                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">{{ $konselor->nama }}</h3>
                        <p class="text-green-600 font-bold">{{ $konselor->cabang_name }}</p>

                        @if($isBusy)
                            <span class="text-xs bg-red-600 text-white font-semibold px-2 py-1 rounded">Sedang dalam sesi konseling lain</span>
                        @endif
                    </div>
                </label>
            @endforeach
        @endif


    </div>

    <div class="p-4 space-y-4">
        <input type="text" wire:model.defer="voucher" placeholder="Masukkan kode voucher" class="border border-blue-500 p-2 rounded">
        <button wire:click="applyVoucher" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Terapkan Voucher</button>

        {{-- @if($vouchernofalid)
            <p class="text-red-600 text-sm">Voucher terisi : <strong>{{ $vouchernofalid }}</strong></p>
        @endif --}}

        @if($message)
            <p class="text-green-600 font-bold">{{ $message }}</p>
        @endif

        <div class="grid gap-4 grid-cols-1 md:grid-cols-2">
            @foreach($layanans as $layanan)
                <div class="rounded-lg shadow-md bg-white p-4 hover:shadow-lg transition border border-blue-500">
                    <h3 class="text-lg font-semibold text-gray-800">{{ $layanan->nama_layanan }}</h3>
                    <p class="text-gray-600">Harga: Rp. {{ number_format($layanan->harga_layanan, 0, ',', '.') }}</p>
                    <p class="text-gray-600">Voucher: {{ $vouchernofalid }}</p>
                    <p class="text-gray-600">Jumlah Potongan: {{ $jenispotongan }}</p>
                    <p class="text-green-600 font-bold">Total: Rp. {{ number_format($hargaSetelahDiskon[$layanan->id_layanan], 0, ',', '.') }}</p>
                </div>
            @endforeach

            <!-- Metode Pembayaran -->
            <div class="rounded-lg shadow-md bg-white p-4 hover:shadow-lg transition border border-blue-500" style="display:none;">
                <h3 class="text-lg font-semibold text-gray-800">Metode Pembayaran</h3>
                <div class="flex items-center space-x-4">
                    <!-- Bank Mandiri -->
                    <div class="flex items-center space-x-2">
                        <img src="https://cdn.antaranews.com/cache/1200x800/2020/03/22/logo-bank-mandiri.jpg" 
                             alt="Logo Mandiri" class="w-20 h-auto mb-2">
                        <div>
                            <p class="text-gray-600 font-semibold">PKBI JAWA TIMUR</p>
                            <p class="text-gray-600 font-semibold">1400095029253</p>
                        </div>
                    </div>

                    <!-- QRIS -->
                    <div class="flex items-center space-x-2">
                        <a href="{{ asset('qrispkbi.pdf') }}" target="_blank">
                            <div class="flex items-center space-x-2 cursor-pointer hover:opacity-80">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a2/Logo_QRIS.svg/640px-Logo_QRIS.svg.png" 
                                     alt="QRIS" class="w-20 h-auto mb-2">
                                <div>
                                    <p class="text-gray-600 font-semibold">PKBI JAWA TIMUR <br>Klik Untuk Scan QRIS</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tombol Simpan -->
    <button wire:click="saveOrder" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Pesan</button>         
    @if (session()->has('message'))
        <p class="text-green-600">{{ session('message') }}</p>
    @endif
</div>
