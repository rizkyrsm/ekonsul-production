<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component {
    public string $name;
    public string $email;

    public function mount()
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    public function updateProfileInformation()
    {
        $this->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'name')->ignore(Auth::id()),
            ],
        ]);

        Auth::user()->update([
            'name' => $this->name,
        ]);

        $this->dispatch('profile-updated');
    }
};
?>
<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Akun')" :subheading="__('Edit Username dan Email')">
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">

            @if (auth()->user()->role === 'USER')
                <flux:input
                    wire:model="name"
                    :label="__('Username')"
                    type="text"
                    required
                    autofocus
                    autocomplete="name"
                />
            @else
                <div>
                    <flux:input
                        value="{{ auth()->user()->name }}"
                        :label="__('Username')"
                        type="text"
                        readonly
                        disabled
                        class="opacity-50 cursor-not-allowed"
                    />
                    <flux:text class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        {{ __('Hanya pengguna dengan role USER yang dapat mengubah username.') }}
                    </flux:text>
                </div>
            @endif

            <div>
                <flux:input
                    wire:model="email"
                    :label="__('Email')"
                    type="email"
                    readonly
                    disabled
                    class="opacity-50 cursor-not-allowed"
                    autocomplete="email"
                />

                <flux:text class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    {{ __('Jika ingin merubah email, silakan hubungi admin.') }}
                </flux:text>

                @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !auth()->user()->hasVerifiedEmail())
                    <div>
                        <flux:text class="mt-4">
                            {{ __('Your email address is unverified.') }}

                            <flux:link class="text-sm cursor-pointer" wire:click.prevent="resendVerificationNotification">
                                {{ __('Click here to re-send the verification email.') }}
                            </flux:link>
                        </flux:text>

                        @if (session('status') === 'verification-link-sent')
                            <flux:text class="mt-2 font-medium !dark:text-green-400 !text-green-600">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </flux:text>
                        @endif
                    </div>
                @endif
            </div>

            @if (auth()->user()->role === 'USER')
                <div class="flex items-center gap-4">
                    <div class="flex items-center justify-end">
                        <flux:button variant="primary" type="submit" class="w-full">{{ __('Simpan') }}</flux:button>
                    </div>

                    <x-action-message class="me-3" on="profile-updated">
                        {{ __('Tersimpan.') }}
                    </x-action-message>
                </div>
            @endif
        </form>

        <livewire:settings.delete-user-form />
    </x-settings.layout>
</section>
