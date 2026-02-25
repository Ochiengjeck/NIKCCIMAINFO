<x-layouts::auth>
    <div class="flex flex-col gap-6">
        <x-auth-header
            :title="__('Confirm password')"
            :description="__('This is a secure area of the application. Please confirm your password before continuing.')"
        />

        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.confirm.store') }}" class="flex flex-col gap-6">
            @csrf

            <flux:input
                name="password"
                :label="__('Password')"
                type="password"
                required
                autocomplete="current-password"
                :placeholder="__('Password')"
                viewable
            />

            <flux:button variant="primary" type="submit" class="w-full" data-test="confirm-password-button">
                {{ __('Confirm') }}
            </flux:button>
        </form>

        <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-center text-sm text-slate-600">
            <span>{{ __('Need a different account?') }}</span>
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <flux:button variant="ghost" type="submit" class="w-full text-sm cursor-pointer">
                    {{ __('Log out') }}
                </flux:button>
            </form>
        </div>
    </div>
</x-layouts::auth>
