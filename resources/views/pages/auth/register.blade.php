<x-layouts::auth>
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Create account')" :description="__('Fill in your details to join the chamber workspace')" />

        <!-- Session Status -->
        <x-auth-session-status :status="session('status')" />

        <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-5">
            @csrf

            <!-- Name -->
            <flux:input
                name="name"
                :label="__('Full name')"
                :value="old('name')"
                type="text"
                required
                autofocus
                autocomplete="name"
                :placeholder="__('Your full name')"
            />

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('Email address')"
                :value="old('email')"
                type="email"
                required
                autocomplete="email"
                placeholder="you@example.com"
            />

            <!-- Password -->
            <flux:input
                name="password"
                :label="__('Password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Choose a strong password')"
                viewable
            />

            <!-- Confirm Password -->
            <flux:input
                name="password_confirmation"
                :label="__('Confirm password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Repeat your password')"
                viewable
            />

            <flux:button type="submit" variant="primary" class="w-full" data-test="register-user-button">
                {{ __('Create account') }}
            </flux:button>
        </form>

        <div class="rounded-xl border border-zinc-700/50 bg-zinc-800/50 px-4 py-3 text-center text-sm text-zinc-400">
            {{ __('Already have an account?') }}
            <a href="{{ route('login') }}"
               class="ml-1 font-medium text-green-400 transition hover:text-green-300"
               wire:navigate>{{ __('Sign in') }}</a>
        </div>
    </div>
</x-layouts::auth>
