<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <flux:heading size="xl">Upload Document</flux:heading>
            <flux:subheading>Upload a document for review and approval</flux:subheading>
        </div>
        <flux:button href="{{ route('admin.documents.index') }}" wire:navigate variant="ghost" icon="arrow-left">Library</flux:button>
    </div>

    @if(session('success'))
        <flux:callout variant="success" class="mb-4">{{ session('success') }}</flux:callout>
    @endif

    <div class="grid gap-6 lg:grid-cols-5">
        {{-- Upload Form --}}
        <div class="lg:col-span-3">
            <div class="rounded-2xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <form wire:submit="save" class="space-y-5">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <flux:field class="sm:col-span-2">
                            <flux:label>Document Title <span class="text-red-500">*</span></flux:label>
                            <flux:input wire:model="title" placeholder="e.g. 2026 Q1 Nigeria–Kenya Trade Corridor Report" />
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
                    </div>

                    <flux:field>
                        <flux:label>Description <span class="text-zinc-400 font-normal">(optional)</span></flux:label>
                        <flux:textarea wire:model="description" rows="3" placeholder="Short summary shown alongside the document." />
                        <flux:error name="description" />
                    </flux:field>

                    <flux:field>
                        <flux:label>File <span class="text-red-500">*</span></flux:label>
                        <livewire:components.media-picker
                            wire:model="fileMediaItemId"
                            disk="local"
                            type="document"
                            :folder="$this->computedFolder()"
                            accept=".pdf,.doc,.docx,.xlsx,.csv"
                            key="doc-file-picker"
                        />
                        <flux:error name="fileMediaItemId" />
                        <p class="mt-1 text-xs text-zinc-500">Max file size: 50 MB. PDF, Word, Excel accepted.</p>
                    </flux:field>

                    {{-- Publish to website --}}
                    <div class="flex items-start gap-3 rounded-xl border border-zinc-200 bg-zinc-50 p-4 dark:border-zinc-700 dark:bg-zinc-800/50">
                        <flux:checkbox wire:model="is_public" />
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-zinc-800 dark:text-zinc-100">Publish to website</p>
                            <p class="text-xs text-zinc-500">When enabled, this document becomes downloadable on the public site
                                <span class="font-medium">once it has been approved</span>. Leave off for internal-only documents.</p>
                        </div>
                    </div>

                    <div class="flex gap-2 pt-1">
                        <flux:button type="submit" variant="primary" icon="arrow-up-tray">Upload Document</flux:button>
                        <flux:button href="{{ route('admin.documents.index') }}" wire:navigate variant="ghost">Cancel</flux:button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Recent Uploads --}}
        <div class="lg:col-span-2">
            <div class="rounded-2xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <flux:heading size="lg" class="mb-4">My Recent Uploads</flux:heading>

                @forelse($recentUploads as $doc)
                    <div class="flex items-center gap-3 border-b border-zinc-100 py-3 dark:border-zinc-800 last:border-0">
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-medium text-zinc-900 dark:text-white">{{ $doc->title }}</p>
                            <p class="text-xs text-zinc-500">
                                {{ ucwords(str_replace('-', ' ', $doc->category)) }}
                                &bull; {{ $doc->created_at->format('d M Y') }}
                                @if($doc->is_public)
                                    <span class="ml-1 inline-flex rounded bg-brand-100 px-1.5 py-0.5 text-[10px] font-medium text-brand-700">Public</span>
                                @endif
                            </p>
                        </div>
                        <span class="inline-flex shrink-0 rounded-full px-2 py-0.5 text-xs font-medium
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
</div>
