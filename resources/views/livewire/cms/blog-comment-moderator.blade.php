<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <flux:heading size="xl">Blog Comments</flux:heading>
            <flux:subheading>
                Moderate reader comments
                @if($pendingCount > 0)
                    <span class="ml-1 inline-flex items-center rounded-full bg-amber-100 px-2 py-0.5 text-xs font-medium text-amber-800 dark:bg-amber-900 dark:text-amber-300">{{ $pendingCount }} pending</span>
                @endif
            </flux:subheading>
        </div>
        <flux:button :href="route('admin.cms.blog')" wire:navigate variant="ghost" icon="arrow-left">Back to Posts</flux:button>
    </div>

    @if(session('success'))
        <flux:callout variant="success" class="mb-4">{{ session('success') }}</flux:callout>
    @endif

    <div class="mb-4">
        <flux:select wire:model.live="statusFilter" class="w-44">
            <flux:select.option value="">All comments</flux:select.option>
            <flux:select.option value="pending">Pending</flux:select.option>
            <flux:select.option value="approved">Approved</flux:select.option>
            <flux:select.option value="spam">Spam</flux:select.option>
        </flux:select>
    </div>

    <div class="space-y-3">
        @forelse($comments as $comment)
            <div class="rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-900">
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-sm font-medium text-zinc-900 dark:text-white">{{ $comment->author_name }}</span>
                            <span class="text-xs text-zinc-400">{{ $comment->author_email }}</span>
                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium
                                @class([
                                    'bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-300' => $comment->status === 'pending',
                                    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' => $comment->status === 'approved',
                                    'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' => $comment->status === 'spam',
                                ])">{{ ucfirst($comment->status) }}</span>
                        </div>
                        <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">{{ $comment->body }}</p>
                        <p class="mt-2 text-xs text-zinc-400">
                            On <span class="font-medium">{{ $comment->post?->title ?? 'deleted post' }}</span>
                            &middot; {{ $comment->created_at->format('d M Y H:i') }}
                        </p>
                    </div>
                    @can('cms.publish')
                        <div class="flex shrink-0 gap-2">
                            @if($comment->status !== 'approved')
                                <flux:button wire:click="approve({{ $comment->id }})" size="sm" variant="ghost" icon="check" class="text-green-600">Approve</flux:button>
                            @endif
                            @if($comment->status !== 'spam')
                                <flux:button wire:click="markSpam({{ $comment->id }})" size="sm" variant="ghost" icon="no-symbol">Spam</flux:button>
                            @endif
                            <flux:button wire:click="delete({{ $comment->id }})" wire:confirm="Delete this comment?" size="sm" variant="ghost" icon="trash" class="text-red-500" />
                        </div>
                    @endcan
                </div>
            </div>
        @empty
            <div class="rounded-xl border border-dashed border-zinc-300 bg-zinc-50 py-12 text-center text-sm text-zinc-500 dark:border-zinc-700 dark:bg-zinc-900">
                No comments found.
            </div>
        @endforelse
    </div>

    <div class="mt-4">{{ $comments->links() }}</div>
</div>
