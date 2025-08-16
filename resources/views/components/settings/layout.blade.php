<div class="flex items-start max-md:flex-col">
    <div class="bg-white me-10 w-full pb-4 md:w-[220px] dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg shadow-md">
        <flux:navlist>
            <flux:navlist.item :href="route('settings.profile')" wire:navigate>{{ __('Akun') }}</flux:navlist.item>
            <flux:navlist.item :href="route('settings.profile-detail')" wire:navigate>{{ __('Profile Detail') }}</flux:navlist.item>
            <flux:navlist.item :href="route('settings.password')" wire:navigate>{{ __('Password') }}</flux:navlist.item>
            <flux:navlist.item :href="route('settings.appearance')" wire:navigate>{{ __('Tampilan') }}</flux:navlist.item>
        </flux:navlist>
    </div>

    <flux:separator class="md:hidden" />

    <div class="flex-1 self-stretch max-md:pt-6">
        <flux:heading>{{ $heading ?? '' }}</flux:heading>
        <flux:subheading>{{ $subheading ?? '' }}</flux:subheading>

        <div class="mt-5 w-full max-w-lg">
            {{ $slot }}
        </div>
    </div>
</div>
