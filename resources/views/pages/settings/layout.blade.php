@props(['heading' => '', 'subheading' => ''])

@php
    $tabActive   = 'pb-3 border-b-2 border-green-500 text-[13px] font-medium text-green-400 px-3 whitespace-nowrap';
    $tabInactive = 'pb-3 border-b-2 border-transparent text-[13px] font-medium text-zinc-500 hover:text-zinc-300 px-3 whitespace-nowrap transition-colors';
@endphp

<div class="w-full">
    {{-- Screen-reader / test-accessible heading (tabs are the visual nav) --}}
    @if ($heading)
        <span class="sr-only">{{ $heading }}</span>
    @endif
    {{-- Horizontal tab nav --}}
    <div class="mb-8 flex gap-1 overflow-x-auto border-b border-zinc-800 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
        <a href="{{ route('profile.edit') }}" wire:navigate
           class="{{ Route::is('profile.edit') ? $tabActive : $tabInactive }}">
            Profile
        </a>
        <a href="{{ route('user-password.edit') }}" wire:navigate
           class="{{ Route::is('user-password.edit') ? $tabActive : $tabInactive }}">
            Password
        </a>
        @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
            <a href="{{ route('two-factor.show') }}" wire:navigate
               class="{{ Route::is('two-factor.show') ? $tabActive : $tabInactive }}">
                Security
            </a>
        @endif
        <a href="{{ route('appearance.edit') }}" wire:navigate
           class="{{ Route::is('appearance.edit') ? $tabActive : $tabInactive }}">
            Appearance
        </a>
    </div>

    {{-- Page content --}}
    <div class="w-full">
        {{ $slot }}
    </div>
</div>
