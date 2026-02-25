<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <flux:heading size="xl">Chapter KPI Comparison</flux:heading>
            <flux:subheading>Side-by-side metrics comparison for {{ now()->format('F Y') }}</flux:subheading>
        </div>
        <flux:button href="{{ route('admin.kpi.dashboard') }}" wire:navigate variant="ghost" icon="arrow-left">Back to Dashboard</flux:button>
    </div>

    @if($chapters->isEmpty() || $snapshots->isEmpty())
        <flux:callout variant="warning">
            No chapter KPI data available for {{ now()->format('F Y') }}. Run <code>php artisan kpi:snapshot</code> to generate snapshots.
        </flux:callout>
    @else
        <div class="overflow-x-auto rounded-xl border border-zinc-200 dark:border-zinc-700">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Metric</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Pillar</th>
                        @foreach($chapters as $chapter)
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">{{ $chapter->name }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-900">
                    @php
                        // Build unified metric list from all chapter snapshots
                        $allMetrics = collect();
                        foreach ($snapshots as $chapterId => $chapterSnaps) {
                            foreach ($chapterSnaps as $snap) {
                                $allMetrics->put($snap->metric_key, ['label' => $snap->metric_label, 'pillar' => $snap->pillar]);
                            }
                        }
                    @endphp
                    @foreach($allMetrics as $key => $info)
                        <tr>
                            <td class="px-4 py-3 font-medium text-zinc-900 dark:text-white">{{ $info['label'] }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    {{ ucfirst($info['pillar']) }}
                                </span>
                            </td>
                            @foreach($chapters as $chapter)
                                @php
                                    $snap = $snapshots->get($chapter->id)?->firstWhere('metric_key', $key);
                                @endphp
                                <td class="px-4 py-3 text-sm text-zinc-700 dark:text-zinc-300">
                                    {{ $snap ? number_format($snap->value, 0) : '—' }}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
