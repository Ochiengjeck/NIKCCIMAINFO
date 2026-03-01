<div class="min-h-screen p-6 space-y-6">

    {{-- ═══ HERO HEADER ═══ --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-zinc-100" style="font-family:'Playfair Display',serif">
                Welcome back, {{ auth()->user()->name }}
            </h1>
            <p class="mt-1 text-sm text-zinc-500">
                NiKCCIMA AfCFTA Corridor Execution Panel
                @if(auth()->user()->chapter)
                    &bull; <span class="text-zinc-400">{{ auth()->user()->chapter->name }} Chapter</span>
                @else
                    &bull; <span class="text-zinc-400">Global View</span>
                @endif
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-2 sm:shrink-0">
            {{-- Date badge --}}
            <span class="inline-flex items-center gap-1.5 rounded-lg border border-zinc-700/50 bg-zinc-800/60 px-3 py-1.5 text-xs text-zinc-400">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                {{ now()->format('D, d M Y') }}
            </span>
            {{-- Role badges --}}
            @foreach(auth()->user()->getRoleNames() as $role)
                <span class="inline-flex items-center rounded-full bg-green-900/40 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-wide text-green-300">
                    {{ $role }}
                </span>
            @endforeach
        </div>
    </div>

    {{-- ═══ KPI STAT CARDS (role-aware) ═══ --}}
    @php
        $hasAnyStat = auth()->user()->canAny([
            'members.view','finance.view','trade.view','policy.view','events.view'
        ]);
    @endphp

    @if($hasAnyStat)
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4 2xl:grid-cols-5">

        {{-- Members Card --}}
        @can('members.view')
        <div class="relative overflow-hidden rounded-xl border border-zinc-700/50 bg-zinc-800/50 p-5 shadow-sm">
            <div class="absolute inset-y-0 left-0 w-1 rounded-l-xl bg-green-500"></div>
            <div class="flex items-start justify-between">
                <div class="min-w-0 flex-1 pl-2">
                    <p class="text-[10px] font-semibold uppercase tracking-widest text-zinc-500">Members</p>
                    <p class="mt-1 text-3xl font-bold tabular-nums text-zinc-100">{{ number_format($totalMembers) }}</p>
                    <div class="mt-2 space-y-0.5">
                        <p class="text-xs text-zinc-500">
                            <span class="font-medium text-green-400">{{ number_format($activeMembers) }}</span> active
                            &bull;
                            <span class="font-medium text-zinc-300">{{ number_format($newMembersThisMonth) }}</span> new this month
                        </p>
                        @if($pendingApplications > 0)
                            <p class="text-xs">
                                <span class="inline-flex items-center gap-1 rounded-full bg-amber-500/15 px-2 py-0.5 text-[10px] font-semibold text-amber-400">
                                    {{ $pendingApplications }} pending {{ Str::plural('application', $pendingApplications) }}
                                </span>
                            </p>
                        @endif
                    </div>
                </div>
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-green-500/10">
                    <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
            </div>
            <a href="{{ route('admin.membership.members') }}" wire:navigate class="mt-3 inline-flex items-center gap-1 text-[11px] font-medium text-green-500 hover:text-green-400 pl-2">
                View members
                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
        @endcan

        {{-- Finance Card --}}
        @can('finance.view')
        <div class="relative overflow-hidden rounded-xl border border-zinc-700/50 bg-zinc-800/50 p-5 shadow-sm">
            <div class="absolute inset-y-0 left-0 w-1 rounded-l-xl bg-blue-500"></div>
            <div class="flex items-start justify-between">
                <div class="min-w-0 flex-1 pl-2">
                    <p class="text-[10px] font-semibold uppercase tracking-widest text-zinc-500">Revenue This Month</p>
                    <p class="mt-1 text-3xl font-bold tabular-nums text-zinc-100">
                        ₦{{ number_format($revenueThisMonth, 0) }}
                    </p>
                    <p class="mt-2 text-xs text-zinc-500">
                        <span class="font-medium text-zinc-300">{{ number_format($paidTransactionsThisMonth) }}</span>
                        paid {{ Str::plural('transaction', $paidTransactionsThisMonth) }}
                    </p>
                </div>
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-500/10">
                    <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>
            <a href="{{ route('admin.finance.transactions') }}" wire:navigate class="mt-3 inline-flex items-center gap-1 text-[11px] font-medium text-blue-400 hover:text-blue-300 pl-2">
                View transactions
                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
        @endcan

        {{-- Trade Card --}}
        @can('trade.view')
        <div class="relative overflow-hidden rounded-xl border border-zinc-700/50 bg-zinc-800/50 p-5 shadow-sm">
            <div class="absolute inset-y-0 left-0 w-1 rounded-l-xl bg-amber-500"></div>
            <div class="flex items-start justify-between">
                <div class="min-w-0 flex-1 pl-2">
                    <p class="text-[10px] font-semibold uppercase tracking-widest text-zinc-500">Trade Leads</p>
                    <p class="mt-1 text-3xl font-bold tabular-nums text-zinc-100">{{ number_format($activeTradeLeads) }}</p>
                    <p class="mt-2 text-xs text-zinc-500">
                        <span class="font-medium text-zinc-300">{{ number_format($dealsInPipeline) }}</span> {{ Str::plural('deal', $dealsInPipeline) }} in pipeline
                        @if($dealPipelineValue > 0)
                            &bull;
                            <span class="font-medium text-amber-400">
                                ${{ $dealPipelineValue >= 1000000 ? number_format($dealPipelineValue / 1000000, 1).'M' : number_format($dealPipelineValue / 1000, 0).'K' }}
                            </span>
                        @endif
                    </p>
                </div>
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-amber-500/10">
                    <svg class="h-5 w-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </div>
            </div>
            <a href="{{ route('admin.trade.leads') }}" wire:navigate class="mt-3 inline-flex items-center gap-1 text-[11px] font-medium text-amber-400 hover:text-amber-300 pl-2">
                View trade leads
                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
        @endcan

        {{-- Policy / NTBs Card --}}
        @can('policy.view')
        <div class="relative overflow-hidden rounded-xl border border-zinc-700/50 bg-zinc-800/50 p-5 shadow-sm">
            <div class="absolute inset-y-0 left-0 w-1 rounded-l-xl bg-rose-500"></div>
            <div class="flex items-start justify-between">
                <div class="min-w-0 flex-1 pl-2">
                    <p class="text-[10px] font-semibold uppercase tracking-widest text-zinc-500">Open NTBs</p>
                    <p class="mt-1 text-3xl font-bold tabular-nums text-zinc-100">{{ number_format($openNtbs) }}</p>
                    <p class="mt-2 text-xs text-zinc-500">
                        @if($escalatedNtbs > 0)
                            <span class="inline-flex items-center gap-1 rounded-full bg-rose-500/15 px-2 py-0.5 text-[10px] font-semibold text-rose-400">
                                {{ $escalatedNtbs }} escalated
                            </span>
                        @else
                            <span class="text-zinc-600">None escalated</span>
                        @endif
                    </p>
                </div>
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-rose-500/10">
                    <svg class="h-5 w-5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
            </div>
            <a href="{{ route('admin.policy.ntbs') }}" wire:navigate class="mt-3 inline-flex items-center gap-1 text-[11px] font-medium text-rose-400 hover:text-rose-300 pl-2">
                View NTBs
                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
        @endcan

        {{-- Events Card --}}
        @can('events.view')
        <div class="relative overflow-hidden rounded-xl border border-zinc-700/50 bg-zinc-800/50 p-5 shadow-sm">
            <div class="absolute inset-y-0 left-0 w-1 rounded-l-xl bg-violet-500"></div>
            <div class="flex items-start justify-between">
                <div class="min-w-0 flex-1 pl-2">
                    <p class="text-[10px] font-semibold uppercase tracking-widest text-zinc-500">Upcoming Events</p>
                    <p class="mt-1 text-3xl font-bold tabular-nums text-zinc-100">{{ number_format($upcomingEventsCount) }}</p>
                    <p class="mt-2 text-xs text-zinc-500">
                        <span class="font-medium text-zinc-300">{{ number_format($registrationsThisMonth) }}</span>
                        {{ Str::plural('registration', $registrationsThisMonth) }} this month
                    </p>
                </div>
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-violet-500/10">
                    <svg class="h-5 w-5 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            </div>
            <a href="{{ route('admin.events.index') }}" wire:navigate class="mt-3 inline-flex items-center gap-1 text-[11px] font-medium text-violet-400 hover:text-violet-300 pl-2">
                View events
                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
        @endcan

    </div>
    @endif

    {{-- ═══ MIDDLE SECTION: Applications + Events ═══ --}}
    @php
        $showApplications = auth()->user()->can('members.view');
        $showEvents       = auth()->user()->can('events.view');
    @endphp

    @if($showApplications || $showEvents)
    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">

        {{-- Pending Applications Table --}}
        @can('members.view')
        <div class="rounded-xl border border-zinc-700/50 bg-zinc-800/50 shadow-sm">
            <div class="flex items-center justify-between border-b border-zinc-700/50 px-5 py-4">
                <div class="flex items-center gap-2">
                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-green-500/10">
                        <svg class="h-4 w-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <h2 class="text-sm font-semibold text-zinc-200">Pending Applications</h2>
                    @if($pendingApplications > 0)
                        <span class="rounded-full bg-amber-500/20 px-2 py-0.5 text-[10px] font-bold text-amber-400">{{ $pendingApplications }}</span>
                    @endif
                </div>
                <a href="{{ route('admin.membership.applications') }}" wire:navigate class="text-[11px] font-medium text-green-500 hover:text-green-400">
                    View all →
                </a>
            </div>

            @if(count($recentApplications) > 0)
                <div class="divide-y divide-zinc-700/30">
                    @foreach($recentApplications as $app)
                    <div class="flex items-center gap-3 px-5 py-3.5">
                        {{-- Avatar initial --}}
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-zinc-700 text-xs font-bold text-zinc-300">
                            {{ strtoupper(substr($app->applicant_name, 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-medium text-zinc-200">{{ $app->applicant_name }}</p>
                            <p class="truncate text-xs text-zinc-500">{{ $app->organization ?: $app->email }}</p>
                        </div>
                        <div class="shrink-0 text-right">
                            @php
                                $statusColor = match($app->status) {
                                    'pending'      => 'bg-zinc-700 text-zinc-300',
                                    'under-review' => 'bg-blue-500/15 text-blue-400',
                                    default        => 'bg-zinc-700 text-zinc-400',
                                };
                            @endphp
                            <span class="rounded-full px-2 py-0.5 text-[10px] font-semibold {{ $statusColor }}">
                                {{ Str::title(str_replace('-', ' ', $app->status)) }}
                            </span>
                            @if($app->submitted_at)
                                <p class="mt-0.5 text-[10px] text-zinc-600">{{ $app->submitted_at->diffForHumans() }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-10 text-center">
                    <svg class="h-8 w-8 text-zinc-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <p class="mt-2 text-sm text-zinc-600">No pending applications</p>
                </div>
            @endif
        </div>
        @endcan

        {{-- Upcoming Events --}}
        @can('events.view')
        <div class="rounded-xl border border-zinc-700/50 bg-zinc-800/50 shadow-sm">
            <div class="flex items-center justify-between border-b border-zinc-700/50 px-5 py-4">
                <div class="flex items-center gap-2">
                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-violet-500/10">
                        <svg class="h-4 w-4 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <h2 class="text-sm font-semibold text-zinc-200">Upcoming Events</h2>
                    @if($upcomingEventsCount > 0)
                        <span class="rounded-full bg-violet-500/20 px-2 py-0.5 text-[10px] font-bold text-violet-400">{{ $upcomingEventsCount }}</span>
                    @endif
                </div>
                <a href="{{ route('admin.events.index') }}" wire:navigate class="text-[11px] font-medium text-violet-400 hover:text-violet-300">
                    View all →
                </a>
            </div>

            @if(count($upcomingEventsList) > 0)
                <div class="divide-y divide-zinc-700/30">
                    @foreach($upcomingEventsList as $event)
                    <div class="flex items-start gap-3 px-5 py-3.5">
                        {{-- Date block --}}
                        <div class="flex w-10 shrink-0 flex-col items-center rounded-lg border border-zinc-700 bg-zinc-900 py-1 text-center">
                            <span class="text-[10px] font-semibold uppercase text-violet-400">{{ $event->starts_at->format('M') }}</span>
                            <span class="text-base font-bold leading-none text-zinc-100">{{ $event->starts_at->format('d') }}</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-medium text-zinc-200">{{ $event->title }}</p>
                            <p class="mt-0.5 truncate text-xs text-zinc-500">
                                {{ $event->venue ?: '—' }}
                                &bull;
                                {{ $event->starts_at->format('H:i') }}
                            </p>
                        </div>
                        <span class="shrink-0 rounded-full bg-zinc-700/60 px-2 py-0.5 text-[10px] font-medium capitalize text-zinc-400">
                            {{ str_replace('_', ' ', $event->type) }}
                        </span>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-10 text-center">
                    <svg class="h-8 w-8 text-zinc-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <p class="mt-2 text-sm text-zinc-600">No upcoming events</p>
                </div>
            @endif
        </div>
        @endcan

    </div>
    @endif

    {{-- ═══ RECENT ACTIVITY FEED ═══ --}}
    @can('audit.view')
    <div class="rounded-xl border border-zinc-700/50 bg-zinc-800/50 shadow-sm">
        <div class="flex items-center justify-between border-b border-zinc-700/50 px-5 py-4">
            <div class="flex items-center gap-2">
                <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-zinc-700">
                    <svg class="h-4 w-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                </div>
                <h2 class="text-sm font-semibold text-zinc-200">Recent Activity</h2>
            </div>
            <a href="{{ route('admin.audit.index') }}" wire:navigate class="text-[11px] font-medium text-zinc-400 hover:text-zinc-300">
                Full audit log →
            </a>
        </div>

        @if(count($recentActivity) > 0)
            <div class="divide-y divide-zinc-700/30">
                @foreach($recentActivity as $activity)
                <div class="flex items-start gap-3 px-5 py-3">
                    {{-- Causer avatar --}}
                    <div class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-zinc-700 text-[10px] font-bold text-zinc-400">
                        {{ $activity->causer ? strtoupper(substr($activity->causer->name, 0, 1)) : '?' }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm">
                            <span class="font-medium text-zinc-200">{{ $activity->causer?->name ?? 'System' }}</span>
                            <span class="text-zinc-500"> {{ $activity->event ?? $activity->description }}</span>
                            @if($activity->subject_type)
                                <span class="font-medium text-zinc-400"> {{ class_basename($activity->subject_type) }}</span>
                                @if($activity->subject_id)<span class="text-zinc-600"> #{{ $activity->subject_id }}</span>@endif
                            @endif
                        </p>
                        <p class="mt-0.5 text-[11px] text-zinc-600">{{ $activity->created_at->diffForHumans() }}</p>
                    </div>
                    @php
                        $evtColor = match($activity->event) {
                            'created'  => 'bg-green-500/15 text-green-400',
                            'updated'  => 'bg-blue-500/15 text-blue-400',
                            'deleted'  => 'bg-rose-500/15 text-rose-400',
                            'restored' => 'bg-amber-500/15 text-amber-400',
                            default    => 'bg-zinc-700/50 text-zinc-500',
                        };
                    @endphp
                    <span class="shrink-0 rounded-full px-2 py-0.5 text-[10px] font-semibold capitalize {{ $evtColor }}">
                        {{ $activity->event ?? $activity->description }}
                    </span>
                </div>
                @endforeach
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-10 text-center">
                <svg class="h-8 w-8 text-zinc-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <p class="mt-2 text-sm text-zinc-600">No activity recorded yet</p>
            </div>
        @endif
    </div>
    @endcan

    {{-- ═══ QUICK ACTIONS (role-gated) ═══ --}}
    @php
        $quickActions = [];
        $user = auth()->user();
        if ($user->can('members.view'))    $quickActions[] = ['label' => 'New Application',  'icon' => 'clipboard', 'route' => 'admin.membership.applications', 'color' => 'green'];
        if ($user->can('trade.view'))      $quickActions[] = ['label' => 'New Trade Lead',    'icon' => 'trending',  'route' => 'admin.trade.leads',            'color' => 'amber'];
        if ($user->can('events.view'))     $quickActions[] = ['label' => 'New Event',         'icon' => 'calendar',  'route' => 'admin.events.index',           'color' => 'violet'];
        if ($user->can('finance.view'))    $quickActions[] = ['label' => 'View Transactions', 'icon' => 'banknote',  'route' => 'admin.finance.transactions',   'color' => 'blue'];
        if ($user->can('cms.view'))        $quickActions[] = ['label' => 'Manage Website',    'icon' => 'globe',     'route' => 'admin.cms.pages',              'color' => 'cyan'];
        if ($user->can('kpi.view'))        $quickActions[] = ['label' => 'KPI Dashboard',     'icon' => 'chart',     'route' => 'admin.kpi.dashboard',          'color' => 'emerald'];
    @endphp

    @if(count($quickActions) > 0)
    <div>
        <h2 class="mb-3 text-xs font-semibold uppercase tracking-widest text-zinc-600">Quick Access</h2>
        <div class="flex flex-wrap gap-2">
            @foreach($quickActions as $action)
            @php
                $btnColor = match($action['color']) {
                    'green'   => 'border-green-700/50 bg-green-900/20 text-green-300 hover:bg-green-900/40 hover:border-green-600',
                    'amber'   => 'border-amber-700/50 bg-amber-900/20 text-amber-300 hover:bg-amber-900/40 hover:border-amber-600',
                    'violet'  => 'border-violet-700/50 bg-violet-900/20 text-violet-300 hover:bg-violet-900/40 hover:border-violet-600',
                    'blue'    => 'border-blue-700/50 bg-blue-900/20 text-blue-300 hover:bg-blue-900/40 hover:border-blue-600',
                    'cyan'    => 'border-cyan-700/50 bg-cyan-900/20 text-cyan-300 hover:bg-cyan-900/40 hover:border-cyan-600',
                    'emerald' => 'border-emerald-700/50 bg-emerald-900/20 text-emerald-300 hover:bg-emerald-900/40 hover:border-emerald-600',
                    default   => 'border-zinc-700 bg-zinc-800 text-zinc-300 hover:bg-zinc-700',
                };
            @endphp
            <a href="{{ route($action['route']) }}" wire:navigate
               class="inline-flex items-center gap-2 rounded-lg border px-4 py-2 text-sm font-medium transition-colors duration-150 {{ $btnColor }}">
                @if($action['icon'] === 'clipboard')
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                @elseif($action['icon'] === 'trending')
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                @elseif($action['icon'] === 'calendar')
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                @elseif($action['icon'] === 'banknote')
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                @elseif($action['icon'] === 'globe')
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                @elseif($action['icon'] === 'chart')
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                @endif
                {{ $action['label'] }}
            </a>
            @endforeach
        </div>
    </div>
    @endif

</div>
