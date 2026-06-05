<div>
    <div class="mb-6">
        <flux:button icon="arrow-left" href="{{ route('admin.documents.index') }}" wire:navigate variant="ghost" size="sm">Document Library</flux:button>
        <flux:heading size="xl" class="mt-2">{{ $document->title }}</flux:heading>
        <flux:subheading>{{ ucwords(str_replace('-', ' ', $document->category)) }} &bull; v{{ $document->version }}</flux:subheading>
    </div>

    @if(session('success'))
        <flux:callout variant="success" class="mb-4" dismissible>{{ session('success') }}</flux:callout>
    @endif

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Main --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <flux:heading size="lg" class="mb-4">Details</flux:heading>
                <dl class="grid grid-cols-2 gap-4">
                    <div><dt class="text-xs text-zinc-500">Category</dt><dd class="font-medium">{{ ucwords(str_replace('-', ' ', $document->category)) }}</dd></div>
                    <div><dt class="text-xs text-zinc-500">Version</dt><dd>v{{ $document->version }}</dd></div>
                    <div><dt class="text-xs text-zinc-500">File type</dt><dd class="uppercase">{{ $document->extension() ?: '—' }}</dd></div>
                    <div><dt class="text-xs text-zinc-500">Size</dt><dd>{{ $document->humanSize() }}</dd></div>
                    <div><dt class="text-xs text-zinc-500">Chapter</dt><dd>{{ $document->chapter?->name ?? '—' }}</dd></div>
                    <div><dt class="text-xs text-zinc-500">Uploaded by</dt><dd>{{ $document->uploader?->name ?? '—' }}</dd></div>
                    <div><dt class="text-xs text-zinc-500">Uploaded</dt><dd>{{ $document->created_at->format('d M Y H:i') }}</dd></div>
                    <div><dt class="text-xs text-zinc-500">Approved by</dt><dd>{{ $document->approver?->name ?? '—' }}</dd></div>
                </dl>
            </div>

            @if($document->description)
                <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                    <flux:heading size="lg" class="mb-3">Description</flux:heading>
                    <p class="text-sm leading-relaxed text-zinc-700 dark:text-zinc-300">{!! nl2br(e($document->description)) !!}</p>
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-4">
            <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
                <flux:heading size="sm" class="mb-3">Status</flux:heading>
                <div class="flex flex-wrap gap-2">
                    <span class="inline-flex rounded-full px-3 py-1 text-sm font-medium
                        {{ $document->status === 'approved' ? 'bg-green-100 text-green-800' :
                           ($document->status === 'pending-approval' ? 'bg-yellow-100 text-yellow-800' :
                           ($document->status === 'archived' ? 'bg-red-100 text-red-800' : 'bg-zinc-100 text-zinc-600')) }}">
                        {{ ucwords(str_replace('-', ' ', $document->status)) }}
                    </span>
                    <span class="inline-flex rounded-full px-3 py-1 text-sm font-medium {{ $document->is_public ? 'bg-brand-100 text-brand-700' : 'bg-zinc-100 text-zinc-600' }}">
                        {{ $document->is_public ? 'Public' : 'Internal' }}
                    </span>
                </div>
            </div>

            <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
                <flux:heading size="sm" class="mb-3">Actions</flux:heading>
                <div class="space-y-2">
                    @if($document->status === 'approved')
                        <flux:button class="w-full" icon="arrow-down-tray" href="{{ route('admin.documents.download', $document) }}">Download</flux:button>
                    @endif
                    @can('documents.approve')
                        @if($document->status !== 'approved')
                            <flux:button class="w-full" variant="primary" icon="check" wire:click="approve">Approve</flux:button>
                        @endif
                        <flux:button class="w-full" variant="ghost" icon="globe-alt" wire:click="togglePublic">
                            {{ $document->is_public ? 'Make Internal' : 'Make Public' }}
                        </flux:button>
                        @if($document->status !== 'archived')
                            <flux:button class="w-full" variant="ghost" icon="archive-box" wire:click="archive" wire:confirm="Archive this document?">Archive</flux:button>
                        @endif
                    @endcan
                    @can('documents.delete')
                        <flux:button class="w-full" variant="danger" icon="trash" wire:click="destroy" wire:confirm="Delete this document permanently?">Delete</flux:button>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
