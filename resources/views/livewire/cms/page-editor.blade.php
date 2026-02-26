<div>
    <div class="mb-6">
        <flux:heading size="xl">Page Editor</flux:heading>
        <flux:subheading>Edit public website page content</flux:subheading>
    </div>

    @if(session('success'))
        <flux:callout variant="success" class="mb-4">{{ session('success') }}</flux:callout>
    @endif

    <div class="grid gap-6 lg:grid-cols-4">
        {{-- Page List Sidebar --}}
        <div class="rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:heading size="sm" class="mb-3">Pages</flux:heading>
            <nav class="space-y-1">
                @foreach($pages as $p)
                    <button
                        wire:click="selectPage('{{ $p->slug }}')"
                        class="flex w-full items-start justify-between gap-2 rounded-lg px-3 py-2.5 text-left text-sm transition
                            {{ $selectedSlug === $p->slug
                                ? 'bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-300'
                                : 'text-zinc-700 hover:bg-zinc-50 dark:text-zinc-300 dark:hover:bg-zinc-800' }}"
                    >
                        <span class="flex items-center gap-2">
                            <span class="mt-0.5 h-2 w-2 flex-shrink-0 rounded-full {{ $p->is_published ? 'bg-green-500' : 'bg-zinc-300 dark:bg-zinc-600' }}"></span>
                            <span>{{ $p->title }}</span>
                        </span>
                        @if($p->is_published)
                            <span class="flex-shrink-0 rounded-full bg-green-100 px-1.5 py-0.5 text-[10px] font-medium text-green-700 dark:bg-green-900 dark:text-green-300">Live</span>
                        @else
                            <span class="flex-shrink-0 rounded-full bg-zinc-100 px-1.5 py-0.5 text-[10px] font-medium text-zinc-500 dark:bg-zinc-700 dark:text-zinc-400">Draft</span>
                        @endif
                    </button>
                @endforeach
            </nav>
        </div>

        {{-- Editor Panel --}}
        <div class="lg:col-span-3">
            @if($page)
                <div class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
                    {{-- Sticky Top Bar --}}
                    <div class="sticky top-0 z-10 flex items-center justify-between gap-4 rounded-t-xl border-b border-zinc-100 bg-white/95 px-6 py-4 backdrop-blur dark:border-zinc-800 dark:bg-zinc-900/95">
                        <div>
                            <flux:heading size="lg">{{ $pageTitle }}</flux:heading>
                            @if($page->updated_at)
                                <p class="mt-0.5 text-xs text-zinc-400">Last saved {{ $page->updated_at->diffForHumans() }}</p>
                            @endif
                        </div>
                        <div class="flex items-center gap-2">
                            <button
                                wire:click="togglePublish"
                                class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-sm font-medium transition
                                    {{ $isPublished
                                        ? 'bg-green-100 text-green-700 hover:bg-green-200 dark:bg-green-900/40 dark:text-green-300'
                                        : 'bg-zinc-100 text-zinc-600 hover:bg-zinc-200 dark:bg-zinc-800 dark:text-zinc-400' }}"
                            >
                                <span class="h-1.5 w-1.5 rounded-full {{ $isPublished ? 'bg-green-500' : 'bg-zinc-400' }}"></span>
                                {{ $isPublished ? 'Published' : 'Draft' }}
                            </button>
                            <flux:button wire:click="save" variant="primary" size="sm" icon="check">Save</flux:button>
                        </div>
                    </div>

                    <div class="p-6">
                        {{-- Section Fields — groups pre-computed in render() to avoid @while + Livewire's $loop->index bug --}}
                        <div class="space-y-5">
                            @foreach($fieldGroups as $group)
                                @if($group['layout'] === 'pair')
                                    {{-- Two short fields side by side --}}
                                    <div class="grid grid-cols-2 gap-4">
                                        <x-cms-field :field-key="$group['key1']" :type="$group['type1']" :value="$sections[$group['key1']]" />
                                        <x-cms-field :field-key="$group['key2']" :type="$group['type2']" :value="$sections[$group['key2']]" />
                                    </div>
                                @else
                                    @php $key = $group['key']; $type = $group['type']; @endphp
                                    {{-- Full-width field --}}
                                    @if($type === 'image')
                                        <div>
                                            <div class="mb-1.5 flex items-center gap-2">
                                                <label class="text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ ucwords(str_replace('_', ' ', $key)) }}</label>
                                                <code class="rounded bg-zinc-100 px-1.5 py-0.5 text-[10px] text-zinc-400 dark:bg-zinc-800">{{ $key }}</code>
                                            </div>
                                            <div class="rounded-xl border border-dashed border-zinc-300 bg-zinc-50 p-4 dark:border-zinc-700 dark:bg-zinc-800/50">
                                                @if(!empty($sections[$key]))
                                                    <img src="{{ Storage::disk('public')->url($sections[$key]) }}"
                                                         class="mb-3 h-40 w-full rounded-lg object-cover shadow"
                                                         alt="Preview" />
                                                    <p class="mb-2 truncate font-mono text-xs text-zinc-400">{{ $sections[$key] }}</p>
                                                @else
                                                    <div class="mb-3 flex h-28 items-center justify-center rounded-lg bg-zinc-100 dark:bg-zinc-800">
                                                        <flux:icon name="photo" class="h-10 w-10 text-zinc-300" />
                                                    </div>
                                                    <p class="mb-2 text-xs text-zinc-400">No image selected</p>
                                                @endif
                                                <div class="flex items-center gap-2">
                                                    <flux:button wire:click="openImagePicker('{{ $key }}')" size="sm" variant="ghost" icon="folder-open">Choose from Library</flux:button>
                                                    @if(!empty($sections[$key]))
                                                        <flux:button wire:click="clearImage('{{ $key }}')" size="sm" variant="ghost" class="text-red-500">Clear</flux:button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($type === 'richtext')
                                        <flux:field>
                                            <div class="mb-1.5 flex items-center gap-2">
                                                <flux:label>{{ ucwords(str_replace('_', ' ', $key)) }}</flux:label>
                                                <code class="rounded bg-zinc-100 px-1.5 py-0.5 text-[10px] text-zinc-400 dark:bg-zinc-800">{{ $key }}</code>
                                                <span class="rounded bg-blue-50 px-1.5 py-0.5 text-[10px] text-blue-500 dark:bg-blue-900/30 dark:text-blue-400">HTML supported</span>
                                            </div>
                                            <flux:textarea wire:model="sections.{{ $key }}" rows="7" />
                                        </flux:field>
                                    @elseif($type === 'list')
                                        <flux:field>
                                            <div class="mb-1.5 flex items-center gap-2">
                                                <flux:label>{{ ucwords(str_replace('_', ' ', $key)) }}</flux:label>
                                                <code class="rounded bg-zinc-100 px-1.5 py-0.5 text-[10px] text-zinc-400 dark:bg-zinc-800">{{ $key }}</code>
                                                <span class="rounded bg-amber-50 px-1.5 py-0.5 text-[10px] text-amber-600 dark:bg-amber-900/30 dark:text-amber-400">One item per line</span>
                                            </div>
                                            <flux:textarea wire:model="sections.{{ $key }}" rows="4" />
                                        </flux:field>
                                    @elseif($type === 'address')
                                        <flux:field>
                                            <div class="mb-1.5 flex items-center gap-2">
                                                <flux:label>{{ ucwords(str_replace('_', ' ', $key)) }}</flux:label>
                                                <code class="rounded bg-zinc-100 px-1.5 py-0.5 text-[10px] text-zinc-400 dark:bg-zinc-800">{{ $key }}</code>
                                            </div>
                                            <flux:textarea wire:model="sections.{{ $key }}" rows="3" />
                                        </flux:field>
                                    @elseif($type === 'url')
                                        <flux:field>
                                            <div class="mb-1.5 flex items-center gap-2">
                                                <flux:label>{{ ucwords(str_replace('_', ' ', $key)) }}</flux:label>
                                                <code class="rounded bg-zinc-100 px-1.5 py-0.5 text-[10px] text-zinc-400 dark:bg-zinc-800">{{ $key }}</code>
                                            </div>
                                            <flux:input wire:model="sections.{{ $key }}" type="url" placeholder="https://" />
                                            @if($key === 'map_embed_url' && !empty($sections[$key]))
                                                <div class="mt-2 overflow-hidden rounded-lg">
                                                    <iframe src="{{ $sections[$key] }}" width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                                                </div>
                                            @endif
                                        </flux:field>
                                    @else
                                        {{-- Single short field at full width --}}
                                        <flux:field>
                                            <div class="mb-1.5 flex items-center gap-2">
                                                <flux:label>{{ ucwords(str_replace('_', ' ', $key)) }}</flux:label>
                                                <code class="rounded bg-zinc-100 px-1.5 py-0.5 text-[10px] text-zinc-400 dark:bg-zinc-800">{{ $key }}</code>
                                                @if($type === 'icon')
                                                    <span class="rounded bg-zinc-50 px-1.5 py-0.5 text-[10px] text-zinc-400 dark:bg-zinc-800">Emoji or icon code</span>
                                                @endif
                                            </div>
                                            <flux:input wire:model="sections.{{ $key }}"
                                                type="{{ $type === 'tel' ? 'tel' : ($type === 'email' ? 'email' : 'text') }}" />
                                        </flux:field>
                                    @endif
                                @endif
                            @endforeach
                        </div>

                        {{-- SEO Section — Collapsed Accordion --}}
                        <div class="mt-8 border-t border-zinc-100 pt-6 dark:border-zinc-800" x-data="{ open: false }">
                            <button
                                @click="open = !open"
                                class="flex w-full items-center justify-between text-left"
                            >
                                <div class="flex items-center gap-2">
                                    <flux:icon name="magnifying-glass" class="h-4 w-4 text-zinc-400" />
                                    <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500">SEO</p>
                                </div>
                                <flux:icon name="chevron-down" class="h-4 w-4 text-zinc-400 transition-transform" x-bind:class="open && 'rotate-180'" />
                            </button>
                            <div x-show="open" x-collapse class="mt-4 space-y-3">
                                <flux:field>
                                    <flux:label>Meta Title</flux:label>
                                    <flux:input wire:model="metaTitle" placeholder="Leave blank to use page title" />
                                </flux:field>
                                <flux:field>
                                    <flux:label>Meta Description</flux:label>
                                    <flux:textarea wire:model="metaDescription" rows="2" placeholder="Brief page description for search engines" />
                                </flux:field>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="flex h-48 items-center justify-center rounded-xl border border-dashed border-zinc-300 dark:border-zinc-700">
                    <p class="text-sm text-zinc-500">Select a page from the left to start editing</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Full-Screen Image Picker Modal --}}
    <div
        x-data
        x-show="$wire.pickerTargetField !== ''"
        x-cloak
        class="fixed inset-0 z-[60] flex flex-col bg-zinc-950/90 backdrop-blur-sm"
        x-transition:enter="transition duration-150 ease-out"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition duration-100 ease-in"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        {{-- Header --}}
        <div class="flex flex-shrink-0 items-center gap-4 border-b border-zinc-800 bg-zinc-900 px-6 py-4">
            <h2 class="text-base font-semibold text-white">Choose Image</h2>
            <div class="max-w-xs flex-1">
                <flux:input
                    wire:model.live.debounce.300ms="pickerSearch"
                    placeholder="Search images..."
                    icon="magnifying-glass"
                />
            </div>
            <button
                wire:click="$set('pickerTargetField', '')"
                class="ml-auto rounded-lg p-2 text-zinc-400 hover:bg-zinc-800 hover:text-white"
            >
                <flux:icon name="x-mark" class="h-5 w-5" />
            </button>
        </div>

        {{-- Upload zone (top of picker modal) --}}
        <div class="flex-shrink-0 border-b border-zinc-800 bg-zinc-900/50 px-6 py-4"
             x-data="{
                 uploading: false,
                 progress: 0,
                 error: '',
                 handleFile(file) {
                     if (!file) return;
                     this.uploading = true; this.progress = 0; this.error = '';
                     const fd = new FormData();
                     fd.append('file', file);
                     fd.append('folder', 'cms/images');
                     const tok = document.querySelector('meta[name=csrf-token]');
                     if (!tok) { this.error = 'CSRF token missing'; this.uploading = false; return; }
                     const xhr = new XMLHttpRequest();
                     xhr.open('POST', '{{ route('admin.media.upload') }}');
                     xhr.setRequestHeader('X-CSRF-TOKEN', tok.content);
                     xhr.setRequestHeader('Accept', 'application/json');
                     xhr.upload.onprogress = e => { if (e.lengthComputable) this.progress = Math.round(e.loaded/e.total*100); };
                     xhr.onload = () => {
                         this.uploading = false;
                         if (xhr.status === 200) {
                             const d = JSON.parse(xhr.responseText);
                             $wire.selectImage(d.path);
                         } else {
                             this.error = 'Upload failed. Try again.';
                         }
                     };
                     xhr.onerror = () => { this.uploading = false; this.error = 'Network error.'; };
                     xhr.send(fd);
                 }
             }"
             @dragover.prevent="$el.classList.add('border-green-500')"
             @dragleave.prevent="$el.classList.remove('border-green-500')"
             @drop.prevent="$el.classList.remove('border-green-500'); handleFile($event.dataTransfer.files[0])">
            <label class="flex cursor-pointer items-center gap-3 rounded-xl border-2 border-dashed border-zinc-700 bg-zinc-800 px-4 py-3 transition hover:border-green-500">
                <input type="file" accept="image/*" class="sr-only" @change="handleFile($event.target.files[0])">
                <flux:icon name="arrow-up-tray" class="h-5 w-5 text-zinc-400" />
                <span x-show="!uploading" class="text-sm text-zinc-400">Drop image here or <span class="text-green-400">click to upload</span></span>
                <span x-show="uploading" class="text-sm text-green-400" x-cloak>Uploading… <span x-text="progress + '%'"></span></span>
            </label>
            <p x-show="error" class="mt-1.5 text-xs text-red-400" x-text="error" x-cloak></p>
        </div>

        {{-- Image Grid --}}
        <div class="grid grid-cols-4 gap-3 overflow-y-auto p-6 md:grid-cols-6 lg:grid-cols-8">
            @foreach($pickerImages as $img)
                <button
                    wire:click="selectImage('{{ $img->path }}')"
                    class="group relative aspect-square overflow-hidden rounded-xl border-2 border-transparent transition hover:border-green-500 focus:border-green-500 focus:outline-none"
                >
                    <img
                        src="{{ $img->url() }}"
                        class="h-full w-full object-cover"
                        alt="{{ $img->alt_text }}"
                    />
                    <div class="absolute inset-0 flex items-end bg-gradient-to-t from-black/70 opacity-0 transition group-hover:opacity-100">
                        <p class="truncate px-2 pb-1.5 text-xs text-white">{{ $img->original_filename }}</p>
                    </div>
                </button>
            @endforeach

            @if($pickerImages->isEmpty())
                <div class="col-span-full py-20 text-center">
                    <flux:icon name="photo" class="mx-auto mb-3 h-12 w-12 text-zinc-600" />
                    <p class="text-zinc-400">No images in the library yet.</p>
                    <a href="{{ route('admin.cms.media') }}" class="mt-2 inline-block text-sm text-green-400 hover:underline">
                        Upload via Media Library →
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
