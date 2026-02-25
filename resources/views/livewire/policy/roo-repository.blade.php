<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Rules of Origin Repository</flux:heading>
            <flux:subheading>AfCFTA RoO documents and trade classification guides</flux:subheading>
        </div>
        @can('policy.create')
            <flux:button variant="primary" icon="plus" wire:click="$toggle('showUpload')">Upload Document</flux:button>
        @endcan
    </div>

    {{-- Upload Form --}}
    @if($showUpload)
        <div class="mb-6 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 p-6">
            <flux:heading size="sm" class="mb-4">Upload RoO Document</flux:heading>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <flux:field class="sm:col-span-2">
                    <flux:label>Title</flux:label>
                    <flux:input wire:model="title" placeholder="Document title" />
                    <flux:error name="title" />
                </flux:field>
                <flux:field>
                    <flux:label>Trade Classification</flux:label>
                    <flux:input wire:model="trade_classification" placeholder="e.g. HS Chapter 09" />
                    <flux:error name="trade_classification" />
                </flux:field>
                <flux:field>
                    <flux:label>Document File <span class="text-red-500">*</span></flux:label>
                    <livewire:components.media-picker
                        wire:model="fileMediaItemId"
                        disk="local"
                        type="document"
                        folder="roo"
                        accept=".pdf,.doc,.docx"
                        key="roo-doc-picker"
                    />
                    <flux:error name="fileMediaItemId" />
                </flux:field>
                <flux:field class="sm:col-span-2">
                    <flux:label>Description</flux:label>
                    <flux:textarea wire:model="description" rows="3" placeholder="Brief description of this document…" />
                    <flux:error name="description" />
                </flux:field>
            </div>
            <div class="mt-4 flex gap-3">
                <flux:button wire:click="save" variant="primary">Upload</flux:button>
                <flux:button wire:click="$set('showUpload', false)" variant="ghost">Cancel</flux:button>
            </div>
        </div>
    @endif

    {{-- Search --}}
    <div class="mb-4">
        <flux:input wire:model.live="search" placeholder="Search title or classification…" icon="magnifying-glass" class="sm:w-72" />
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto rounded-xl border border-zinc-200 dark:border-zinc-700">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Title</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Classification</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Description</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Uploaded</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-900">
                @forelse($documents as $doc)
                    <tr>
                        <td class="px-4 py-3 font-medium text-zinc-900 dark:text-white">{{ $doc->title }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">{{ $doc->trade_classification ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400 max-w-xs truncate">{{ $doc->description ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-500">{{ $doc->created_at?->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-right">
                            <flux:button size="sm" variant="ghost" icon="arrow-down-tray" href="{{ route('admin.policy.roo') }}">Download</flux:button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-zinc-500">No RoO documents uploaded.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $documents->links() }}</div>
</div>
