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
                             if (xhr.status >= 200 && xhr.status < 300) {
                                 const d = JSON.parse(xhr.responseText);
                                 $wire.selectLogo(d.path);
                             } else {
                                 try {
                                     const err = JSON.parse(xhr.responseText);
                                     this.error = err.message || err.errors?.file?.[0] || 'Upload failed. Please try again.';
                                 } catch {
                                     this.error = 'Upload failed. Please try again.';
                                 }
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
                             if (xhr.status >= 200 && xhr.status < 300) {
                                 const d = JSON.parse(xhr.responseText);
                                 $wire.selectIcon(d.path);
                             } else {
                                 try {
                                     const err = JSON.parse(xhr.responseText);
                                     this.error = err.message || err.errors?.file?.[0] || 'Upload failed. Please try again.';
                                 } catch {
                                     this.error = 'Upload failed. Please try again.';
                                 }
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

    {{-- ===== CONTACT DETAILS ===== --}}
    <div class="mt-8 max-w-xl">
        <div class="mb-4">
            <flux:heading size="lg">Contact Details</flux:heading>
            <flux:subheading>Chapter addresses, phones and emails — shown on the public contact page and site footer.</flux:subheading>
        </div>

        <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <form wire:submit="saveContact" class="space-y-6">

                {{-- Nigeria --}}
                <div class="space-y-4">
                    <p class="flex items-center gap-2 text-sm font-semibold text-zinc-700 dark:text-zinc-300">
                        <span class="text-base leading-none">&#127475;&#127468;</span> Nigeria Chapter
                    </p>
                    <flux:field>
                        <flux:label>Address</flux:label>
                        <flux:textarea wire:model="nigeriaAddress" rows="2" />
                        <flux:error name="nigeriaAddress" />
                    </flux:field>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <flux:field>
                            <flux:label>Phone</flux:label>
                            <flux:input wire:model="nigeriaPhone" type="tel" placeholder="+234 ..." />
                            <flux:error name="nigeriaPhone" />
                        </flux:field>
                        <flux:field>
                            <flux:label>Email</flux:label>
                            <flux:input wire:model="nigeriaEmail" type="email" placeholder="nigeria@nikccima.org" />
                            <flux:error name="nigeriaEmail" />
                        </flux:field>
                    </div>
                </div>

                {{-- Kenya --}}
                <div class="space-y-4 border-t border-zinc-100 pt-6 dark:border-zinc-800">
                    <p class="flex items-center gap-2 text-sm font-semibold text-zinc-700 dark:text-zinc-300">
                        <span class="text-base leading-none">&#127472;&#127466;</span> Kenya Chapter
                    </p>
                    <flux:field>
                        <flux:label>Address</flux:label>
                        <flux:textarea wire:model="kenyaAddress" rows="2" />
                        <flux:error name="kenyaAddress" />
                    </flux:field>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <flux:field>
                            <flux:label>Phone</flux:label>
                            <flux:input wire:model="kenyaPhone" type="tel" placeholder="+254 ..." />
                            <flux:error name="kenyaPhone" />
                        </flux:field>
                        <flux:field>
                            <flux:label>Email</flux:label>
                            <flux:input wire:model="kenyaEmail" type="email" placeholder="kenya@nikccima.org" />
                            <flux:error name="kenyaEmail" />
                        </flux:field>
                    </div>
                </div>

                {{-- Map --}}
                <div class="border-t border-zinc-100 pt-6 dark:border-zinc-800">
                    <flux:field>
                        <flux:label>Map Embed URL</flux:label>
                        <flux:description>Google Maps embed URL (Share → Embed a map → copy the <code>src</code>). Shown on the contact page when set.</flux:description>
                        <flux:input wire:model="mapEmbedUrl" type="url" placeholder="https://www.google.com/maps/embed?..." />
                        <flux:error name="mapEmbedUrl" />
                    </flux:field>
                    @if($mapEmbedUrl)
                        <div class="mt-3 overflow-hidden rounded-lg border border-zinc-200 dark:border-zinc-700">
                            <iframe src="{{ $mapEmbedUrl }}" width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    @endif
                </div>

                <div class="pt-2">
                    <flux:button type="submit" variant="primary" icon="check">Save Contact Details</flux:button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===== SEO & ANALYTICS ===== --}}
    <div class="mt-8 max-w-xl">
        <div class="mb-4">
            <flux:heading size="lg">SEO &amp; Analytics</flux:heading>
            <flux:subheading>Search-engine metadata, social share image, and analytics tags for the public website.</flux:subheading>
        </div>

        <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <form wire:submit="saveSeo" class="space-y-4">
                <flux:field>
                    <flux:label>Default Meta Description</flux:label>
                    <flux:description>Used on pages without their own description. ~150–160 characters is ideal.</flux:description>
                    <flux:textarea wire:model="seoDefaultDescription" rows="3" placeholder="The Nigeria-Kenya Chamber of Commerce, Industry, Mines & Agriculture — driving AfCFTA corridor trade between Nigeria and Kenya." />
                    <flux:error name="seoDefaultDescription" />
                </flux:field>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <flux:field>
                        <flux:label>Google Analytics ID (GA4)</flux:label>
                        <flux:input wire:model="gaMeasurementId" placeholder="G-XXXXXXXXXX" />
                        <flux:error name="gaMeasurementId" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Search Console Verification</flux:label>
                        <flux:input wire:model="searchConsoleVerification" placeholder="google-site-verification token" />
                        <flux:error name="searchConsoleVerification" />
                    </flux:field>
                </div>

                <div class="pt-2">
                    <flux:button type="submit" variant="primary" icon="check">Save SEO Settings</flux:button>
                </div>
            </form>

            {{-- Social share image (XHR upload, mirrors logo/icon) --}}
            <div class="mt-6 border-t border-zinc-100 pt-6 dark:border-zinc-800"
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
                         xhr.upload.onprogress = e => { if (e.lengthComputable) this.progress = Math.round(e.loaded / e.total * 100); };
                         xhr.onload = () => {
                             this.uploading = false;
                             if (xhr.status >= 200 && xhr.status < 300) {
                                 const d = JSON.parse(xhr.responseText);
                                 $wire.selectShareImage(d.path);
                             } else {
                                 try { const err = JSON.parse(xhr.responseText); this.error = err.message || err.errors?.file?.[0] || 'Upload failed.'; }
                                 catch { this.error = 'Upload failed.'; }
                             }
                         };
                         xhr.onerror = () => { this.uploading = false; this.error = 'Network error.'; };
                         xhr.send(fd);
                     }
                 }">
                <p class="mb-3 text-sm font-medium text-zinc-700 dark:text-zinc-300">Social Share Image</p>

                @if($shareImagePath)
                    <div class="mb-3 flex items-center gap-3">
                        <div class="h-16 w-28 overflow-hidden rounded-lg border border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-800">
                            <img src="{{ Storage::disk('public')->url($shareImagePath) }}" alt="Share image" class="h-full w-full object-cover">
                        </div>
                        <button wire:click="clearShareImage" class="text-xs text-red-400 transition-colors hover:text-red-300">Remove</button>
                    </div>
                @endif

                <label class="flex cursor-pointer flex-col items-center gap-2 rounded-xl border-2 border-dashed border-zinc-300 bg-zinc-50 px-4 py-5 text-center transition hover:border-green-500 dark:border-zinc-700 dark:bg-zinc-800/50 dark:hover:border-green-500"
                       @dragover.prevent="$el.classList.add('!border-green-500')"
                       @dragleave.prevent="$el.classList.remove('!border-green-500')"
                       @drop.prevent="$el.classList.remove('!border-green-500'); handleFile($event.dataTransfer.files[0])">
                    <input type="file" accept="image/*" class="sr-only" @change="handleFile($event.target.files[0])">
                    <svg class="h-6 w-6 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                    </svg>
                    <span x-show="!uploading" class="text-xs text-zinc-500 dark:text-zinc-400">Drop image or <span class="font-medium text-green-600 dark:text-green-400">click to upload</span></span>
                    <span x-show="uploading" class="text-xs text-green-600 dark:text-green-400" x-cloak>Uploading… <span x-text="progress + '%'"></span></span>
                </label>
                <p x-show="error" class="mt-1.5 text-xs text-red-500" x-text="error" x-cloak></p>
                <p class="mt-2 text-[11px] text-zinc-400">Shown when a page is shared on social media. Recommended 1200×630px. Falls back to the site logo if unset.</p>
            </div>
        </div>
    </div>
</div>
