<div
    x-data="{
        uploading: false,
        progress: 0,
        errorMsg: '',

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
            @if($folder)
            formData.append('folder', @js($folder));
            @endif
            @if($disk)
            formData.append('disk', @js($disk));
            @endif

            try {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', '{{ route('admin.media.upload') }}');

                xhr.upload.addEventListener('progress', (e) => {
                    if (e.lengthComputable) {
                        this.progress = Math.round((e.loaded / e.total) * 100);
                    }
                });

                await new Promise((resolve, reject) => {
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
                }).then((data) => {
                    $wire.setFromUpload(data.id);
                });
            } catch (err) {
                this.errorMsg = typeof err === 'string' ? err : 'Upload failed';
            } finally {
                this.uploading = false;
                this.progress = 0;
            }
        }
    }"
    class="w-full"
>
    {{-- ── Selected Preview ── --}}
    <div class="flex items-center gap-3 rounded-xl border border-zinc-200 bg-zinc-50 p-3 dark:border-zinc-700 dark:bg-zinc-800/50">
        @if($selected)
            @if($selected->type === 'image')
                <img
                    src="{{ $selected->disk === 'public' ? $selected->url() : route('admin.media.download', $selected->id) }}"
                    alt="{{ $selected->alt_text ?? $selected->original_filename }}"
                    class="h-14 w-14 shrink-0 rounded-lg object-cover"
                />
            @else
                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-lg bg-zinc-200 dark:bg-zinc-700">
                    <svg class="h-7 w-7 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
            @endif
            <div class="min-w-0 flex-1">
                <p class="truncate text-sm font-medium text-zinc-700 dark:text-zinc-200">{{ $selected->original_filename }}</p>
                <p class="text-xs text-zinc-400">{{ $selected->humanSize() }}</p>
            </div>
            <div class="flex shrink-0 gap-2">
                <flux:button wire:click="$set('open', true)" size="sm" variant="ghost">Change</flux:button>
                <flux:button wire:click="clearSelection" size="sm" variant="ghost" class="text-red-500 hover:text-red-600">Remove</flux:button>
            </div>
        @else
            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-lg border-2 border-dashed border-zinc-300 dark:border-zinc-600">
                <svg class="h-6 w-6 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">No file selected</p>
            </div>
            <flux:button wire:click="$set('open', true)" size="sm">Choose file</flux:button>
        @endif
    </div>

    {{-- ── Picker Panel ── --}}
    @if($open)
        <div class="mt-3 rounded-2xl border border-zinc-200 bg-white shadow-lg dark:border-zinc-700 dark:bg-zinc-900">
            {{-- Tab Bar --}}
            <div class="flex items-center justify-between border-b border-zinc-200 px-4 dark:border-zinc-700">
                <div class="flex gap-1">
                    <button
                        wire:click="$set('tab', 'upload')"
                        class="border-b-2 px-4 py-3 text-sm font-medium transition
                            {{ $tab === 'upload'
                                ? 'border-green-600 text-green-600'
                                : 'border-transparent text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300' }}"
                    >
                        Upload New
                    </button>
                    <button
                        wire:click="$set('tab', 'library')"
                        class="border-b-2 px-4 py-3 text-sm font-medium transition
                            {{ $tab === 'library'
                                ? 'border-green-600 text-green-600'
                                : 'border-transparent text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300' }}"
                    >
                        Choose from Library
                    </button>
                </div>
                <button
                    wire:click="$set('open', false)"
                    class="p-2 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200"
                    title="Close"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- ── Upload Tab ── --}}
            @if($tab === 'upload')
                <div class="p-6">
                    <div
                        x-data="{
                            isDragging: false,
                            onDrop(e) {
                                this.isDragging = false;
                                const file = e.dataTransfer.files[0];
                                if (file) this.uploadFile(file);
                            }
                        }"
                        @dragover.prevent="isDragging = true"
                        @dragleave.self="isDragging = false"
                        @drop.prevent="onDrop($event)"
                        :class="isDragging ? 'border-green-500 bg-green-50 dark:bg-green-900/10' : 'border-zinc-300 dark:border-zinc-600'"
                        class="cursor-pointer rounded-2xl border-2 border-dashed p-8 text-center transition-colors"
                        @click="$refs.fileInput.click()"
                    >
                        <input
                            type="file"
                            x-ref="fileInput"
                            accept="{{ $accept }}"
                            class="sr-only"
                            @change="if ($event.target.files[0]) uploadFile($event.target.files[0])"
                        />

                        <template x-if="!uploading">
                            <div>
                                <svg class="mx-auto mb-3 h-10 w-10 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                <p class="font-medium text-zinc-700 dark:text-zinc-300">Click to browse or drag &amp; drop</p>
                                @if($accept)
                                    <p class="mt-1 text-xs text-zinc-400">Accepted: {{ $accept }}</p>
                                @endif
                            </div>
                        </template>

                        <template x-if="uploading">
                            <div @click.stop>
                                <p class="mb-3 text-sm font-medium text-zinc-600 dark:text-zinc-300">Uploading...</p>
                                <div class="mx-auto h-2 max-w-xs overflow-hidden rounded-full bg-zinc-200 dark:bg-zinc-700">
                                    <div
                                        class="h-full rounded-full bg-green-500 transition-all"
                                        :style="'width:' + progress + '%'"
                                    ></div>
                                </div>
                                <p class="mt-2 text-xs text-zinc-400" x-text="progress + '%'"></p>
                            </div>
                        </template>
                    </div>

                    <p x-show="errorMsg" x-text="errorMsg" class="mt-2 text-sm text-red-600 dark:text-red-400"></p>
                </div>
            @endif

            {{-- ── Library Tab ── --}}
            @if($tab === 'library')
                <div class="p-4">
                    <div class="mb-3">
                        <flux:input
                            wire:model.live.debounce.300ms="search"
                            placeholder="Search files..."
                            icon="magnifying-glass"
                            class="w-full"
                        />
                    </div>

                    @if($items->isEmpty())
                        <div class="flex h-40 items-center justify-center rounded-xl border border-dashed border-zinc-300 dark:border-zinc-700">
                            <p class="text-sm text-zinc-500">No files found.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-3 gap-2 sm:grid-cols-4">
                            @foreach($items as $item)
                                <button
                                    type="button"
                                    wire:click="selectItem({{ $item->id }})"
                                    class="group relative overflow-hidden rounded-xl border-2 transition
                                        {{ $value === $item->id
                                            ? 'border-green-500 ring-2 ring-green-400'
                                            : 'border-zinc-200 hover:border-zinc-400 dark:border-zinc-700 dark:hover:border-zinc-500' }}"
                                >
                                    <div class="aspect-video">
                                        @if($item->type === 'image')
                                            <img
                                                src="{{ $item->disk === 'public' ? $item->url() : route('admin.media.download', $item->id) }}"
                                                alt="{{ $item->alt_text ?? $item->original_filename }}"
                                                class="h-full w-full object-cover"
                                            />
                                        @else
                                            <div class="flex h-full items-center justify-center bg-zinc-50 dark:bg-zinc-800">
                                                <svg class="h-8 w-8 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="p-1.5">
                                        <p class="truncate text-[10px] text-zinc-600 dark:text-zinc-300">{{ $item->original_filename }}</p>
                                    </div>
                                    @if($value === $item->id)
                                        <div class="absolute right-1 top-1 flex h-5 w-5 items-center justify-center rounded-full bg-green-500">
                                            <svg class="h-3 w-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                    @endif
                                </button>
                            @endforeach
                        </div>

                        <div class="mt-3 text-xs text-zinc-500">
                            {{ $items->links(data: ['scrollTo' => false]) }}
                        </div>
                    @endif
                </div>
            @endif
        </div>
    @endif
</div>
