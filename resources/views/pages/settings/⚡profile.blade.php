<?php

use App\Concerns\ProfileValidationRules;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts.admin')] class extends Component {
    use ProfileValidationRules;

    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $job_title = '';
    public string $bio = '';
    public string $location = '';
    public string $timezone = 'UTC';
    public string $profilePhotoPath = '';

    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone ?? '';
        $this->job_title = $user->job_title ?? '';
        $this->bio = $user->bio ?? '';
        $this->location = $user->location ?? '';
        $this->timezone = $user->timezone ?? 'UTC';
        $this->profilePhotoPath = $user->profile_photo_path ?? '';
    }

    public function selectProfilePhoto(string $path): void
    {
        Auth::user()->update(['profile_photo_path' => $path]);
        $this->profilePhotoPath = $path;
    }

    public function clearProfilePhoto(): void
    {
        Auth::user()->update(['profile_photo_path' => null]);
        $this->profilePhotoPath = '';
    }

    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate($this->profileRules($user->id));

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('admin.dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    #[Computed]
    public function hasUnverifiedEmail(): bool
    {
        return Auth::user() instanceof MustVerifyEmail && ! Auth::user()->hasVerifiedEmail();
    }

    #[Computed]
    public function showDeleteUser(): bool
    {
        return ! Auth::user() instanceof MustVerifyEmail
            || (Auth::user() instanceof MustVerifyEmail && Auth::user()->hasVerifiedEmail());
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-pages::settings.layout>
        {{-- Two-column grid --}}
        <div class="flex flex-col gap-8 lg:flex-row lg:gap-12">

            {{-- Left: Photo upload card --}}
            <div class="w-full lg:w-72 shrink-0"
                x-data="{
                    uploading: false,
                    progress: 0,
                    errorMsg: '',
                    previewUrl: @js($profilePhotoPath ? Storage::disk('public')->url($profilePhotoPath) : ''),

                    async uploadFile(file) {
                        this.uploading = true;
                        this.progress = 0;
                        this.errorMsg = '';

                        const tokenEl = document.querySelector('meta[name=csrf-token]');
                        if (!tokenEl) {
                            this.errorMsg = 'CSRF token missing — please refresh the page.';
                            this.uploading = false;
                            return;
                        }

                        const formData = new FormData();
                        formData.append('file', file);
                        formData.append('_token', tokenEl.content);
                        formData.append('folder', 'profiles');
                        formData.append('disk', 'public');

                        try {
                            const data = await new Promise((resolve, reject) => {
                                const xhr = new XMLHttpRequest();
                                xhr.open('POST', '{{ route('admin.media.upload') }}');

                                xhr.upload.addEventListener('progress', (e) => {
                                    if (e.lengthComputable) {
                                        this.progress = Math.round((e.loaded / e.total) * 100);
                                    }
                                });

                                xhr.onload = () => {
                                    if (xhr.status === 201) {
                                        resolve(JSON.parse(xhr.responseText));
                                    } else {
                                        try {
                                            const err = JSON.parse(xhr.responseText);
                                            reject(err.message || err.errors?.file?.[0] || 'Upload failed');
                                        } catch {
                                            reject('Upload failed (status ' + xhr.status + ')');
                                        }
                                    }
                                };
                                xhr.onerror = () => reject('Network error');
                                xhr.send(formData);
                            });

                            this.previewUrl = data.url;
                            $wire.selectProfilePhoto(data.path);
                        } catch (err) {
                            this.errorMsg = typeof err === 'string' ? err : 'Upload failed';
                        } finally {
                            this.uploading = false;
                            this.progress = 0;
                        }
                    }
                }"
            >
                <div class="rounded-xl border border-zinc-800 bg-zinc-900 p-6">
                    <h3 class="mb-4 text-[13px] font-semibold text-zinc-300">Profile Photo</h3>

                    {{-- Avatar display --}}
                    <div class="mb-5 flex justify-center">
                        <template x-if="previewUrl">
                            <img :src="previewUrl" alt="Profile photo"
                                 class="h-24 w-24 rounded-full object-cover ring-2 ring-zinc-700" />
                        </template>
                        <template x-if="!previewUrl">
                            <div class="flex h-24 w-24 items-center justify-center rounded-full bg-zinc-700 ring-2 ring-zinc-600 text-2xl font-semibold text-zinc-300">
                                {{ Auth::user()->initials() }}
                            </div>
                        </template>
                    </div>

                    {{-- Upload zone --}}
                    <div
                        class="relative mb-4 flex cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed border-zinc-700 px-4 py-5 text-center transition-colors hover:border-zinc-500 hover:bg-zinc-800/40"
                        @click="$refs.photoInput.click()"
                    >
                        <template x-if="!uploading">
                            <div>
                                <flux:icon name="camera" class="mb-2 h-5 w-5 text-zinc-500" />
                                <p class="text-[12px] text-zinc-400">Click to upload</p>
                                <p class="text-[11px] text-zinc-600">JPG, PNG, GIF up to 5 MB</p>
                            </div>
                        </template>
                        <template x-if="uploading">
                            <div @click.stop class="w-full">
                                <p class="mb-2 text-[12px] text-zinc-400">Uploading…</p>
                                <div class="h-1 w-full rounded-full bg-zinc-800">
                                    <div class="h-1 rounded-full bg-green-500 transition-all"
                                         :style="'width:' + progress + '%'"></div>
                                </div>
                                <p class="mt-1 text-[11px] text-zinc-500" x-text="progress + '%'"></p>
                            </div>
                        </template>
                        <input
                            type="file"
                            x-ref="photoInput"
                            accept="image/*"
                            class="sr-only"
                            @change="if ($event.target.files[0]) uploadFile($event.target.files[0])"
                        />
                    </div>

                    {{-- Error --}}
                    <p x-show="errorMsg" x-text="errorMsg" class="mb-3 text-[11px] text-red-400"></p>

                    {{-- Remove link --}}
                    @if($profilePhotoPath)
                        <button
                            wire:click="clearProfilePhoto"
                            class="w-full text-center text-[12px] text-zinc-500 hover:text-red-400 transition-colors"
                        >
                            Remove photo
                        </button>
                    @endif
                </div>
            </div>

            {{-- Right: Profile form --}}
            <div class="flex-1 min-w-0">
                <form wire:submit="updateProfileInformation" class="space-y-5">

                    {{-- Name --}}
                    <flux:input
                        wire:model="name"
                        :label="__('Full Name')"
                        type="text"
                        required
                        autofocus
                        autocomplete="name"
                    />

                    {{-- Email --}}
                    <div>
                        <flux:input
                            wire:model="email"
                            :label="__('Email Address')"
                            type="email"
                            required
                            autocomplete="email"
                        />

                        @if ($this->hasUnverifiedEmail)
                            <div class="mt-2">
                                <flux:text class="text-[12px]">
                                    {{ __('Your email address is unverified.') }}
                                    <flux:link class="cursor-pointer text-[12px]" wire:click.prevent="resendVerificationNotification">
                                        {{ __('Re-send verification email.') }}
                                    </flux:link>
                                </flux:text>
                                @if (session('status') === 'verification-link-sent')
                                    <flux:text class="mt-1 text-[12px] !text-green-400">
                                        {{ __('A new verification link has been sent to your email address.') }}
                                    </flux:text>
                                @endif
                            </div>
                        @endif
                    </div>

                    {{-- Phone + Job Title (2-col on md+) --}}
                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <flux:input
                            wire:model="phone"
                            :label="__('Phone')"
                            type="tel"
                            autocomplete="tel"
                            placeholder="+234 000 000 0000"
                        />
                        <flux:input
                            wire:model="job_title"
                            :label="__('Job Title')"
                            type="text"
                            placeholder="e.g. Trade Liaison Officer"
                        />
                    </div>

                    {{-- Location + Timezone (2-col on md+) --}}
                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <flux:input
                            wire:model="location"
                            :label="__('Location')"
                            type="text"
                            placeholder="e.g. Lagos, Nigeria"
                        />
                        <div>
                            <flux:label>{{ __('Timezone') }}</flux:label>
                            <select
                                wire:model="timezone"
                                class="mt-1 w-full rounded-lg border border-zinc-700 bg-zinc-800 px-3 py-2 text-[13px] text-zinc-200 focus:border-zinc-500 focus:outline-none focus:ring-1 focus:ring-zinc-500"
                            >
                                @foreach ([
                                    'UTC'                   => 'UTC',
                                    'Africa/Lagos'          => 'Africa/Lagos (WAT)',
                                    'Africa/Nairobi'        => 'Africa/Nairobi (EAT)',
                                    'Africa/Cairo'          => 'Africa/Cairo (EET)',
                                    'Africa/Johannesburg'   => 'Africa/Johannesburg (SAST)',
                                    'Africa/Accra'          => 'Africa/Accra (GMT)',
                                    'Africa/Abidjan'        => 'Africa/Abidjan (GMT)',
                                    'Europe/London'         => 'Europe/London (GMT/BST)',
                                    'Europe/Paris'          => 'Europe/Paris (CET)',
                                    'Europe/Berlin'         => 'Europe/Berlin (CET)',
                                    'America/New_York'      => 'America/New_York (EST)',
                                    'America/Chicago'       => 'America/Chicago (CST)',
                                    'America/Los_Angeles'   => 'America/Los_Angeles (PST)',
                                    'Asia/Dubai'            => 'Asia/Dubai (GST)',
                                    'Asia/Kolkata'          => 'Asia/Kolkata (IST)',
                                    'Asia/Singapore'        => 'Asia/Singapore (SGT)',
                                    'Asia/Tokyo'            => 'Asia/Tokyo (JST)',
                                    'Australia/Sydney'      => 'Australia/Sydney (AEST)',
                                ] as $tz => $label)
                                    <option value="{{ $tz }}" {{ $timezone === $tz ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('timezone')
                                <p class="mt-1 text-[12px] text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Bio --}}
                    <div>
                        <flux:label>{{ __('Bio') }}</flux:label>
                        <textarea
                            wire:model="bio"
                            rows="3"
                            maxlength="500"
                            placeholder="A short bio about yourself…"
                            class="mt-1 w-full resize-none rounded-lg border border-zinc-700 bg-zinc-800 px-3 py-2.5 text-[13px] text-zinc-200 placeholder:text-zinc-600 focus:border-zinc-500 focus:outline-none focus:ring-1 focus:ring-zinc-500"
                        ></textarea>
                        @error('bio')
                            <p class="mt-1 text-[12px] text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-[11px] text-zinc-600">{{ strlen($bio) }}/500</p>
                    </div>

                    {{-- Save --}}
                    <div class="flex items-center gap-4 pt-2">
                        <flux:button variant="primary" type="submit" data-test="update-profile-button">
                            {{ __('Save Changes') }}
                        </flux:button>
                        <x-action-message on="profile-updated">
                            <span class="text-[12px] text-green-400">{{ __('Saved.') }}</span>
                        </x-action-message>
                    </div>
                </form>
            </div>
        </div>

        {{-- Separator + Delete account --}}
        @if ($this->showDeleteUser)
            <div class="mt-10 border-t border-zinc-800 pt-8">
                <livewire:pages::settings.delete-user-form />
            </div>
        @endif
    </x-pages::settings.layout>
</section>

<script>
function uploadProfilePhoto(input) {
    const file = input.files[0];
    if (!file) return;

    const tokenEl = document.querySelector('meta[name="csrf-token"]');
    if (!tokenEl) {
        showPhotoError('CSRF token missing — please refresh the page.');
        return;
    }

    const progressWrap = document.getElementById('photo-upload-progress');
    const progressBar  = document.getElementById('photo-upload-bar');
    const progressPct  = document.getElementById('photo-upload-pct');
    const errorEl      = document.getElementById('photo-upload-error');

    errorEl.classList.add('hidden');
    progressWrap.classList.remove('hidden');
    progressBar.style.width = '0%';
    progressPct.textContent = '0%';

    const formData = new FormData();
    formData.append('file', file);
    formData.append('folder', 'profiles');
    formData.append('disk', 'public');

    const xhr = new XMLHttpRequest();
    xhr.open('POST', '{{ route('admin.media.upload') }}');
    xhr.setRequestHeader('X-CSRF-TOKEN', tokenEl.content);
    xhr.setRequestHeader('Accept', 'application/json');

    xhr.upload.addEventListener('progress', (e) => {
        if (e.lengthComputable) {
            const pct = Math.round((e.loaded / e.total) * 100);
            progressBar.style.width = pct + '%';
            progressPct.textContent = pct + '%';
        }
    });

    xhr.addEventListener('load', () => {
        progressWrap.classList.add('hidden');
        input.value = '';

        if (xhr.status === 201) {
            const d = JSON.parse(xhr.responseText);
            // Update preview immediately
            const preview = document.getElementById('profile-photo-preview');
            if (preview.tagName === 'IMG') {
                preview.src = d.url;
            } else {
                const img = document.createElement('img');
                img.id = 'profile-photo-preview';
                img.src = d.url;
                img.alt = 'Profile photo';
                img.className = 'h-24 w-24 rounded-full object-cover ring-2 ring-zinc-700';
                preview.replaceWith(img);
            }
            // Persist via Livewire (Livewire.find works in plain script tags)
            Livewire.find('{{ $this->getId() }}').selectProfilePhoto(d.path);
        } else {
            let msg = 'Upload failed.';
            try { msg = JSON.parse(xhr.responseText).message || msg; } catch {}
            showPhotoError(msg);
        }
    });

    xhr.addEventListener('error', () => {
        progressWrap.classList.add('hidden');
        showPhotoError('Network error during upload.');
    });

    xhr.send(formData);
}

function showPhotoError(msg) {
    const el = document.getElementById('photo-upload-error');
    el.textContent = msg;
    el.classList.remove('hidden');
}
</script>
