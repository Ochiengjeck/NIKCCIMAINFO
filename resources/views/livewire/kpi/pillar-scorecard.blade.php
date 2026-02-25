<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <flux:heading size="xl">Pillar Scorecard</flux:heading>
            <flux:subheading>KPI performance by strategic pillar — {{ now()->format('F Y') }}</flux:subheading>
        </div>
        <flux:button href="{{ route('admin.kpi.dashboard') }}" wire:navigate variant="ghost" icon="arrow-left">Back to Dashboard</flux:button>
    </div>

    @if($pillars->isEmpty())
        <flux:callout variant="warning">
            No scorecard data available for {{ now()->format('F Y') }}. Run <code>php artisan kpi:snapshot</code> to generate snapshots.
        </flux:callout>
    @else
        <div class="grid gap-6 lg:grid-cols-2">
            @foreach($pillars as $pillar => $metrics)
                <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                    <div class="mb-4 flex items-center gap-2">
                        <span class="inline-flex rounded-full px-3 py-1 text-sm font-semibold
                            {{ match($pillar) {
                                'membership' => 'bg-blue-100 text-blue-800',
                                'finance' => 'bg-green-100 text-green-800',
                                'trade' => 'bg-purple-100 text-purple-800',
                                'policy' => 'bg-orange-100 text-orange-800',
                                'events' => 'bg-pink-100 text-pink-800',
                                'governance' => 'bg-teal-100 text-teal-800',
                                default => 'bg-zinc-100 text-zinc-600',
                            } }}">
                            {{ ucfirst($pillar) }}
                        </span>
                    </div>
                    <div class="space-y-3">
                        @foreach($metrics as $snap)
                            <div class="flex items-center justify-between rounded-lg bg-zinc-50 px-4 py-3 dark:bg-zinc-800">
                                <span class="text-sm text-zinc-600 dark:text-zinc-400">{{ $snap->metric_label }}</span>
                                <span class="text-lg font-bold text-zinc-900 dark:text-white">{{ number_format($snap->value, 0) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
