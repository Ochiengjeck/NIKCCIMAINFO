@php
    $photo = auth()->user()->profilePhotoUrl();
@endphp

<flux:dropdown position="bottom" align="start">
    @if ($photo)
        <button
            type="button"
            data-test="sidebar-menu-button"
            class="flex w-full items-center gap-2.5 rounded-lg px-2 py-1.5 text-start hover:bg-zinc-800/60 transition-colors"
        >
            <img src="{{ $photo }}" alt="{{ auth()->user()->name }}"
                 class="h-8 w-8 shrink-0 rounded-full object-cover ring-1 ring-zinc-700" />
            <div class="min-w-0 flex-1">
                <p class="truncate text-[13px] font-medium text-zinc-200">{{ auth()->user()->name }}</p>
                <p class="truncate text-[11px] text-zinc-500">{{ auth()->user()->email }}</p>
            </div>
            <flux:icon name="chevrons-up-down" class="h-4 w-4 shrink-0 text-zinc-600" />
        </button>
    @else
        <flux:sidebar.profile
            :name="auth()->user()->name"
            :initials="auth()->user()->initials()"
            icon:trailing="chevrons-up-down"
            data-test="sidebar-menu-button"
        />
    @endif

    <flux:menu>
        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
            @if ($photo)
                <img src="{{ $photo }}" alt="{{ auth()->user()->name }}"
                     class="h-8 w-8 shrink-0 rounded-full object-cover ring-1 ring-zinc-700" />
            @else
                <flux:avatar
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                />
            @endif
            <div class="grid flex-1 text-start text-sm leading-tight">
                <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
            </div>
        </div>
        <flux:menu.separator />
        <flux:menu.radio.group>
            <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                {{ __('Settings') }}
            </flux:menu.item>
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <flux:menu.item
                    as="button"
                    type="submit"
                    icon="arrow-right-start-on-rectangle"
                    class="w-full cursor-pointer"
                    data-test="logout-button"
                >
                    {{ __('Log Out') }}
                </flux:menu.item>
            </form>
        </flux:menu.radio.group>
    </flux:menu>
</flux:dropdown>
