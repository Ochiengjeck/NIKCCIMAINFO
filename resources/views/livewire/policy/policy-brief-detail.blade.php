<div>
    <div class="mb-6">
        <flux:button icon="arrow-left" href="{{ route('admin.policy.briefs') }}" wire:navigate variant="ghost" size="sm">Policy Briefs</flux:button>
        <flux:heading size="xl" class="mt-2">{{ $brief->title }}</flux:heading>
        <flux:subheading>By {{ $brief->author?->name ?? '—' }}</flux:subheading>
    </div>

    @if(session('success'))
        <flux:callout variant="success" class="mb-4" dismissible>{{ session('success') }}</flux:callout>
    @endif

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Main --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <flux:heading size="lg" class="mb-3">Brief</flux:heading>
                <div class="prose prose-sm max-w-none text-zinc-700 dark:text-zinc-300">{!! nl2br(e($brief->body)) !!}</div>
            </div>

            @if($brief->file)
                <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                    <flux:heading size="lg" class="mb-3">Attachment</flux:heading>
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-zinc-100 text-zinc-500 dark:bg-zinc-700">
                                <flux:icon name="document" class="h-5 w-5" />
                            </div>
                            <div class="min-w-0">
                                <p class="truncate text-sm font-medium text-zinc-900 dark:text-white">{{ $brief->file->original_filename }}</p>
                                <p class="text-xs text-zinc-400">{{ $brief->file->humanSize() }}</p>
                            </div>
                        </div>
                        <flux:button size="sm" icon="arrow-down-tray" href="{{ route('admin.media.download', $brief->file->id) }}">Download</flux:button>
                    </div>
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-4">
            <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
                <flux:heading size="sm" class="mb-3">Status</flux:heading>
                <span class="inline-flex rounded-full px-3 py-1 text-sm font-medium
                    @switch($brief->status)
                        @case('draft') bg-zinc-100 text-zinc-700 @break
                        @case('in-review') bg-yellow-100 text-yellow-800 @break
                        @case('approved') bg-blue-100 text-blue-800 @break
                        @case('published') bg-green-100 text-green-800 @break
                    @endswitch
                ">{{ ucfirst(str_replace('-', ' ', $brief->status)) }}</span>
                <dl class="mt-4 space-y-2 text-sm">
                    <div class="flex justify-between"><dt class="text-zinc-500">Reviewer</dt><dd>{{ $brief->reviewer?->name ?? '—' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-zinc-500">Published</dt><dd>{{ $brief->published_at?->format('d M Y') ?? '—' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-zinc-500">Created</dt><dd>{{ $brief->created_at->format('d M Y') }}</dd></div>
                </dl>
            </div>

            <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
                <flux:heading size="sm" class="mb-3">Actions</flux:heading>
                <div class="space-y-2">
                    @if($brief->status === 'published')
                        <flux:button class="w-full" variant="ghost" icon="eye" href="{{ route('policy.show', $brief) }}" target="_blank">View on website</flux:button>
                    @endif
                    @can('policy.edit')
                        <flux:button class="w-full" variant="ghost" icon="pencil-square" href="{{ route('admin.policy.briefs') }}" wire:navigate>Edit in list</flux:button>
                    @endcan
                    @can('policy.publish')
                        @if($brief->status !== 'published')
                            <flux:button class="w-full" variant="primary" icon="megaphone" wire:click="publish">Publish</flux:button>
                        @else
                            <flux:button class="w-full" variant="ghost" icon="eye-slash" wire:click="unpublish">Unpublish</flux:button>
                        @endif
                    @endcan
                    @can('policy.delete')
                        <flux:button class="w-full" variant="danger" icon="trash" wire:click="destroy" wire:confirm="Delete this policy brief permanently?">Delete</flux:button>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
