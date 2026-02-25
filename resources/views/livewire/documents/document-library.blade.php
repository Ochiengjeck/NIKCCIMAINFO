<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <flux:heading size="xl">Document Library</flux:heading>
            <flux:subheading>Browse and download organisational documents</flux:subheading>
        </div>
        @can('documents.upload')
            <flux:button href="{{ route('admin.documents.upload') }}" wire:navigate icon="arrow-up-tray">Upload Document</flux:button>
        @endcan
    </div>

    @if(session('success'))
        <flux:callout variant="success" class="mb-4">{{ session('success') }}</flux:callout>
    @endif

    {{-- Filters --}}
    <div class="mb-4 flex flex-col gap-3 sm:flex-row">
        <flux:input wire:model.live="search" placeholder="Search by title…" icon="magnifying-glass" class="sm:w-72" />
        <flux:select wire:model.live="categoryFilter" class="sm:w-44">
            <flux:select.option value="">All Categories</flux:select.option>
            <flux:select.option value="governance">Governance</flux:select.option>
            <flux:select.option value="mou">MoU</flux:select.option>
            <flux:select.option value="policy-brief">Policy Brief</flux:select.option>
            <flux:select.option value="trade-report">Trade Report</flux:select.option>
            <flux:select.option value="audit">Audit</flux:select.option>
            <flux:select.option value="meeting-minutes">Meeting Minutes</flux:select.option>
            <flux:select.option value="other">Other</flux:select.option>
        </flux:select>
        <flux:select wire:model.live="statusFilter" class="sm:w-40">
            <flux:select.option value="">All Statuses</flux:select.option>
            <flux:select.option value="draft">Draft</flux:select.option>
            <flux:select.option value="pending-approval">Pending Approval</flux:select.option>
            <flux:select.option value="approved">Approved</flux:select.option>
            <flux:select.option value="archived">Archived</flux:select.option>
        </flux:select>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto rounded-xl border border-zinc-200 dark:border-zinc-700">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Title</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Category</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Version</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Uploaded By</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Date</th>
                    <th class="px-4 py-3"></th>
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
                        <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">v{{ $doc->version }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium
                                {{ $doc->status === 'approved' ? 'bg-green-100 text-green-800' :
                                   ($doc->status === 'pending-approval' ? 'bg-yellow-100 text-yellow-800' :
                                   ($doc->status === 'archived' ? 'bg-red-100 text-red-800' : 'bg-zinc-100 text-zinc-600')) }}">
                                {{ ucwords(str_replace('-', ' ', $doc->status)) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">{{ $doc->uploader?->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-500">{{ $doc->created_at->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-right">
                            @if($doc->status === 'approved')
                                <flux:button size="sm" href="{{ route('admin.documents.download', $doc) }}" icon="arrow-down-tray">Download</flux:button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-zinc-500">No documents found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $documents->links() }}</div>
</div>
