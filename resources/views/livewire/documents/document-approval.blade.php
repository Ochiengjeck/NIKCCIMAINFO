<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <flux:heading size="xl">Document Approval</flux:heading>
            <flux:subheading>Review and approve pending documents</flux:subheading>
        </div>
        <flux:button href="{{ route('admin.documents.index') }}" wire:navigate variant="ghost" icon="arrow-left">Back to Library</flux:button>
    </div>

    @if(session('success'))
        <flux:callout variant="success" class="mb-4">{{ session('success') }}</flux:callout>
    @endif

    <div class="overflow-x-auto rounded-xl border border-zinc-200 dark:border-zinc-700">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Title</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Category</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Uploaded By</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Version</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Visibility</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Date</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-900">
                @forelse($documents as $doc)
                    <tr>
                        <td class="px-4 py-3 font-medium text-zinc-900 dark:text-white">{{ $doc->title }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                {{ ucwords(str_replace('-', ' ', $doc->category)) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">{{ $doc->uploader?->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">v{{ $doc->version }}</td>
                        <td class="px-4 py-3">
                            <button type="button" wire:click="togglePublic({{ $doc->id }})"
                                class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-medium transition
                                    {{ $doc->is_public ? 'bg-brand-100 text-brand-700 hover:bg-brand-200' : 'bg-zinc-100 text-zinc-600 hover:bg-zinc-200' }}"
                                title="Click to toggle public visibility">
                                {{ $doc->is_public ? 'Public' : 'Internal' }}
                            </button>
                        </td>
                        <td class="px-4 py-3 text-sm text-zinc-500">{{ $doc->created_at->format('d M Y') }}</td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2">
                                <flux:button
                                    size="sm"
                                    variant="primary"
                                    wire:click="approve({{ $doc->id }})"
                                    wire:confirm="Approve this document?"
                                    icon="check"
                                >Approve</flux:button>
                                <flux:button
                                    size="sm"
                                    variant="danger"
                                    wire:click="reject({{ $doc->id }})"
                                    wire:confirm="Reject and archive this document?"
                                    icon="x-mark"
                                >Reject</flux:button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-zinc-500">No documents pending approval.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $documents->links() }}</div>
</div>
