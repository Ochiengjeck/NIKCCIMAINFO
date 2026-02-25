<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Invoices</flux:heading>
            <flux:subheading>Create and manage member invoices</flux:subheading>
        </div>
        @can('finance.create')
            <flux:button variant="primary" icon="plus" wire:click="openForm()">New Invoice</flux:button>
        @endcan
    </div>

    {{-- Form --}}
    @if($showForm)
        <div class="mb-6 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 p-6">
            <flux:heading size="sm" class="mb-4">{{ $editingId ? 'Edit Invoice' : 'New Invoice' }}</flux:heading>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <flux:field>
                    <flux:label>Member</flux:label>
                    <flux:select wire:model="member_id">
                        <option value="">No member (general)</option>
                        @foreach($members as $member)
                            <option value="{{ $member->id }}">{{ $member->full_name }} ({{ $member->membership_number }})</option>
                        @endforeach
                    </flux:select>
                    <flux:error name="member_id" />
                </flux:field>
                <flux:field>
                    <flux:label>Due Date</flux:label>
                    <flux:input wire:model="due_date" type="date" />
                    <flux:error name="due_date" />
                </flux:field>
                <flux:field>
                    <flux:label>Status</flux:label>
                    <flux:select wire:model="status">
                        <option value="draft">Draft</option>
                        <option value="sent">Sent</option>
                        <option value="paid">Paid</option>
                        <option value="overdue">Overdue</option>
                    </flux:select>
                    <flux:error name="status" />
                </flux:field>
                <flux:field class="sm:col-span-2">
                    <flux:label>Line Items (JSON)</flux:label>
                    <flux:textarea wire:model="line_items" rows="5" placeholder='[{"description": "Membership Fee", "qty": 1, "price": 50000}]' />
                    <flux:error name="line_items" />
                </flux:field>
            </div>
            <div class="mt-4 flex gap-3">
                <flux:button wire:click="save" variant="primary">Save</flux:button>
                <flux:button wire:click="closeForm" variant="ghost">Cancel</flux:button>
            </div>
        </div>
    @endif

    {{-- Table --}}
    <div class="overflow-x-auto rounded-xl border border-zinc-200 dark:border-zinc-700">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Invoice #</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Member</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Total</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Due Date</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-900">
                @forelse($invoices as $invoice)
                    <tr>
                        <td class="px-4 py-3 font-mono text-sm text-zinc-700 dark:text-zinc-300">{{ $invoice->invoice_number }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">{{ $invoice->member?->full_name ?? 'General' }}</td>
                        <td class="px-4 py-3 text-sm font-medium text-zinc-900 dark:text-white">{{ number_format($invoice->total, 2) }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-500">{{ $invoice->due_date?->format('d M Y') }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium
                                @switch($invoice->status)
                                    @case('draft') bg-zinc-100 text-zinc-700 @break
                                    @case('sent') bg-blue-100 text-blue-800 @break
                                    @case('paid') bg-green-100 text-green-800 @break
                                    @case('overdue') bg-red-100 text-red-800 @break
                                @endswitch
                            ">
                                {{ ucfirst($invoice->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            @can('finance.edit')
                                <flux:button size="sm" variant="ghost" wire:click="openForm({{ $invoice->id }})">Edit</flux:button>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-zinc-500">No invoices found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $invoices->links() }}</div>
</div>
