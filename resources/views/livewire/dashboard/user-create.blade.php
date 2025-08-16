<div class="flex flex-col md:flex-row gap-6 p-6 dark:bg-gray-700 rounded-2xl shadow min-h-screen">

    @php
        $isInactive = Auth::user()->status === 'NONACTIVE';
    @endphp
    @if($isInactive) {{ 'AKUN ANDA TIDAK AKTIV' }} @else

    {{-- Kolom Status --}}

    {{-- Kolom Form --}}
    <div class="md:w-1/4 bg-white dark:bg-gray-800 p-6 rounded-2xl shadow">
        <h2 class="text-2xl font-semibold mb-4 flex items-center gap-2">
            @if($editingId)
                <x-heroicon-o-pencil class="w-5 h-5 text-yellow-600" />
                Edit User
            @else
                <x-heroicon-o-user-plus class="w-5 h-5 text-blue-600" />
                Tambah User Baru
            @endif
        </h2>

        @if (session()->has('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <form wire:submit.prevent="submit" class="space-y-4">
            <div>
                <label class="block text-sm font-medium mb-1">Username</label>
                <input wire:model="name" type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2" />
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Email</label>
                <input wire:model="email" type="email" class="w-full border border-gray-300 rounded-lg px-3 py-2" @if($editingId) disabled @endif />
                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">{{ $editingId ? 'Password Baru (opsional)' : 'Password' }}</label>
                <input wire:model="password" type="password" class="w-full border border-gray-300 rounded-lg px-3 py-2" />
                @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Role</label>
                @canRole('ADMIN')
                <select wire:model="role" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option class="dark: bg-gray-600" value="">Pilih Role</option>
                    <option class="dark: bg-gray-600" value="ADMIN">ADMIN</option>
                    <option class="dark: bg-gray-600" value="CABANG">CABANG</option>
                    {{-- <option class="dark: bg-gray-600" value="KONSELOR">KONSELOR</option> --}}
                </select>
                @endcanRole
                @canRole('CABANG')
                <select wire:model="role" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option class="dark: bg-gray-600" value="">Pilih Role</option>
                    <option class="dark: bg-gray-600" value="KONSELOR">KONSELOR</option>
                </select>
                @endcanRole
                @error('role') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-xl hover:bg-blue-700">
                    {{ $editingId ? 'Update' : 'Simpan' }}
                </button>
                @if($editingId)
                    <button type="button" wire:click="resetForm" class="bg-gray-400 text-white px-4 py-2 rounded-xl">
                        Batal
                    </button>
                @endif
            </div>
        </form>
    </div>

    @php
        $userRole = Auth()->user()->role;
    @endphp

    @if ($userRole === 'USER')
        <div class="text-center text-gray-600 text-lg mt-4">
            Riwayat Konseling
        </div>
    @else

    {{-- Kolom List --}}
    <div class="md:w-3/4 bg-white dark:bg-gray-800 p-6 rounded-2xl shadow">
        <h2 class="text-2xl font-semibold mb-4 flex items-center gap-2">
            <x-heroicon-o-user-group class="w-5 h-5 text-blue-700" />
            Daftar User
        </h2>

        {{-- Search Bar + Filter --}}
        <div class="flex flex-col md:flex-row gap-3 mb-4">
            <input
                wire:model.live.debounce.300ms="search"
                type="text"
                placeholder="Cari nama/email..."
                class="flex-1 border border-gray-300 rounded-lg px-3 py-2"
            />
        </div>

        {{-- Table --}}
<div class="overflow-x-auto">
    <table class="min-w-full text-sm text-left border">
        <thead class="bg-gray-100 text-gray-700 font-semibold">
            <tr>
                <th class="py-2 px-3">Username</th>
                <th class="py-2 px-3">Cabang</th>
                <th class="py-2 px-3">Email</th>
                <th class="py-2 px-3">Role</th>
                <th class="py-2 px-3">Aksi</th>
                <th class="py-2 px-3">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse ($users as $user)
                <tr>
                    <td class="py-2 px-3">{{ $user->name }}</td>
                    <td class="py-2 px-3">{{ $user->cabang_name ?? '' }}</td>
                    <td class="py-2 px-3">{{ $user->email }}</td>
                    <td class="py-2 px-3 capitalize">{{ $user->role }}</td>
                    <td class="py-2 px-3 space-x-2">
                        <button wire:click="edit({{ $user->id }})" class="text-blue-600 hover:bg-sky-100 hover:underline">
                            <x-heroicon-o-pencil-square class="w-5 h-5 text-blue-700" />
                        </button>
                        <button wire:click="delete({{ $user->id }})" wire:confirm="Apakah Anda yakin ingin menghapus user ini?" class="text-red-600 hover:bg-red-100 hover:underline">
                            <x-heroicon-o-trash class="w-5 h-5 text-red-700" />
                        </button>
                    </td>
                    <td class="py-2 px-3 space-x-2">
                        <button wire:click="toggleStatus({{ $user->id }})" class="text-sm px-2 py-1 rounded {{ $user->status === 'ACTIVE' ? 'bg-green-500 text-white' : 'bg-red-400 text-white' }}">
                            {{ $user->status === 'ACTIVE' ? 'ACTIVE' : 'NONACTIVE' }}
                        </button>
                        @canRole('CABANG')
                        <button wire:click="toggleStatusChat({{ $user->id }})" class="text-sm px-2 py-1 rounded {{ $user->status_online === 'online' ? 'bg-green-500 text-white' : 'bg-red-400 text-white' }}">
                            <i class="bi bi-wifi"></i> {{ $user->status_online === 'online' ? 'online' : 'offline' }}
                        </button>
                        @endcanRole
                        @canRole('ADMIN')
                        <span class="text-sm px-2 py-1 rounded {{ $user->status_online === 'online' ? 'bg-green-500 text-white' : 'bg-red-400 text-white' }}">
                            {{ $user->status_online === 'online' ? 'online' : 'offline' }}
                        </span>
                        @endcanRole
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="py-4 text-center text-gray-500">Tidak ada data ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>


        {{-- Pagination --}}
        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
    @endif

    @endif
</div>

