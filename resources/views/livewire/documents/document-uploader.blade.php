<div>
    <div class="mb-6">
        <flux:heading size="xl">Upload Document</flux:heading>
        <flux:subheading>Upload a document for review and approval</flux:subheading>
    </div>

    @if(session('success'))
        <flux:callout variant="success" class="mb-4">{{ session('success') }}</flux:callout>
    @endif

    <div class="grid gap-6 lg:grid-cols-2">
        {{-- Upload Form --}}
        <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:heading size="lg" class="mb-4">New Upload</flux:heading>

            <form wire:submit="save" class="space-y-4">
                <flux:field>
                    <flux:label>Document Title <span class="text-red-500">*</span></flux:label>
                    <flux:input wire:model="title" placeholder="Enter document title" />
                    <flux:error name="title" />
                </flux:field>

                <flux:field>
                    <flux:label>Category <span class="text-red-500">*</span></flux:label>
                    <flux:select wire:model="category">
                        <flux:select.option value="governance">Governance</flux:select.option>
                        <flux:select.option value="mou">MoU</flux:select.option>
                        <flux:select.option value="policy-brief">Policy Brief</flux:select.option>
                        <flux:select.option value="trade-report">Trade Report</flux:select.option>
                        <flux:select.option value="audit">Audit</flux:select.option>
                        <flux:select.option value="meeting-minutes">Meeting Minutes</flux:select.option>
                        <flux:select.option value="other">Other</flux:select.option>
                    </flux:select>
                    <flux:error name="category" />
                </flux:field>

                <flux:field>
                    <flux:label>Version</flux:label>
                    <flux:input wire:model="version" type="number" min="1" />
                    <flux:error name="version" />
                </flux:field>

                <flux:field>
                    <flux:label>File <span class="text-red-500">*</span></flux:label>
                    <livewire:components.media-picker
                        wire:model="fileMediaItemId"
                        disk="local"
                        type="document"
                        :folder="$this->computedFolder()"
                        accept=".pdf,.doc,.docx,.xlsx,.csv"
                        :key="'doc-picker-' . $category"
                    />
                    <flux:error name="fileMediaItemId" />
                    <p class="mt-1 text-xs text-zinc-500">Max file size: 50 MB. PDF, Word, Excel accepted.</p>
                </flux:field>

                <div class="flex gap-2 pt-2">
                    <flux:button type="submit" variant="primary" icon="arrow-up-tray">Upload Document</flux:button>
                    <flux:button href="{{ route('admin.documents.index') }}" wire:navigate variant="ghost">Cancel</flux:button>
                </div>
            </form>
        </div>

        {{-- Recent Uploads --}}
        <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:heading size="lg" class="mb-4">My Recent Uploads</flux:heading>

            @forelse($recentUploads as $doc)
                <div class="flex items-center justify-between border-b border-zinc-100 py-3 dark:border-zinc-800 last:border-0">
                    <div>
                        <p class="text-sm font-medium text-zinc-900 dark:text-white">{{ $doc->title }}</p>
                        <p class="text-xs text-zinc-500">{{ ucwords(str_replace('-', ' ', $doc->category)) }} &bull; {{ $doc->created_at->format('d M Y') }}</p>
                    </div>
                    <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium
                        {{ $doc->status === 'approved' ? 'bg-green-100 text-green-800' :
                           ($doc->status === 'pending-approval' ? 'bg-yellow-100 text-yellow-800' : 'bg-zinc-100 text-zinc-600') }}">
                        {{ ucwords(str_replace('-', ' ', $doc->status)) }}
                    </span>
                </div>
            @empty
                <p class="text-sm text-zinc-500">No uploads yet.</p>
            @endforelse
        </div>
    </div>
</div>
