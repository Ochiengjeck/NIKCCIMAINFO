<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Strategic Plans</flux:heading>
            <flux:subheading>Upload and manage strategic planning documents</flux:subheading>
        </div>
        @can('governance.upload')
            <flux:button icon="arrow-up-tray" wire:click="$set('showForm',true)">Upload Plan</flux:button>
        @endcan
    </div>

    @if(session('success'))
        <flux:callout variant="success" class="mb-4" dismissible>{{ session('success') }}</flux:callout>
    @endif

    @if($showForm)
    <div class="mb-6 rounded-xl border border-zinc-700/50 bg-zinc-900/60 p-6">
        <form wire:submit="save" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <flux:field class="sm:col-span-2">
                <flux:label>Title</flux:label>
                <flux:input wire:model="title" />
                <flux:error name="title" />
            </flux:field>
            <flux:field>
                <flux:label>Fiscal Year</flux:label>
                <flux:input wire:model="fiscal_year" placeholder="2026" />
                <flux:error name="fiscal_year" />
            </flux:field>
            <flux:field>
                <flux:label>Status</flux:label>
                <flux:select wire:model="status">
                    <option value="draft">Draft</option>
                    <option value="active">Active</option>
                </flux:select>
            </flux:field>
            <flux:field class="sm:col-span-2">
                <flux:label>File (PDF/DOC) <span class="text-red-500">*</span></flux:label>
                <livewire:components.media-picker
                    wire:model="fileMediaItemId"
                    disk="local"
                    type="document"
                    folder="strategic-plans"
                    accept=".pdf,.doc,.docx"
                    key="strategic-plan-picker"
                />
                <flux:error name="fileMediaItemId" />
            </flux:field>
            <div class="sm:col-span-2 flex gap-3">
                <flux:button type="submit" variant="primary">Upload</flux:button>
                <flux:button wire:click="$set('showForm',false)" variant="ghost">Cancel</flux:button>
            </div>
        </form>
    </div>
    @endif

    <div class="overflow-x-auto rounded-xl border border-zinc-700/50">
        <table class="min-w-full divide-y divide-zinc-700/50">
            <thead class="bg-zinc-800/50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Title</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Fiscal Year</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">File</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Uploaded By</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Date</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-700/50 bg-zinc-900/60">
                @forelse($plans as $plan)
                <tr>
                    <td class="px-4 py-3 font-medium text-white">{{ $plan->title }}</td>
                    <td class="px-4 py-3 text-sm text-zinc-400">{{ $plan->fiscal_year }}</td>
                    <td class="px-4 py-3">
                        <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium
                            {{ $plan->status === 'active' ? 'bg-green-900/50 text-green-400' : 'bg-zinc-800 text-zinc-400' }}">
                            {{ ucfirst($plan->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        @if($plan->mediaItem)
                            <a href="{{ route('admin.media.download', $plan->mediaItem->id) }}"
                               class="inline-flex items-center gap-1 text-xs text-blue-400 hover:text-blue-300">
                                <flux:icon name="arrow-down-tray" class="h-3.5 w-3.5" />
                                {{ $plan->mediaItem->humanSize() }}
                            </a>
                        @else
                            <span class="text-xs text-zinc-600">No file</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm text-zinc-400">{{ $plan->uploader?->name }}</td>
                    <td class="px-4 py-3 text-sm text-zinc-400">{{ $plan->created_at->format('d M Y') }}</td>
                    <td class="px-4 py-3">
                        @can('governance.upload')
                            <flux:button size="sm" variant="ghost"
                                wire:click="delete({{ $plan->id }})"
                                wire:confirm="Remove this strategic plan?">
                                <flux:icon name="trash" class="h-3.5 w-3.5 text-rose-400" />
                            </flux:button>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-zinc-500">No strategic plans uploaded.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $plans->links() }}</div>
</div>
