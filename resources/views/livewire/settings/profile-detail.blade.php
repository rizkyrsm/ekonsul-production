<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Profile Details')" :subheading="__('Update your profile details')">
        @if (session()->has('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form wire:submit.prevent="save" class="my-6 w-full space-y-6">
            <flux:input wire:model="nama" :label="__('Nama')" type="text" required autocomplete="name" />
            @error('nama') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

            <flux:input 
                wire:model="nik" 
                :label="__('NIK')" 
                type="text" 
                inputmode="numeric" 
                pattern="^\d{16}$" 
                minlength="16" 
                maxlength="16" 
                required 
            />
            @error('nik') 
                <span class="text-red-600 text-sm">{{ $message }}</span> 
            @enderror


            <flux:input wire:model="tgl_lahir" :label="__('Tanggal Lahir')" type="date" required />
            @error('tgl_lahir') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

            {{-- <flux:input wire:model="alamat" :label="__('Alamat')" type="text" required />
            @error('alamat') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror --}}

            <flux:input wire:model="no_tlp" :label="__('No Telepon')" type="text" inputmode="numeric" pattern="[0-9]*" required />
            @error('no_tlp') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

            {{-- <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Status Online') }}</label>
                <select wire:model="status_online" class="w-full dark:bg-gray-700 rounded-md border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500">
                    <option class="dark: bg-gray-600" value="">-- Pilih --</option>
                    <option class="dark: bg-gray-600" value="online">online</option>
                    <option class="dark: bg-gray-600" value="offline">offline</option>
                </select>
                @error('status_online') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div> --}}

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Jenis Kelamin') }}</label>
                <select wire:model="jenis_kelamin" class="w-full bg-white rounded-md dark:bg-gray-700 border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500">
                    <option class="dark: bg-gray-600" value="">-- Pilih --</option>
                    <option class="dark: bg-gray-600" value="LAKI-LAKI">LAKI-LAKI</option>
                    <option class="dark: bg-gray-600" value="PEREMPUAN">PEREMPUAN</option>
                    <option class="dark: bg-gray-600" value="LAINYA">LAINYA</option>
                </select>
                @error('jenis_kelamin') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <flux:input wire:model="tempat_lahir" :label="__('Tempat Lahir')" type="text" required />
                @error('tempat_lahir') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            
            <flux:input wire:model="pekerjaan" :label="__('Pekerjaan')" type="text" required />
                @error('pekerjaan') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Status Pernikahan') }}</label>
                    <select wire:model="status_pernikahan" class="w-full bg-white rounded-md dark:bg-gray-700 border-gray-300 shadow-sm">
                        <option value="">-- Pilih --</option>
                        <option value="MENIKAH">MENIKAH</option>
                        <option value="BELUM MENIKAH">BELUM MENIKAH</option>
                        <option value="TIDAK MENIKAH">TIDAK MENIKAH</option>
                    </select>
                    @error('status_pernikahan') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Agama') }}</label>
                    <select wire:model="agama" class="w-full bg-white rounded-md dark:bg-gray-700 border-gray-300 shadow-sm">
                        <option value="">-- Pilih --</option>
                        <option value="Islam">Islam</option>
                        <option value="Kristen">Kristen</option>
                        <option value="Katolik">Katolik</option>
                        <option value="Hindu">Hindu</option>
                        <option value="Buddha">Buddha</option>
                        <option value="Konghucu">Konghucu</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                    @error('agama') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="alamat" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Alamat') }}</label>
                    <textarea wire:model="alamat" rows="4" id="alamat" class="w-full bg-white rounded-md dark:bg-gray-700 border-gray-300 shadow-sm"></textarea>
                    @error('alamat') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

            <div class="flex items-center gap-4">
                <flux:button type="submit" variant="primary" class="w-full">
                    {{ __('Simpan') }}
                </flux:button>

                <x-action-message class="me-3" on="profile-updated">
                    {{ __('Tersimpan.') }}
                </x-action-message>
            </div>
        </form>

        <div class="mt-10">
            <livewire:settings.delete-user-form />
        </div>
    </x-settings.layout>
</section>

