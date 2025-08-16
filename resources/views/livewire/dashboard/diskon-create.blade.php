<div class="flex flex-col md:flex-row gap-6 p-6 dark:bg-gray-700 rounded-2xl shadow min-h-screen">

    <!-- Kolom Kiri: Form -->
    <div class="md:w-1/4 bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
        <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-white">
            {{ $updateMode ? 'Edit Diskon' : 'Tambah Diskon' }}
        </h2>

        @if (session()->has('message'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4 text-sm">
                {{ session('message') }}
            </div>
        @endif

        <form wire:submit.prevent="{{ $updateMode ? 'update' : 'store' }}" class="space-y-4">

            <!-- Nama Diskon -->
            <div>
                <label class="block text-gray-700 dark:text-white">Nama Diskon</label>
                <input wire:model="nama_diskon" type="text" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                @error('nama_diskon') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Kode Voucher -->
            <div>
                <label class="block text-gray-700 dark:text-white">Kode Voucher</label>
                <input wire:model="kode_voucher" type="text" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                @error('kode_voucher') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Jumlah Diskon Harga -->
            <div>
                <label class="block text-gray-700 dark:text-white">Jumlah Diskon Harga</label>
                <input wire:model="jumlah_diskon_harga" type="number" step="0.01" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                @error('jumlah_diskon_harga') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Jumlah Diskon Persen -->
            <div>
                <label class="block text-gray-700 dark:text-white">Jumlah Diskon Persen</label>
                <input wire:model="jumlah_diskon_persen" type="number" step="0.01" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                @error('jumlah_diskon_persen') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Status Aktiv -->
            <div>
                <label class="block text-gray-700 dark:text-white">Status Aktiv</label>
                <select wire:model="status_aktiv" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option class="dark: bg-gray-600" value="AKTIF">AKTIF</option>
                    <option class="dark: bg-gray-600" value="TIDAK AKTIF">TIDAK AKTIF</option>
                </select>
            </div>

            <!-- Submit Button -->
            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    {{ $updateMode ? 'Update' : 'Simpan' }}
                </button>
                @if ($updateMode)
                    <button type="button" wire:click="resetForm" class="bg-gray-400 text-white px-4 py-2 rounded">Batal</button>
                @endif
            </div>
        </form>
    </div>

    <!-- Kolom Kanan: Daftar Diskon -->
    <div class="md:w-3/4 bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
        <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-white">Daftar Diskon</h2>

        <!-- Pencarian -->
        <div class="flex flex-col md:flex-row gap-3 mb-4">
            <input
                wire:model.live.debounce.300ms="search"
                type="text"
                placeholder="Cari nama..."
                class="flex-1 border border-gray-300 rounded-lg px-3 py-2"
            />
        </div>

        <!-- Daftar Diskon -->
        <table class="w-full table-auto border border-gray-300 rounded">
            <thead class="bg-gray-100 dark:bg-gray-800">
                <tr>
                    <th class="p-2 text-left text-gray-600 dark:text-white">Nama Diskon</th>
                    <th class="p-2 text-left text-gray-600 dark:text-white">Kode Voucher</th>
                    <th class="p-2 text-left text-gray-600 dark:text-white">Potongan Persen</th>
                    <th class="p-2 text-left text-gray-600 dark:text-white">Potongan Harga</th>
                    <th class="p-2 text-left text-gray-600 dark:text-white">Status</th>
                    <th class="p-2 text-left text-gray-600 dark:text-white">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($diskons as $diskon)
                    <tr class="border-b">
                        <td class="p-2">{{ $diskon->nama_diskon }}</td>
                        <td class="p-2">{{ $diskon->kode_voucher }}</td>
                        <td class="p-2">{{ $diskon->jumlah_diskon_persen }}%</td>
                        <td class="p-2">Rp. {{ $diskon->jumlah_diskon_harga }}</td>
                        <td class="p-2">
                            @if($diskon->status_aktiv === 'AKTIF')
                                <span class="bg-green-500 text-green-800 text-sm px-2 py-1 rounded-full">AKTIF</span>
                            @else
                                <span class="bg-red-500 text-red-800 text-sm px-2 py-1 rounded-full">TIDAK AKTIF</span>
                            @endif
                        </td>
                        <td class="p-2">
                            <!-- Edit & Delete Buttons -->
                            <button wire:click="edit({{ $diskon->id_diskon }})" class="bg-yellow-500 text-white py-1 px-3 rounded hover:bg-yellow-600">Edit</button>
                            <button wire:click="delete({{ $diskon->id_diskon }})" wire:confirm="Apakah Anda yakin ingin menghapus ini?" class="bg-red-500 text-white py-1 px-3 rounded hover:bg-red-600 ml-2">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- No Data Message -->
        @if($diskons->isEmpty())
            <p class="text-gray-600 dark:text-white text-center mt-4">Tidak ada diskon yang tersedia.</p>
        @endif
    </div>
</div>
