<x-layouts::auth>
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Sign in')" :description="__('Enter your email and password to access your workspace')" />

        <!-- Session Status -->
        <x-auth-session-status :status="session('status')" />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-5">
            @csrf

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('Email address')"
                :value="old('email')"
                type="email"
                required
                autofocus
                autocomplete="email"
                placeholder="you@example.com"
            />

            <!-- Password -->
            <div class="relative">
                <flux:input
                    name="password"
                    :label="__('Password')"
                    type="password"
                    required
                    autocomplete="current-password"
                    :placeholder="__('Password')"
                    viewable
                />

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                       class="absolute end-0 top-0 text-xs text-green-400 transition hover:text-green-300"
                       wire:navigate>
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>

            <!-- Remember Me -->
            <flux:checkbox name="remember" :label="__('Keep me signed in')" :checked="old('remember')" />

            <flux:button variant="primary" type="submit" class="w-full" data-test="login-button">
                {{ __('Sign in') }}
            </flux:button>
        </form>

        @if (Route::has('register'))
            <div class="rounded-xl border border-zinc-700/50 bg-zinc-800/50 px-4 py-3 text-center text-sm text-zinc-400">
                {{ __("Don't have an account?") }}
                <a href="{{ route('register') }}"
                   class="ml-1 font-medium text-green-400 transition hover:text-green-300"
                   wire:navigate>{{ __('Create one') }}</a>
            </div>
        @endif
    </div>
</x-layouts::auth>
