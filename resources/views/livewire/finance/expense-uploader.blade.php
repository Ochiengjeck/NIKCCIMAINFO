<div>
    <flux:heading size="xl" class="mb-1">Expense Submissions</flux:heading>
    <flux:subheading class="mb-6">Submit and track chapter expenses for approval</flux:subheading>

    @if(session('success'))
        <flux:callout variant="success" class="mb-4" dismissible>{{ session('success') }}</flux:callout>
    @endif

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="lg:col-span-1">
            <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <flux:heading size="lg" class="mb-4">Submit Expense</flux:heading>
                <form wire:submit="save" class="space-y-4">
                    <flux:field>
                        <flux:label>Description</flux:label>
                        <flux:input wire:model="description" placeholder="What was this expense for?" />
                        <flux:error name="description" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Amount (NGN)</flux:label>
                        <flux:input type="number" wire:model="amount" step="0.01" min="0" />
                        <flux:error name="amount" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Budget (optional)</flux:label>
                        <flux:select wire:model="budget_id">
                            <option value="">None</option>
                            @foreach($budgets as $budget)
                                <option value="{{ $budget->id }}">{{ ucfirst($budget->pillar) }} — {{ $budget->fiscal_year }}</option>
                            @endforeach
                        </flux:select>
                    </flux:field>
                    <flux:field>
                        <flux:label>Receipt (optional)</flux:label>
                        <livewire:components.media-picker
                            wire:model="receiptMediaItemId"
                            disk="local"
                            type="document"
                            folder="receipts"
                            accept=".pdf,.jpg,.jpeg,.png"
                            key="expense-receipt-picker"
                        />
                        <flux:error name="receiptMediaItemId" />
                    </flux:field>
                    <flux:button type="submit" variant="primary" class="w-full">Submit Expense</flux:button>
                </form>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 overflow-hidden">
                <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                    <thead class="bg-zinc-50 dark:bg-zinc-800">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Description</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Amount</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Submitted By</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-900">
                        @forelse($expenses as $expense)
                            <tr>
                                <td class="px-4 py-3 text-sm font-medium text-zinc-900 dark:text-white">{{ $expense->description }}</td>
                                <td class="px-4 py-3 text-sm">NGN {{ number_format($expense->amount, 2) }}</td>
                                <td class="px-4 py-3 text-sm text-zinc-500">{{ $expense->submitter?->name }}</td>
                                <td class="px-4 py-3">
                                    @if($expense->approved_at)
                                        <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium bg-green-100 text-green-800">Approved</span>
                                    @else
                                        <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-zinc-500">{{ $expense->created_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-4 py-8 text-center text-zinc-500">No expenses submitted yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $expenses->links() }}</div>
        </div>
    </div>
</div>
