<x-layouts::auth>
    <div class="flex flex-col gap-6">
        <div
            class="relative w-full"
            x-cloak
            x-data="{
                showRecoveryInput: @js($errors->has('recovery_code')),
                code: '',
                recovery_code: '',
                toggleInput() {
                    this.showRecoveryInput = !this.showRecoveryInput;
                    this.code = '';
                    this.recovery_code = '';
                    $dispatch('clear-2fa-auth-code');
                    $nextTick(() => {
                        this.showRecoveryInput
                            ? this.$refs.recovery_code?.focus()
                            : $dispatch('focus-2fa-auth-code');
                    });
                },
            }"
        >
            <div x-show="!showRecoveryInput">
                <x-auth-header
                    :title="__('Two-factor auth')"
                    :description="__('Enter the 6-digit code from your authenticator app')"
                />
            </div>

            <div x-show="showRecoveryInput">
                <x-auth-header
                    :title="__('Recovery code')"
                    :description="__('Enter one of your emergency recovery codes to access your account')"
                />
            </div>

            <form method="POST" action="{{ route('two-factor.login.store') }}" class="mt-6 space-y-5">
                @csrf

                <div class="space-y-5 text-center">
                    <div x-show="!showRecoveryInput">
                        <div class="my-2 flex items-center justify-center rounded-xl border border-zinc-700/50 bg-zinc-800/50 p-5">
                            <flux:otp
                                x-model="code"
                                length="6"
                                name="code"
                                label="OTP Code"
                                label:sr-only
                                class="mx-auto"
                            />
                        </div>
                    </div>

                    <div x-show="showRecoveryInput">
                        <div class="my-2">
                            <flux:input
                                type="text"
                                name="recovery_code"
                                x-ref="recovery_code"
                                x-bind:required="showRecoveryInput"
                                autocomplete="one-time-code"
                                x-model="recovery_code"
                                placeholder="xxxx-xxxx-xxxx-xxxx"
                            />
                        </div>

                        @error('recovery_code')
                            <p class="text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <flux:button variant="primary" type="submit" class="w-full">
                        {{ __('Continue') }}
                    </flux:button>
                </div>

                <div class="rounded-xl border border-zinc-700/50 bg-zinc-800/50 px-4 py-3 text-center text-sm text-zinc-400">
                    <span>{{ __('or') }}</span>
                    <button type="button" @click="toggleInput()"
                        class="ml-1 font-medium text-green-400 underline decoration-zinc-600 underline-offset-4 transition hover:text-green-300">
                        <span x-show="!showRecoveryInput">{{ __('use a recovery code') }}</span>
                        <span x-show="showRecoveryInput">{{ __('use an authentication code') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts::auth>
