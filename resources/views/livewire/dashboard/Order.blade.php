<div class="container mx-auto p-4">
    @if (session('message'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif
    
    <h2 class="text-2xl font-bold mb-4">Daftar Pembayaran</h2>
    
    @if ($orders->isEmpty())
        <div class="bg-yellow-100 text-yellow-800 p-3 rounded mb-4">
            Tidak ada pesanan ditemukan.
        </div>
    @else

    @if (session()->has('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif


     <!-- Pagination -->
            <div class="mt-4">
                {{ $orders->links() }}
            </div>
        <div class="overflow-x-auto shadow-lg rounded-lg">
            <table class="min-w-full bg-white border border-gray-300">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">ID Order</th>
                        <th class="py-3 px-6 text-left">Client</th>
                        <th class="py-3 px-6 text-left">Nama Layanan</th>
                        <th class="py-3 px-6 text-left">Konselor</th>
                        <th class="py-3 px-6 text-left">Voucher</th>
                        <th class="py-3 px-6 text-left">Total Payment</th>
                        <th class="py-3 px-6 text-left">Status</th>
                        <th class="py-3 px-6 text-left">Upload</th>
                        <th class="py-3 px-6 text-left">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @foreach ($orders as $order)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-6">{{ $order->id_order }}</td>
                            <td class="py-3 px-6"><span class="px-5 text-white inline-flex text-xs leading-5 font-semibold rounded bg-yellow-500">{{ $order->user->name }} </span></td>
                            <td class="py-3 px-6">{{ $order->nama_layanan }}</td>
                            <td class="py-3 px-6">{{ $order->konselor->detailUser->nama }}</td>
                            <td class="py-3 px-6">{{ $order->voucher ?? '-' }}</td>
                            <td class="py-3 px-6">Rp. {{ number_format($order->total, 0, ',', '.') }}</td>
                            <td class="py-3 px-6">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $order->payment_status == 'BELUM BAYAR' ? 'bg-red-300 text-red-800' : 'bg-green-300 text-green-800' }}">
                                    {{ $order->payment_status }}
                                </span>

                                @if (auth()->user()->role === 'ADMIN')
                                    <button wire:click="editStatus({{ $order->id_order }})"
                                        class="ml-2 bg-yellow-500 text-white px-2 py-1 rounded text-xs hover:bg-yellow-600">
                                        Edit
                                    </button>
                                @endif

                                {{-- Form edit status (jika sedang diedit) --}}
                                @if ($editingStatusId === $order->id_order)
                                    <div class="mt-2 flex items-center space-x-2">
                                        <select wire:model="newStatus" class="text-xs border rounded px-2 py-1">
                                            <option value="">-- Pilih Status --</option>
                                            <option value="BELUM BAYAR">BELUM BAYAR</option>
                                            <option value="LUNAS">LUNAS</option>
                                        </select>
                                        <button wire:click="updateStatus({{ $order->id_order }})"
                                            class="bg-green-500 text-white px-2 py-1 rounded text-xs hover:bg-green-600">Simpan</button>
                                        <button wire:click="cancelEditStatus"
                                            class="bg-red-500 text-white px-2 py-1 rounded text-xs hover:bg-red-600">Batal</button>
                                    </div>
                                @endif
                            </td>

                            {{-- Upload Bukti Transfer --}}
                            <td class="py-3 px-6">
                                @if($order->bukti_transfer)
                                    <a href="{{ asset('public/' . $order->bukti_transfer) }}" target="_blank" class="bg-green-500 text-white px-2 py-1 rounded text-xs hover:bg-green-600">
                                        Lihat
                                    </a>
                                    {{-- <img src="{{ asset('public/' . $order->bukti_transfer) }}" alt="Bukti Transfer" class="w-32 rounded"> --}}
                                    @canRole('ADMIN','CABANG')
                                        <button wire:click="deleteBuktiTransfer({{ $order->id_order }})"
                                            onclick="confirm('Yakin ingin menghapus bukti transfer?') || event.stopImmediatePropagation()"
                                            class="bg-red-500 text-white px-2 py-1 rounded text-xs hover:bg-red-600">
                                            Hapus
                                        </button>
                                    @endcanRole
                                @else
                                    @canRole('USER')
                                    @if ($order->payment_status === 'BELUM BAYAR')
                                        @if ($uploadingOrderId !== $order->id_order)
                                            <button wire:click="showUploadForm({{ $order->id_order }})"
                                                class="bg-blue-500 text-white px-2 py-1 rounded text-xs hover:bg-blue-600">
                                                Upload
                                            </button>
                                        @else
                                            <div class="flex flex-col space-y-2 mt-2">
                                                <input type="file" wire:model="bukti_transfer" accept="image/*" class="text-xs">
                                                <div wire:loading wire:target="bukti_transfer" class="text-xs text-gray-500">
                                                    Mengunggah...
                                                </div>
                                                @error('bukti_transfer') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

                                                <div class="flex space-x-2">
                                                    <button wire:click="uploadBukti" class="bg-green-500 text-white px-2 py-1 rounded text-xs hover:bg-green-600">
                                                        Simpan
                                                    </button>
                                                    <button wire:click="cancelUpload" class="bg-red-500 text-white px-2 py-1 rounded text-xs hover:bg-red-600">
                                                        Batal
                                                    </button>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                    @endcanRole
                                @endif
                            </td>

                            <td class="py-3 px-6">{{ $order->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
<br>
    <!-- Metode Pembayaran -->
            <div class="rounded-lg shadow-md bg-white p-4 hover:shadow-lg transition border border-blue-500">
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