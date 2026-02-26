<x-layouts::auth>
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Reset password')" :description="__('Enter your email and we\'ll send you a reset link')" />

        <!-- Session Status -->
        <x-auth-session-status :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-5">
            @csrf

            <flux:input
                name="email"
                :label="__('Email address')"
                type="email"
                required
                autofocus
                placeholder="you@example.com"
            />

            <flux:button variant="primary" type="submit" class="w-full" data-test="email-password-reset-link-button">
                {{ __('Send reset link') }}
            </flux:button>
        </form>

        <div class="rounded-xl border border-zinc-700/50 bg-zinc-800/50 px-4 py-3 text-center text-sm text-zinc-400">
            {{ __('Remembered your password?') }}
            <a href="{{ route('login') }}"
               class="ml-1 font-medium text-green-400 transition hover:text-green-300"
               wire:navigate>{{ __('Sign in') }}</a>
        </div>
    </div>
</x-layouts::auth>
