<div>
    <div class="mb-6">
        <flux:heading size="xl">Media Library</flux:heading>
        <flux:subheading>Upload and manage images and downloadable files</flux:subheading>
    </div>

    @if($message)
        <flux:callout variant="success" class="mb-4" x-data x-init="setTimeout(() => $el.remove(), 4000)">
            {{ $message }}
        </flux:callout>
    @endif

    {{-- ── Upload Zone ── --}}
    @can('cms.edit')
        <div
            x-data="{
                isDragging: false,
                uploading: false,
                progress: 0,
                errorMsg: '',
                altText: '',

                onDrop(e) {
                    this.isDragging = false;
                    const file = e.dataTransfer.files[0];
                    if (file) this.doUpload(file);
                },

                async doUpload(file) {
                    this.uploading = true;
                    this.progress = 0;
                    this.errorMsg = '';

                    const tokenEl = document.querySelector('meta[name=csrf-token]');
                    if (!tokenEl) {
                        this.errorMsg = 'CSRF token missing — please refresh the page.';
                        this.uploading = false;
                        return;
                    }

                    const fd = new FormData();
                    fd.append('file', file);
                    fd.append('_token', tokenEl.content);
                    fd.append('disk', 'public');
                    if (this.altText) fd.append('alt_text', this.altText);

                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', '{{ route('admin.media.upload') }}');

                    xhr.upload.addEventListener('progress', (e) => {
                        if (e.lengthComputable) this.progress = Math.round(e.loaded / e.total * 100);
                    });

                    xhr.onload = () => {
                        this.uploading = false;
                        this.progress = 0;
                        if (xhr.status === 201) {
                            const data = JSON.parse(xhr.responseText);
                            this.altText = '';
                            this.$refs.fileInput.value = '';
                            $wire.dispatch('media-uploaded', { filename: data.original_filename });
                        } else {
                            try {
                                const err = JSON.parse(xhr.responseText);
                                this.errorMsg = err.message || err.errors?.file?.[0] || 'Upload failed';
                            } catch {
                                this.errorMsg = 'Upload failed (status ' + xhr.status + ')';
                            }
                        }
                    };

                    xhr.onerror = () => {
                        this.uploading = false;
                        this.errorMsg = 'Network error — please try again';
                    };

                    xhr.send(fd);
                }
            }"
            class="mb-6"
        >
            {{-- Drop zone --}}
            <div
                @dragover.prevent="isDragging = true"
                @dragleave.self="isDragging = false"
                @drop.prevent="onDrop($event)"
                :class="isDragging ? 'border-green-500 bg-green-50 dark:bg-green-900/10' : 'border-zinc-300 dark:border-zinc-600'"
                class="mb-3 rounded-2xl border-2 border-dashed p-10 text-center transition-colors"
            >
                <input
                    type="file"
                    x-ref="fileInput"
                    id="mediaFileInput"
                    class="sr-only"
                    @change="if ($event.target.files[0]) doUpload($event.target.files[0])"
                />

                <template x-if="!uploading">
                    <div>
                        <svg class="mx-auto mb-3 h-10 w-10 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <p class="font-semibold text-zinc-700 dark:text-zinc-300">Drag &amp; drop a file here</p>
                        <p class="mt-1 text-sm text-zinc-500">
                            or <label for="mediaFileInput" class="cursor-pointer text-green-600 hover:underline dark:text-green-400">browse your computer</label>
                        </p>
                        <p class="mt-2 text-xs text-zinc-400">Images (jpg, png, webp, gif) and documents (pdf, docx, xlsx) — max 50 MB</p>
                    </div>
                </template>

                <template x-if="uploading">
                    <div>
                        <p class="mb-3 text-sm font-medium text-zinc-600 dark:text-zinc-300">Uploading...</p>
                        <div class="mx-auto h-2 max-w-sm overflow-hidden rounded-full bg-zinc-200 dark:bg-zinc-700">
                            <div class="h-full rounded-full bg-green-500 transition-all" :style="'width:' + progress + '%'"></div>
                        </div>
                        <p class="mt-2 text-xs text-zinc-400" x-text="progress + '%'"></p>
                    </div>
                </template>

                <p x-show="errorMsg" x-cloak x-text="errorMsg" class="mt-2 text-sm text-red-600 dark:text-red-400"></p>
            </div>

            {{-- Alt-text input (inside same Alpine scope) --}}
            <div class="flex flex-wrap items-center gap-3">
                <input
                    type="text"
                    x-model="altText"
                    placeholder="Label / alt text for the next upload (optional)"
                    class="max-w-xs rounded-lg border border-zinc-300 px-3 py-2 text-sm shadow-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100"
                />
                <p class="text-xs text-zinc-400">Fill in before uploading if you want alt text saved with the file.</p>
            </div>
        </div>
    @endcan

    {{-- ── Filter Bar ── --}}
    <div class="mb-5 flex flex-wrap items-center gap-3">
        <flux:input
            wire:model.live.debounce.300ms="search"
            placeholder="Search files..."
            icon="magnifying-glass"
            class="max-w-xs"
        />
        <div class="flex rounded-lg border border-zinc-200 bg-white p-0.5 dark:border-zinc-700 dark:bg-zinc-900">
            @foreach(['' => 'All', 'image' => 'Images', 'document' => 'Documents', 'other' => 'Other'] as $val => $label)
                <button
                    wire:click="$set('typeFilter', '{{ $val }}')"
                    class="rounded-md px-3 py-1.5 text-xs font-medium transition
                        {{ $typeFilter === $val
                            ? 'bg-green-600 text-white shadow-sm'
                            : 'text-zinc-600 hover:bg-zinc-100 dark:text-zinc-400 dark:hover:bg-zinc-800' }}"
                >
                    {{ $label }}
                </button>
            @endforeach
        </div>
    </div>

    {{-- ── Grid ── --}}
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
        @forelse($items as $item)
            <div class="group relative overflow-hidden rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
                {{-- Preview --}}
                <div class="relative aspect-video">
                    @if($item->type === 'image')
                        <img
                            src="{{ $item->disk === 'public' ? $item->url() : route('admin.media.download', $item->id) }}"
                            alt="{{ $item->alt_text ?? $item->original_filename }}"
                            class="h-full w-full object-cover"
                        />
                    @elseif($item->type === 'document')
                        <div class="flex h-full items-center justify-center bg-zinc-50 dark:bg-zinc-800">
                            <svg class="h-12 w-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @else
                        <div class="flex h-full items-center justify-center bg-zinc-50 dark:bg-zinc-800">
                            <svg class="h-12 w-12 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                            </svg>
                        </div>
                    @endif

                    {{-- Hover Overlay --}}
                    <div class="absolute inset-0 flex items-center justify-center gap-2 bg-zinc-900/70 opacity-0 transition group-hover:opacity-100">
                        @if($item->disk === 'public')
                            <button
                                onclick="navigator.clipboard.writeText('{{ $item->url() }}').then(() => { this.textContent = 'Copied!'; setTimeout(() => this.textContent = 'Copy URL', 1500) })"
                                class="rounded-lg bg-white px-3 py-1.5 text-xs font-medium text-zinc-900 transition hover:bg-zinc-100"
                            >
                                Copy URL
                            </button>
                        @else
                            <a
                                href="{{ route('admin.media.download', $item->id) }}"
                                class="rounded-lg bg-white px-3 py-1.5 text-xs font-medium text-zinc-900 transition hover:bg-zinc-100"
                            >
                                Download
                            </a>
                        @endif
                        @can('cms.edit')
                            <button
                                wire:click="delete({{ $item->id }})"
                                wire:confirm="Delete this file permanently?"
                                class="rounded-lg bg-red-600 p-1.5 text-white transition hover:bg-red-700"
                                title="Delete"
                            >
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        @endcan
                    </div>
                </div>

                {{-- Info --}}
                <div class="p-3">
                    <p class="truncate text-xs font-medium text-zinc-700 dark:text-zinc-300" title="{{ $item->original_filename }}">
                        {{ $item->original_filename }}
                    </p>
                    <div class="mt-1 flex items-center justify-between">
                        <span class="text-xs text-zinc-400">{{ $item->humanSize() }}</span>
                        <span class="rounded-full bg-zinc-100 px-1.5 py-0.5 text-[10px] font-medium uppercase text-zinc-500 dark:bg-zinc-800 dark:text-zinc-400">
                            {{ strtoupper(pathinfo($item->original_filename, PATHINFO_EXTENSION)) }}
                        </span>
                    </div>
                    @if($item->disk === 'local')
                        <span class="mt-1 inline-block rounded-full bg-amber-100 px-1.5 py-0.5 text-[10px] font-medium text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                            Private
                        </span>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full flex h-40 items-center justify-center rounded-xl border border-dashed border-zinc-300 dark:border-zinc-700">
                <div class="text-center">
                    <svg class="mx-auto mb-2 h-10 w-10 text-zinc-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-sm text-zinc-500">No files found.</p>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-5">{{ $items->links() }}</div>
</div>
