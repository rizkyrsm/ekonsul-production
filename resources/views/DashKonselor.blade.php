<x-layouts.app :title="__('Tambah Konselor')">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 h-full w-full">
        <!-- Kolom Input Data (1/3) -->
        <div class="col-span-1 flex flex-col gap-6 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-white dark:bg-neutral-900 shadow">
            
            <x-auth-header :title="__('Tambah User Konselor')" :description="__('Tambah user baru untuk konselor')" />

            @if(session('success'))
                <div class="px-4 py-3 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-900 dark:text-green-200">
                    {{ session('success') }}
                </div>
            @endif

            @php
                $isEdit = isset($detail);
            @endphp

            <form action="{{ $isEdit ? route('konselor.update', $detail->id) : route('konselor.store') }}" method="POST" class="space-y-4">
                @csrf
                @if($isEdit)
                    @method('PUT')
                @endif

                <!-- Nama -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama</label>
                    <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-lg" value="{{ old('name', $isEdit ? $detail->name : '') }}">
                    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                    <input type="email" name="email" id="email" class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-lg" value="{{ old('email', $isEdit ? $detail->email : '') }}" {{ $isEdit ? 'disabled' : '' }} >
                    @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                    <input type="password" name="password" id="password" class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-lg">
                    @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Konfirmasi Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-lg">
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Simpan
                    </button>
                </div>
            </form>
        </div>

        <!-- Kolom List Data (2/3) -->
        <div class="col-span-2 flex flex-col gap-4 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 max-h-[91vh] overflow-y-auto bg-white dark:bg-neutral-900 shadow">
            <h2 class="text-lg font-semibold text-neutral-800 dark:text-neutral-100">List Data</h2>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                    <thead class="bg-gray-100 dark:bg-neutral-800">
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 dark:text-neutral-300">Name</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 dark:text-neutral-300">Email</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 dark:text-neutral-300">Role</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 dark:text-neutral-300">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-neutral-800">
                        @foreach ($users as $user)
                            <tr class="bg-white dark:bg-neutral-900 hover:bg-gray-50 dark:hover:bg-neutral-800 transition">
                                <td class="px-4 py-2 text-gray-900 dark:text-white">{{ $user->name }}</td>
                                <td class="px-4 py-2 text-gray-600 dark:text-gray-400">{{ $user->email }}</td>
                                <td class="px-4 py-2 text-gray-600 dark:text-gray-400 uppercase">{{ $user->role }}</td>
                                <td class="px-4 py-2 text-gray-600 dark:text-gray-400">
                                    <div class="flex space-x-2">
                                        <!-- Tombol Edit -->
                                        <a href="{{ route('konselor.edit', $user->id) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300" title="Edit">
                                            <!-- Heroicon Pencil -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 11l6.768-6.768a2 2 0 112.828 2.828L11.828 13.828a2 2 0 01-1.414.586H9v-1.414a2 2 0 01.586-1.414z" />
                                            </svg>
                                        </a>
                                
                                        <!-- Tombol Hapus -->
                                        <form action="{{ route('konselor.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus user ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300" title="Hapus">
                                                <!-- Heroicon Trash -->
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0a2 2 0 00-2-2H9a2 2 0 00-2 2m2 0v0" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
            
    </div>
</x-layouts.app>
