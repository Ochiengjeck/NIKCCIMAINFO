<div>
    <flux:heading size="xl" class="mb-1">Welcome back, {{ auth()->user()->name }}</flux:heading>
    <flux:subheading class="mb-6">
        NiKCCIMA Backoffice — AfCFTA Corridor Execution Panel
        @if(auth()->user()->chapter)
            &bull; <strong>{{ auth()->user()->chapter->name }}</strong> Chapter
        @else
            &bull; <strong>Global</strong> View
        @endif
    </flux:subheading>

    {{-- KPI Summary Cards (populated in Phase 9) --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:text class="text-xs uppercase tracking-wide text-zinc-500">Total Members</flux:text>
            <p class="mt-1 text-3xl font-bold text-zinc-900 dark:text-white">—</p>
        </div>
        <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:text class="text-xs uppercase tracking-wide text-zinc-500">Pending Applications</flux:text>
            <p class="mt-1 text-3xl font-bold text-zinc-900 dark:text-white">—</p>
        </div>
        <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:text class="text-xs uppercase tracking-wide text-zinc-500">Active Trade Leads</flux:text>
            <p class="mt-1 text-3xl font-bold text-zinc-900 dark:text-white">—</p>
        </div>
        <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:text class="text-xs uppercase tracking-wide text-zinc-500">Open NTBs</flux:text>
            <p class="mt-1 text-3xl font-bold text-zinc-900 dark:text-white">—</p>
        </div>
    </div>

    {{-- Module Grid --}}
    <flux:heading size="lg" class="mb-4">Modules</flux:heading>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @foreach([
            ['icon' => 'users',            'label' => 'Membership',        'desc' => 'Applications, members, categories'],
            ['icon' => 'arrow-trending-up','label' => 'Trade & Investment', 'desc' => 'Leads, deals, corridors, investors'],
            ['icon' => 'document-text',    'label' => 'Policy & Research',  'desc' => 'NTBs, policy briefs, ROO'],
            ['icon' => 'banknotes',        'label' => 'Finance',            'desc' => 'Transactions, invoices, budgets'],
            ['icon' => 'calendar',         'label' => 'Events',             'desc' => 'Trade missions, sector forums'],
            ['icon' => 'briefcase',        'label' => 'Governance',         'desc' => 'Resolutions, MoUs, engagements'],
            ['icon' => 'folder-open',      'label' => 'Documents',          'desc' => 'Knowledge repository'],
            ['icon' => 'chart-pie',        'label' => 'KPI Intelligence',   'desc' => 'Executive dashboards, scorecards'],
            ['icon' => 'shield-check',     'label' => 'Audit Log',          'desc' => 'Immutable activity trail'],
        ] as $module)
        <div class="flex items-start gap-4 rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-100 dark:bg-green-900">
                <flux:icon :name="$module['icon']" class="h-5 w-5 text-green-700 dark:text-green-300" />
            </div>
            <div>
                <flux:heading size="sm">{{ $module['label'] }}</flux:heading>
                <flux:text class="text-xs text-zinc-500">{{ $module['desc'] }}</flux:text>
            </div>
        </div>
        @endforeach
    </div>
</div>
