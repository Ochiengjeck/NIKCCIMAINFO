<x-layouts::auth>
    <div class="flex flex-col gap-6">
        <x-auth-header
            :title="__('Confirm password')"
            :description="__('This is a secure area. Please confirm your password before continuing')"
        />

        <x-auth-session-status :status="session('status')" />

        <form method="POST" action="{{ route('password.confirm.store') }}" class="flex flex-col gap-5">
            @csrf

            <flux:input
                name="password"
                :label="__('Password')"
                type="password"
                required
                autocomplete="current-password"
                :placeholder="__('Your current password')"
                viewable
            />

            <flux:button variant="primary" type="submit" class="w-full" data-test="confirm-password-button">
                {{ __('Confirm & continue') }}
            </flux:button>
        </form>

        <div class="rounded-xl border border-zinc-700/50 bg-zinc-800/50 px-4 py-3 text-center text-sm text-zinc-400">
            <span>{{ __('Wrong account?') }}</span>
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <flux:button variant="ghost" type="submit" class="w-full text-sm">
                    {{ __('Sign out') }}
                </flux:button>
            </form>
        </div>
    </div>
</x-layouts::auth>
