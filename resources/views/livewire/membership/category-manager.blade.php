<div>
    {{-- ===== Header ===== --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <flux:heading size="xl">Membership Categories</flux:heading>
            <flux:subheading>Define the categories applicants can join and their fees — prices are USD-primary.</flux:subheading>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-medium {{ $grouped ? 'bg-brand-100 text-brand-700' : 'bg-zinc-100 text-zinc-600' }}">
                <span class="h-1.5 w-1.5 rounded-full {{ $grouped ? 'bg-brand-500' : 'bg-zinc-400' }}"></span>
                {{ $grouped ? 'Corporate / Individual grouping' : 'Flat pricing' }}
            </span>
            @can('settings.edit')
                <flux:button icon="plus" variant="primary" wire:click="create">New Category</flux:button>
            @endcan
        </div>
    </div>

    @if(session('success'))
        <flux:callout variant="success" class="mb-4" dismissible>{{ session('success') }}</flux:callout>
    @endif

    @unless($grouped)
        <div class="mb-5 flex items-start gap-3 rounded-xl border border-zinc-200 bg-zinc-50 p-4 text-sm dark:border-zinc-700 dark:bg-zinc-800/50">
            <flux:icon name="information-circle" class="mt-0.5 h-5 w-5 shrink-0 text-zinc-400" />
            <p class="text-zinc-600 dark:text-zinc-400">
                Corporate/Individual grouping is <strong class="text-zinc-800 dark:text-zinc-200">off</strong> — each category uses a single flat price.
                Turn it on under <a href="{{ route('admin.system.settings') }}" wire:navigate class="font-medium text-brand-600 hover:underline">Settings → Membership</a> to set separate Corporate and Individual prices.
            </p>
        </div>
    @endunless

    {{-- ===== List ===== --}}
    <div class="overflow-hidden rounded-2xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
        <div class="hidden grid-cols-12 gap-4 border-b border-zinc-200 bg-zinc-50 px-5 py-3 text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:border-zinc-700 dark:bg-zinc-800/60 sm:grid">
            <div class="col-span-5">Category</div>
            <div class="col-span-4">{{ $grouped ? 'Corporate / Individual' : 'Annual fee' }}</div>
            <div class="col-span-2">Status</div>
            <div class="col-span-1 text-right">Actions</div>
        </div>

        <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
            @forelse($categories as $cat)
                @php
                    $palette = ['bg-brand-100 text-brand-700', 'bg-amber-100 text-amber-700', 'bg-crimson-100 text-crimson-700', 'bg-blue-100 text-blue-700', 'bg-emerald-100 text-emerald-700', 'bg-purple-100 text-purple-700'];
                    $swatch = $palette[$cat->id % count($palette)];
                @endphp
                <div class="grid grid-cols-1 gap-4 px-5 py-4 transition hover:bg-zinc-50 dark:hover:bg-zinc-800/40 sm:grid-cols-12 sm:items-center">
                    {{-- Category --}}
                    <div class="flex items-center gap-3 sm:col-span-5">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl text-sm font-bold {{ $swatch }}">
                            {{ \Illuminate\Support\Str::of($cat->name)->explode(' ')->take(2)->map(fn ($w) => \Illuminate\Support\Str::substr($w, 0, 1))->implode('') }}
                        </div>
                        <div class="min-w-0">
                            <p class="truncate font-semibold text-zinc-900 dark:text-white">{{ $cat->name }}</p>
                            <p class="truncate text-xs text-zinc-400">
                                {{ $cat->slug }}@if($cat->description) &middot; {{ \Illuminate\Support\Str::limit($cat->description, 50) }}@endif
                            </p>
                        </div>
                    </div>

                    {{-- Pricing --}}
                    <div class="sm:col-span-4">
                        @if($grouped)
                            <div class="flex flex-wrap gap-2">
                                <span class="inline-flex items-center gap-1.5 rounded-lg border px-2.5 py-1 text-xs {{ $cat->corporate_enabled ? 'border-brand-200 bg-brand-50 text-brand-700' : 'border-zinc-200 bg-zinc-50 text-zinc-400 line-through' }}">
                                    <span class="font-semibold">Corp</span>
                                    <span>{{ $cat->corporate_enabled ? $cat->priceLabelUsd('corporate') : 'n/a' }}</span>
                                </span>
                                <span class="inline-flex items-center gap-1.5 rounded-lg border px-2.5 py-1 text-xs {{ $cat->individual_enabled ? 'border-amber-200 bg-amber-50 text-amber-700' : 'border-zinc-200 bg-zinc-50 text-zinc-400 line-through' }}">
                                    <span class="font-semibold">Indiv</span>
                                    <span>{{ $cat->individual_enabled ? $cat->priceLabelUsd('individual') : 'n/a' }}</span>
                                </span>
                            </div>
                        @else
                            @php $flatNgn = (float) $cat->feeNgn(); @endphp
                            <p class="font-semibold text-zinc-900 dark:text-white">{{ $cat->priceLabelUsd() }}</p>
                            @if(! $cat->price_on_request && $flatNgn > 0)
                                <p class="text-xs text-zinc-400">≈ ₦{{ number_format($flatNgn) }}</p>
                            @endif
                        @endif
                    </div>

                    {{-- Status --}}
                    <div class="sm:col-span-2">
                        <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-medium {{ $cat->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300' : 'bg-zinc-100 text-zinc-500 dark:bg-zinc-800 dark:text-zinc-400' }}">
                            <span class="h-1.5 w-1.5 rounded-full {{ $cat->is_active ? 'bg-green-500' : 'bg-zinc-400' }}"></span>
                            {{ $cat->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-1 sm:col-span-1 sm:justify-end">
                        @can('settings.edit')
                            <flux:button size="sm" variant="ghost" icon="pencil-square" wire:click="edit({{ $cat->id }})" title="Edit" />
                            <flux:button size="sm" variant="ghost" icon="{{ $cat->is_active ? 'eye-slash' : 'eye' }}" wire:click="toggleActive({{ $cat->id }})" title="{{ $cat->is_active ? 'Deactivate' : 'Activate' }}" />
                        @endcan
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center px-5 py-16 text-center">
                    <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-zinc-100 dark:bg-zinc-800">
                        <flux:icon name="rectangle-stack" class="h-6 w-6 text-zinc-400" />
                    </div>
                    <p class="font-medium text-zinc-700 dark:text-zinc-300">No categories yet</p>
                    <p class="mb-4 text-sm text-zinc-500">Create your first membership category to get started.</p>
                    @can('settings.edit')
                        <flux:button size="sm" icon="plus" wire:click="create">New Category</flux:button>
                    @endcan
                </div>
            @endforelse
        </div>
    </div>

    <div class="mt-4">{{ $categories->links() }}</div>

    {{-- ===== Create / Edit Modal ===== --}}
    <flux:modal wire:model="showForm" name="category-form" class="w-full md:max-w-2xl">
        <form wire:submit="save" class="space-y-6">
            <div>
                <flux:heading size="lg">{{ $editingId ? 'Edit Category' : 'New Category' }}</flux:heading>
                <flux:subheading>{{ $editingId ? 'Update this membership category and its pricing.' : 'Add a new membership category applicants can choose.' }}</flux:subheading>
            </div>

            {{-- Details --}}
            <div class="space-y-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <flux:field>
                        <flux:label>Category Name</flux:label>
                        <flux:input wire:model.blur="name" placeholder="e.g. Platinum Member" />
                        <flux:error name="name" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Slug</flux:label>
                        <flux:input wire:model="slug" placeholder="platinum" />
                        <flux:error name="slug" />
                    </flux:field>
                </div>
                <flux:field>
                    <flux:label>Description / Benefits</flux:label>
                    <flux:textarea wire:model="description" rows="2" placeholder="What this category includes…" />
                    <flux:error name="description" />
                </flux:field>
            </div>

            {{-- Pricing --}}
            <div class="space-y-3 border-t border-zinc-100 pt-5 dark:border-zinc-800">
                <p class="text-sm font-semibold text-zinc-700 dark:text-zinc-200">Pricing</p>
                @if($grouped)
                    <p class="text-xs text-zinc-500">Enable a group to offer this category to it, and set each group's price. Leave a fee blank for Free.</p>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="rounded-xl border border-zinc-200 p-4 dark:border-zinc-700">
                            <flux:checkbox wire:model="corporate_enabled" label="Offer to Corporate" />
                            <div class="mt-3 grid grid-cols-2 gap-3">
                                <flux:field>
                                    <flux:label>USD</flux:label>
                                    <flux:input type="number" wire:model="corporate_fee_usd" step="0.01" placeholder="Free" />
                                </flux:field>
                                <flux:field>
                                    <flux:label>NGN</flux:label>
                                    <flux:input type="number" wire:model="corporate_fee_ngn" step="0.01" placeholder="—" />
                                </flux:field>
                            </div>
                        </div>
                        <div class="rounded-xl border border-zinc-200 p-4 dark:border-zinc-700">
                            <flux:checkbox wire:model="individual_enabled" label="Offer to Individual" />
                            <div class="mt-3 grid grid-cols-2 gap-3">
                                <flux:field>
                                    <flux:label>USD</flux:label>
                                    <flux:input type="number" wire:model="individual_fee_usd" step="0.01" placeholder="Free" />
                                </flux:field>
                                <flux:field>
                                    <flux:label>NGN</flux:label>
                                    <flux:input type="number" wire:model="individual_fee_ngn" step="0.01" placeholder="—" />
                                </flux:field>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <flux:field>
                            <flux:label>Fee (USD)</flux:label>
                            <flux:description>Primary price. Leave blank for Free.</flux:description>
                            <flux:input type="number" wire:model="fee_usd" step="0.01" placeholder="Free" />
                            <flux:error name="fee_usd" />
                        </flux:field>
                        <flux:field>
                            <flux:label>Fee (NGN)</flux:label>
                            <flux:description>Secondary reference (optional).</flux:description>
                            <flux:input type="number" wire:model="fee_ngn" step="0.01" placeholder="—" />
                            <flux:error name="fee_ngn" />
                        </flux:field>
                    </div>
                @endif
            </div>

            {{-- Display --}}
            <div class="space-y-4 border-t border-zinc-100 pt-5 dark:border-zinc-800">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <flux:field>
                        <flux:label>Sort Order</flux:label>
                        <flux:input type="number" wire:model="sort_order" />
                        <flux:error name="sort_order" />
                    </flux:field>
                    <div class="flex items-end pb-1">
                        <flux:checkbox wire:model="is_active" label="Active (visible to applicants)" />
                    </div>
                </div>
                <label class="flex items-start gap-3 rounded-xl border border-zinc-200 p-4 dark:border-zinc-700">
                    <flux:checkbox wire:model="price_on_request" />
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-zinc-800 dark:text-zinc-100">Show price as "On request"</p>
                        <p class="text-xs text-zinc-500">When on, the public website and application form display <strong>On request</strong> instead of the figure — even if a price is set above.</p>
                    </div>
                </label>
            </div>

            <div class="flex justify-end gap-3 border-t border-zinc-100 pt-5 dark:border-zinc-800">
                <flux:button wire:click="cancel" variant="ghost">Cancel</flux:button>
                <flux:button type="submit" variant="primary" icon="check">{{ $editingId ? 'Save Changes' : 'Create Category' }}</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
