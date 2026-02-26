<div>
    <div class="mb-6">
        <flux:heading size="xl">General Settings</flux:heading>
        <flux:subheading>Configure global system settings for NiKCCIMA Backoffice</flux:subheading>
    </div>

    @if(session('success'))
        <flux:callout variant="success" class="mb-4">{{ session('success') }}</flux:callout>
    @endif

    {{-- General settings --}}
    <div class="max-w-xl rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
        <form wire:submit="save" class="space-y-4">
            <flux:field>
                <flux:label>Site Name <span class="text-red-500">*</span></flux:label>
                <flux:input wire:model="siteName" placeholder="NiKCCIMA Backoffice" />
                <flux:error name="siteName" />
            </flux:field>

            <flux:field>
                <flux:label>Default Currency <span class="text-red-500">*</span></flux:label>
                <flux:select wire:model="defaultCurrency">
                    <flux:select.option value="NGN">NGN — Nigerian Naira</flux:select.option>
                    <flux:select.option value="KES">KES — Kenyan Shilling</flux:select.option>
                    <flux:select.option value="USD">USD — US Dollar</flux:select.option>
                    <flux:select.option value="EUR">EUR — Euro</flux:select.option>
                    <flux:select.option value="GBP">GBP — British Pound</flux:select.option>
                </flux:select>
                <flux:error name="defaultCurrency" />
            </flux:field>

            <flux:field>
                <flux:label>System Notification Email</flux:label>
                <flux:input wire:model="notificationEmail" type="email" placeholder="notifications@nikcima.org" />
                <flux:error name="notificationEmail" />
            </flux:field>

            <div class="pt-2">
                <flux:button type="submit" variant="primary" icon="check">Save Settings</flux:button>
            </div>
        </form>
    </div>

    {{-- ===== BRANDING ===== --}}
    <div class="mt-8 max-w-xl">
        <div class="mb-4">
            <flux:heading size="lg">Branding</flux:heading>
            <flux:subheading>Upload a site logo and favicon/icon. PNG or SVG recommended.</flux:subheading>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">

            {{-- ---- LOGO ---- --}}
            <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900"
                 x-data="{
                     uploading: false,
                     progress: 0,
                     error: '',
                     handleFile(file) {
                         if (!file) return;
                         this.uploading = true; this.progress = 0; this.error = '';
                         const fd = new FormData();
                         fd.append('file', file);
                         fd.append('folder', 'system/branding');
                         const tok = document.querySelector('meta[name=csrf-token]');
                         if (!tok) { this.error = 'CSRF token missing'; this.uploading = false; return; }
                         const xhr = new XMLHttpRequest();
                         xhr.open('POST', '{{ route('admin.media.upload') }}');
                         xhr.setRequestHeader('X-CSRF-TOKEN', tok.content);
                         xhr.setRequestHeader('Accept', 'application/json');
                         xhr.upload.onprogress = e => {
                             if (e.lengthComputable) this.progress = Math.round(e.loaded / e.total * 100);
                         };
                         xhr.onload = () => {
                             this.uploading = false;
                             if (xhr.status === 200) {
                                 const d = JSON.parse(xhr.responseText);
                                 $wire.selectLogo(d.path);
                             } else {
                                 this.error = 'Upload failed. Please try again.';
                             }
                         };
                         xhr.onerror = () => { this.uploading = false; this.error = 'Network error.'; };
                         xhr.send(fd);
                     }
                 }">
                <p class="mb-3 text-sm font-medium text-zinc-700 dark:text-zinc-300">Site Logo</p>

                {{-- Current logo preview --}}
                @if($logoPath)
                    <div class="mb-3 flex items-center gap-3">
                        <div class="flex h-12 w-auto max-w-[160px] items-center overflow-hidden rounded-lg border border-zinc-200 bg-zinc-50 px-3 dark:border-zinc-700 dark:bg-zinc-800">
                            <img src="{{ Storage::disk('public')->url($logoPath) }}" alt="Site logo" class="h-8 w-auto object-contain">
                        </div>
                        <button wire:click="clearLogo" class="text-xs text-red-400 hover:text-red-300 transition-colors">Remove</button>
                    </div>
                @endif

                {{-- Upload zone --}}
                <label class="flex cursor-pointer flex-col items-center gap-2 rounded-xl border-2 border-dashed border-zinc-300 bg-zinc-50 px-4 py-5 text-center transition hover:border-green-500 dark:border-zinc-700 dark:bg-zinc-800/50 dark:hover:border-green-500"
                       @dragover.prevent="$el.classList.add('!border-green-500')"
                       @dragleave.prevent="$el.classList.remove('!border-green-500')"
                       @drop.prevent="$el.classList.remove('!border-green-500'); handleFile($event.dataTransfer.files[0])">
                    <input type="file" accept="image/*" class="sr-only" @change="handleFile($event.target.files[0])">
                    <svg class="h-6 w-6 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                    </svg>
                    <span x-show="!uploading" class="text-xs text-zinc-500 dark:text-zinc-400">
                        Drop image or <span class="font-medium text-green-600 dark:text-green-400">click to upload</span>
                    </span>
                    <span x-show="uploading" class="text-xs text-green-600 dark:text-green-400" x-cloak>
                        Uploading… <span x-text="progress + '%'"></span>
                    </span>
                </label>
                <p x-show="error" class="mt-1.5 text-xs text-red-500" x-text="error" x-cloak></p>
                <p class="mt-2 text-[11px] text-zinc-400">Shown in the sidebar, header, and auth pages. PNG or SVG, ideally 200×50px or wider.</p>
            </div>

            {{-- ---- ICON / FAVICON ---- --}}
            <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900"
                 x-data="{
                     uploading: false,
                     progress: 0,
                     error: '',
                     handleFile(file) {
                         if (!file) return;
                         this.uploading = true; this.progress = 0; this.error = '';
                         const fd = new FormData();
                         fd.append('file', file);
                         fd.append('folder', 'system/branding');
                         const tok = document.querySelector('meta[name=csrf-token]');
                         if (!tok) { this.error = 'CSRF token missing'; this.uploading = false; return; }
                         const xhr = new XMLHttpRequest();
                         xhr.open('POST', '{{ route('admin.media.upload') }}');
                         xhr.setRequestHeader('X-CSRF-TOKEN', tok.content);
                         xhr.setRequestHeader('Accept', 'application/json');
                         xhr.upload.onprogress = e => {
                             if (e.lengthComputable) this.progress = Math.round(e.loaded / e.total * 100);
                         };
                         xhr.onload = () => {
                             this.uploading = false;
                             if (xhr.status === 200) {
                                 const d = JSON.parse(xhr.responseText);
                                 $wire.selectIcon(d.path);
                             } else {
                                 this.error = 'Upload failed. Please try again.';
                             }
                         };
                         xhr.onerror = () => { this.uploading = false; this.error = 'Network error.'; };
                         xhr.send(fd);
                     }
                 }">
                <p class="mb-3 text-sm font-medium text-zinc-700 dark:text-zinc-300">Site Icon / Favicon</p>

                {{-- Current icon preview --}}
                @if($iconPath)
                    <div class="mb-3 flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center overflow-hidden rounded-lg border border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-800">
                            <img src="{{ Storage::disk('public')->url($iconPath) }}" alt="Site icon" class="h-8 w-8 object-contain">
                        </div>
                        <button wire:click="clearIcon" class="text-xs text-red-400 hover:text-red-300 transition-colors">Remove</button>
                    </div>
                @endif

                {{-- Upload zone --}}
                <label class="flex cursor-pointer flex-col items-center gap-2 rounded-xl border-2 border-dashed border-zinc-300 bg-zinc-50 px-4 py-5 text-center transition hover:border-green-500 dark:border-zinc-700 dark:bg-zinc-800/50 dark:hover:border-green-500"
                       @dragover.prevent="$el.classList.add('!border-green-500')"
                       @dragleave.prevent="$el.classList.remove('!border-green-500')"
                       @drop.prevent="$el.classList.remove('!border-green-500'); handleFile($event.dataTransfer.files[0])">
                    <input type="file" accept="image/*" class="sr-only" @change="handleFile($event.target.files[0])">
                    <svg class="h-6 w-6 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                    </svg>
                    <span x-show="!uploading" class="text-xs text-zinc-500 dark:text-zinc-400">
                        Drop image or <span class="font-medium text-green-600 dark:text-green-400">click to upload</span>
                    </span>
                    <span x-show="uploading" class="text-xs text-green-600 dark:text-green-400" x-cloak>
                        Uploading… <span x-text="progress + '%'"></span>
                    </span>
                </label>
                <p x-show="error" class="mt-1.5 text-xs text-red-500" x-text="error" x-cloak></p>
                <p class="mt-2 text-[11px] text-zinc-400">Used as the browser tab favicon. Square PNG or ICO, 32×32 or 64×64px recommended.</p>
            </div>

        </div>
    </div>
</div>
