<div>
    <flux:heading size="xl" class="mb-1">Revenue Report</flux:heading>
    <flux:subheading class="mb-6">Revenue breakdown by stream and currency</flux:subheading>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4 mb-8">
        @foreach($summary as $stream)
            <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
                <flux:text class="text-xs uppercase tracking-wide text-zinc-500">{{ ucwords(str_replace('-', ' ', $stream->type)) }}</flux:text>
                <p class="mt-1 text-2xl font-bold text-zinc-900 dark:text-white">{{ $stream->currency }} {{ number_format($stream->total, 0) }}</p>
                <p class="text-xs text-zinc-400 mt-1">{{ $stream->count }} transaction{{ $stream->count !== 1 ? 's' : '' }}</p>
            </div>
        @endforeach
        @if($summary->isEmpty())
            <div class="col-span-full text-center text-zinc-500 py-8">No paid transactions yet.</div>
        @endif
    </div>

    {{-- Revenue Table --}}
    @if($summary->isNotEmpty())
    <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 overflow-hidden">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Revenue Stream</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Currency</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Total</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Transactions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-900">
                @foreach($summary as $stream)
                <tr>
                    <td class="px-4 py-3 font-medium text-zinc-900 dark:text-white">{{ ucwords(str_replace('-', ' ', $stream->type)) }}</td>
                    <td class="px-4 py-3 text-sm">{{ $stream->currency }}</td>
                    <td class="px-4 py-3 text-sm font-mono">{{ number_format($stream->total, 2) }}</td>
                    <td class="px-4 py-3 text-sm">{{ $stream->count }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <td class="px-4 py-3 font-bold text-zinc-900 dark:text-white" colspan="2">Total (NGN)</td>
                    <td class="px-4 py-3 font-bold font-mono">{{ number_format($summary->where('currency','NGN')->sum('total'), 2) }}</td>
                    <td class="px-4 py-3 font-bold">{{ $summary->sum('count') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
    @endif
</div>
