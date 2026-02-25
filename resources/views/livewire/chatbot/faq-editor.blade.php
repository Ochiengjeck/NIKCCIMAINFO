<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <flux:heading size="xl">FAQ Manager</flux:heading>
            <flux:subheading>Manage frequently asked questions for the chatbot</flux:subheading>
        </div>
        <flux:button wire:click="openCreate" icon="plus">Add FAQ</flux:button>
    </div>

    @if(session('success'))
        <flux:callout variant="success" class="mb-4">{{ session('success') }}</flux:callout>
    @endif

    {{-- Form --}}
    @if($showForm)
        <div class="mb-6 rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:heading size="lg" class="mb-4">{{ $editingId ? 'Edit FAQ' : 'New FAQ' }}</flux:heading>
            <form wire:submit="save" class="space-y-4">
                <flux:field>
                    <flux:label>Question <span class="text-red-500">*</span></flux:label>
                    <flux:textarea wire:model="question" rows="2" placeholder="Enter the question…" />
                    <flux:error name="question" />
                </flux:field>
                <flux:field>
                    <flux:label>Answer <span class="text-red-500">*</span></flux:label>
                    <flux:textarea wire:model="answer" rows="4" placeholder="Enter the answer…" />
                    <flux:error name="answer" />
                </flux:field>
                <div class="grid gap-4 sm:grid-cols-3">
                    <flux:field>
                        <flux:label>Category</flux:label>
                        <flux:input wire:model="category" placeholder="e.g. Membership, Finance" />
                        <flux:error name="category" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Sort Order</flux:label>
                        <flux:input wire:model="sortOrder" type="number" min="0" />
                        <flux:error name="sortOrder" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Active</flux:label>
                        <div class="flex items-center gap-2 pt-2">
                            <flux:checkbox wire:model="isActive" id="isActive" />
                            <label for="isActive" class="text-sm text-zinc-600 dark:text-zinc-400">Visible to chatbot</label>
                        </div>
                    </flux:field>
                </div>
                <div class="flex gap-2">
                    <flux:button type="submit" variant="primary">{{ $editingId ? 'Update' : 'Create' }} FAQ</flux:button>
                    <flux:button wire:click="$set('showForm', false)" variant="ghost">Cancel</flux:button>
                </div>
            </form>
        </div>
    @endif

    {{-- Table --}}
    <div class="overflow-x-auto rounded-xl border border-zinc-200 dark:border-zinc-700">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500 w-8">#</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Question</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Category</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-900">
                @forelse($faqs as $faq)
                    <tr>
                        <td class="px-4 py-3 text-xs text-zinc-400">{{ $faq->sort_order }}</td>
                        <td class="px-4 py-3">
                            <p class="text-sm font-medium text-zinc-900 dark:text-white">{{ Str::limit($faq->question, 80) }}</p>
                            <p class="mt-0.5 text-xs text-zinc-500">{{ Str::limit($faq->answer, 100) }}</p>
                        </td>
                        <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">{{ $faq->category ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <button wire:click="toggleActive({{ $faq->id }})" class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium cursor-pointer
                                {{ $faq->is_active ? 'bg-green-100 text-green-800' : 'bg-zinc-100 text-zinc-500' }}">
                                {{ $faq->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex gap-2 justify-end">
                                <flux:button size="sm" variant="ghost" wire:click="edit({{ $faq->id }})">Edit</flux:button>
                                <flux:button size="sm" variant="danger" wire:click="delete({{ $faq->id }})" wire:confirm="Delete this FAQ?">Delete</flux:button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-zinc-500">No FAQs yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $faqs->links() }}</div>
</div>
