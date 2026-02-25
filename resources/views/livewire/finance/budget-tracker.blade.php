<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Budget Tracker</flux:heading>
            <flux:subheading>Monitor chapter budget allocations and expenditure</flux:subheading>
        </div>
    </div>

    {{-- Budget Grid --}}
    <div class="overflow-x-auto rounded-xl border border-zinc-200 dark:border-zinc-700">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Pillar</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Fiscal Year</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Allocated</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Spent</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Remaining</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Utilisation</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-900">
                @forelse($budgets as $budget)
                    @php
                        $remaining = $budget->allocated_amount - $budget->spent_amount;
                        $pct = $budget->allocated_amount > 0
                            ? round(($budget->spent_amount / $budget->allocated_amount) * 100)
                            : 0;
                    @endphp
                    <tr>
                        <td class="px-4 py-3 font-medium text-zinc-900 dark:text-white">{{ ucfirst($budget->pillar) }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">{{ $budget->fiscal_year }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">{{ number_format($budget->allocated_amount, 2) }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">{{ number_format($budget->spent_amount, 2) }}</td>
                        <td class="px-4 py-3 text-sm {{ $remaining < 0 ? 'text-red-600 font-semibold' : 'text-zinc-600 dark:text-zinc-400' }}">
                            {{ number_format($remaining, 2) }}
                        </td>
                        <td class="px-4 py-3 w-48">
                            <div class="flex items-center gap-2">
                                <div class="flex-1 bg-zinc-200 dark:bg-zinc-700 rounded-full h-2">
                                    <div class="h-2 rounded-full {{ $pct > 90 ? 'bg-red-500' : ($pct > 70 ? 'bg-yellow-500' : 'bg-green-500') }}"
                                        style="width: {{ min($pct, 100) }}%"></div>
                                </div>
                                <span class="text-xs text-zinc-500 w-8">{{ $pct }}%</span>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-zinc-500">No budgets configured.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $budgets->links() }}</div>
</div>
