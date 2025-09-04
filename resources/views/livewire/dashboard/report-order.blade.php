{{-- <div class="p-6 bg-gray-100 min-h-screen"> --}}
    <div class="p-6 bg-gray-100 min-h-screen bg-white shadow-lg rounded-lg p-6">
        <h2 class="text-xl font-bold mb-4 text-gray-700">📊 Detail Konseling Selesai</h2>

        <!-- Filter Form -->
        <div class="flex flex-col md:flex-row items-center gap-4 mb-6">
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-600">Dari</label>
                <input type="date" wire:model.defer="start_date"
                    class="px-3 py-2 border rounded-lg text-sm focus:ring focus:ring-blue-300 dark:text-white-400 bg-green-500" />
            </div>
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-600">Sampai</label>
                <input type="date" wire:model.defer="end_date"
                    class="px-3 py-2 border rounded-lg text-sm focus:ring focus:ring-blue-300 dark:text-white-400 bg-green-500" />
            </div>
            <button wire:click="search"
                class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg shadow hover:bg-blue-700 transition">
                🔍 Cari
            </button>
            <a href="{{ route('dashboard.report-order.export', [
                    'start_date' => $start_date,
                    'end_date'   => $end_date
                ]) }}"
                target="_blank"
                class="px-4 py-2v bg-green-600 text-white text-sm rounded-lg shadow dark:text-white-400 bg-green-500 hover:bg-green-700 transition">
                📥 Export Excel
            </a>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="w-full border-collapse text-sm text-gray-700">
                <thead>
                    <tr class="bg-gray-50 border-b">
                        <th class="px-4 py-2 text-left">ID Order</th>
                        <th class="px-4 py-2 text-left">Nama User</th>
                        <th class="px-4 py-2 text-left">NIK</th>
                        <th class="px-4 py-2 text-left">Tgl Lahir</th>
                        <th class="px-4 py-2 text-left">Tempat Lahir</th>
                        <th class="px-4 py-2 text-left">Alamat</th>
                        <th class="px-4 py-2 text-left">No Tlp</th>
                        <th class="px-4 py-2 text-left">Status Online</th>
                        <th class="px-4 py-2 text-left">Jenis Kelamin</th>
                        <th class="px-4 py-2 text-left">Status Pernikahan</th>
                        <th class="px-4 py-2 text-left">Agama</th>
                        <th class="px-4 py-2 text-left">Pekerjaan</th>
                        <th class="px-4 py-2 text-left">Konselor</th>
                        <th class="px-4 py-2 text-left">Layanan</th>
                        <th class="px-4 py-2 text-left">Mulai Konseling</th>
                        <th class="px-4 py-2 text-left">Selesai Konseling</th>
                        <th class="px-4 py-2 text-center">Link Chat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50 border-b">
                            <td class="px-4 py-2">{{ $order->id_order }}</td>
                            <td class="px-4 py-2">{{ $order->nama_user }}</td>
                            <td class="px-4 py-2">{{ $order->nik }}</td>
                            <td class="px-4 py-2">{{ $order->tgl_lahir ? \Carbon\Carbon::parse($order->tgl_lahir)->translatedFormat('d F Y') : '-' }}</td>
                            <td class="px-4 py-2">{{ $order->tempat_lahir }}</td>
                            <td class="px-4 py-2">{{ $order->alamat }}</td>
                            <td class="px-4 py-2">{{ $order->no_tlp }}</td>
                            <td class="px-4 py-2">{{ $order->status_online }}</td>
                            <td class="px-4 py-2">{{ $order->jenis_kelamin }}</td>
                            <td class="px-4 py-2">{{ $order->status_pernikahan }}</td>
                            <td class="px-4 py-2">{{ $order->agama }}</td>
                            <td class="px-4 py-2">{{ $order->pekerjaan }}</td>
                            <td class="px-4 py-2">{{ $order->nama_konselor }}</td>
                            <td class="px-4 py-2">{{ $order->nama_layanan }}</td>
                            <td class="px-4 py-2">{{ \Carbon\Carbon::parse($order->mulai_konseling)->format('d-m-Y H:i') }}</td>
                            <td class="px-4 py-2">{{ \Carbon\Carbon::parse($order->selesai_konseling)->format('d-m-Y H:i') }}</td>
                            <td class="px-4 py-2 text-center">
                                <a href="{{ url('allChat/' . $order->id_user) }}"  target="_blank"
                                class="px-3 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                                Lihat
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="17" class="px-4 py-4 text-center text-gray-500">Tidak ada data ditemukan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    </div>
{{-- </div> --}}
