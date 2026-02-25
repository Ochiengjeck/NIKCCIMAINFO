<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <flux:heading size="xl">KPI Executive Dashboard</flux:heading>
            <flux:subheading>Live performance metrics across all pillars</flux:subheading>
        </div>
        <div class="flex gap-2">
            <flux:button href="{{ route('admin.kpi.scorecard') }}" wire:navigate variant="ghost" icon="clipboard-document-list">Pillar Scorecard</flux:button>
            <flux:button href="{{ route('admin.kpi.comparison') }}" wire:navigate variant="ghost" icon="chart-bar">Chapter Comparison</flux:button>
        </div>
    </div>

    {{-- Live Metrics Grid --}}
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">Live Metrics (Current Session)</flux:heading>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @foreach($liveMetrics as $metric)
                <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
                    <div class="flex items-start justify-between">
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $metric['label'] }}</p>
                        <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium
                            {{ match($metric['pillar']) {
                                'membership' => 'bg-blue-100 text-blue-800',
                                'finance' => 'bg-green-100 text-green-800',
                                'trade' => 'bg-purple-100 text-purple-800',
                                'policy' => 'bg-orange-100 text-orange-800',
                                'events' => 'bg-pink-100 text-pink-800',
                                default => 'bg-zinc-100 text-zinc-600',
                            } }}">
                            {{ ucfirst($metric['pillar']) }}
                        </span>
                    </div>
                    <p class="mt-2 text-3xl font-bold text-zinc-900 dark:text-white">
                        {{ is_numeric($metric['value']) ? number_format($metric['value'], 0) : $metric['value'] }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Snapshot Metrics by Pillar --}}
    @if($snapshots->isNotEmpty())
        <div>
            <flux:heading size="lg" class="mb-4">Snapshot — {{ now()->format('F Y') }}</flux:heading>
            @foreach($snapshots as $pillar => $metrics)
                <div class="mb-6">
                    <h3 class="mb-3 text-sm font-semibold uppercase tracking-wide text-zinc-500">{{ ucfirst($pillar) }}</h3>
                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        @foreach($metrics as $snap)
                            <div class="rounded-xl border border-zinc-100 bg-zinc-50 p-4 dark:border-zinc-800 dark:bg-zinc-800">
                                <p class="text-xs text-zinc-500">{{ $snap->metric_label }}</p>
                                <p class="mt-1 text-2xl font-bold text-zinc-900 dark:text-white">{{ number_format($snap->value, 0) }}</p>
                                <p class="text-xs text-zinc-400">{{ $snap->recorded_at->format('d M Y H:i') }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <flux:callout variant="warning">
            No KPI snapshots recorded yet for {{ now()->format('F Y') }}. Run <code>php artisan kpi:snapshot</code> to generate them.
        </flux:callout>
    @endif
</div>
