<x-layouts::auth>
    <div class="flex flex-col gap-6">
        <x-auth-header
            :title="__('Verify your email')"
            :description="__('Confirm your email address to activate your account and continue')"
        />

        <p class="rounded-xl border border-zinc-700/50 bg-zinc-800/50 px-4 py-3 text-center text-sm text-zinc-400">
            {{ __('Please verify your email address by clicking on the link we just emailed to you.') }}
        </p>

        @if (session('status') == 'verification-link-sent')
            <p class="rounded-xl border border-green-500/30 bg-green-500/10 px-4 py-3 text-center text-sm font-medium text-green-300">
                {{ __('A new verification link has been sent to your email address.') }}
            </p>
        @endif

        <div class="flex flex-col gap-3">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <flux:button type="submit" variant="primary" class="w-full">
                    {{ __('Resend verification email') }}
                </flux:button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <flux:button variant="ghost" type="submit" class="w-full text-sm" data-test="logout-button">
                    {{ __('Sign out') }}
                </flux:button>
            </form>
        </div>
    </div>
</x-layouts::auth>
