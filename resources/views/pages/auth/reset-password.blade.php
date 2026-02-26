<x-layouts::auth>
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Set new password')" :description="__('Choose a strong password for your account')" />

        <!-- Session Status -->
        <x-auth-session-status :status="session('status')" />

        <form method="POST" action="{{ route('password.update') }}" class="flex flex-col gap-5">
            @csrf
            <input type="hidden" name="token" value="{{ request()->route('token') }}">

            <flux:input
                name="email"
                value="{{ request('email') }}"
                :label="__('Email address')"
                type="email"
                required
                autocomplete="email"
            />

            <flux:input
                name="password"
                :label="__('New password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Choose a strong password')"
                viewable
            />

            <flux:input
                name="password_confirmation"
                :label="__('Confirm new password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Repeat your new password')"
                viewable
            />

            <flux:button type="submit" variant="primary" class="w-full" data-test="reset-password-button">
                {{ __('Update password') }}
            </flux:button>
        </form>

        <div class="rounded-xl border border-zinc-700/50 bg-zinc-800/50 px-4 py-3 text-center text-sm text-zinc-400">
            {{ __('Password already set?') }}
            <a href="{{ route('login') }}"
               class="ml-1 font-medium text-green-400 transition hover:text-green-300"
               wire:navigate>{{ __('Sign in') }}</a>
        </div>
    </div>
</x-layouts::auth>
