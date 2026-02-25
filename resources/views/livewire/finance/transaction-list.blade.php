<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Financial Transactions</flux:heading>
            <flux:subheading>All chapter payment and revenue transactions</flux:subheading>
        </div>
    </div>

    {{-- Filters --}}
    <div class="mb-4 flex flex-col gap-3 sm:flex-row">
        <flux:input wire:model.live="search" placeholder="Search reference or description…" icon="magnifying-glass" class="sm:w-64" />
        <flux:select wire:model.live="typeFilter" class="sm:w-44">
            <option value="">All Types</option>
            <option value="membership-fee">Membership Fee</option>
            <option value="renewal">Renewal</option>
            <option value="event-ticket">Event Ticket</option>
            <option value="sponsorship">Sponsorship</option>
            <option value="other">Other</option>
        </flux:select>
        <flux:select wire:model.live="statusFilter" class="sm:w-36">
            <option value="">All Statuses</option>
            <option value="pending">Pending</option>
            <option value="paid">Paid</option>
            <option value="failed">Failed</option>
            <option value="refunded">Refunded</option>
        </flux:select>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto rounded-xl border border-zinc-200 dark:border-zinc-700">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Reference</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Member</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Type</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Amount</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Paid At</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-900">
                @forelse($transactions as $txn)
                    <tr>
                        <td class="px-4 py-3 font-mono text-sm text-zinc-700 dark:text-zinc-300">{{ $txn->reference }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">{{ $txn->member?->full_name ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">{{ ucfirst(str_replace('-', ' ', $txn->type)) }}</td>
                        <td class="px-4 py-3 text-sm font-medium text-zinc-900 dark:text-white">
                            {{ number_format($txn->amount, 2) }} {{ $txn->currency }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium
                                @switch($txn->status)
                                    @case('paid') bg-green-100 text-green-800 @break
                                    @case('pending') bg-yellow-100 text-yellow-800 @break
                                    @case('failed') bg-red-100 text-red-800 @break
                                    @case('refunded') bg-purple-100 text-purple-800 @break
                                @endswitch
                            ">
                                {{ ucfirst($txn->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-zinc-500">{{ $txn->paid_at?->format('d M Y') ?? '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-zinc-500">No transactions found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $transactions->links() }}</div>
</div>
