<div class="flex flex-col md:flex-row gap-6 p-6 dark:bg-gray-700 rounded-2xl shadow min-h-screen">
    {{-- Kolom Kiri: Form --}}
    <div class="md:w-1/4 bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
        <h2 class="text-xl font-semibold mb-4">{{ $updateMode ? 'Edit Layanan' : 'Tambah Layanan' }}</h2>

        @if (session()->has('message'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4 text-sm">
                {{ session('message') }}
            </div>
        @endif

        <form wire:submit.prevent="{{ $updateMode ? 'update' : 'store' }}" class="space-y-4">
            <div>
                <label>Nama Layanan</label>
                <input wire:model="nama_layanan" type="text" class="w-full border rounded px-3 py-2" />
                @error('nama_layanan') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Warna Layanan</label>
                <div class="relative">
                    <input wire:model="warna_layanan" type="color" 
                        class="w-full h-12 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white transition duration-300 ease-in-out">
                    
                    <!-- Menambahkan preview warna di samping -->
                    <span class="absolute right-3 top-3 text-gray-500 dark:text-gray-300">
                        <i class="fas fa-palette"></i>
                    </span>
                </div>
                @error('warna_layanan') 
                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                @enderror
            </div>
            

            <div>
                <label>Harga Layanan</label>
                <input wire:model="harga_layanan" type="number" class="w-full border rounded px-3 py-2" />
                @error('harga_layanan') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

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

    {{-- Kolom Kanan: List --}}
    <div class="md:w-3/4 bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
        <h2 class="text-xl font-semibold mb-4">Daftar Layanan</h2>

        <div class="flex flex-col md:flex-row gap-3 mb-4">
            <input
                wire:model.live.debounce.300ms="search"
                type="text"
                placeholder="Cari nama..."
                class="flex-1 border border-gray-300 rounded-lg px-3 py-2"
            />
        </div>

        <table class="w-full text-sm border">
            <thead class="bg-gray-100 text-gray-700 font-semibold">
                <tr>
                    <th class="px-4 py-2 text-left">Nama</th>
                    <th class="px-4 py-2 text-left">Warna</th>
                    <th class="px-4 py-2 text-left">Harga</th>
                    <th class="px-4 py-2 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($layanans as $layanan)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $layanan->nama_layanan }}</td>
                        <td class="px-4 py-2">
                            <span class="inline-block w-4 h-4 rounded-full" style="background: {{ $layanan->warna_layanan }}"></span>
                            {{ $layanan->warna_layanan }}
                        </td>
                        <td class="px-4 py-2">Rp{{ number_format($layanan->harga_layanan, 0, ',', '.') }}</td>
                        <td class="px-4 py-2 flex gap-2">
                            <button wire:click="edit({{ $layanan->id_layanan }})" class="text-blue-600 hover:bg-sky-100 hover:underline"><x-heroicon-o-pencil-square class="w-5 h-5 text-blue-700" /></button>
                            <button wire:click="delete({{ $layanan->id_layanan }})" wire:confirm="Apakah Anda yakin ingin menghapus ini?" class="text-red-600 hover:bg-red-100 hover:underline"><x-heroicon-o-trash class="w-5 h-5 text-red-700" /></button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-gray-500 py-4">Data tidak ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
