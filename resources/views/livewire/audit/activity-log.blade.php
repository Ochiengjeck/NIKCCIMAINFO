<div class="space-y-6 p-6">

    {{-- ── Header ── --}}
    <div>
        <h1 class="text-2xl font-bold text-zinc-100" style="font-family:'Playfair Display',serif">Audit Log</h1>
        <p class="mt-1 text-sm text-zinc-500">Complete immutable trail of all system activities and changes</p>
    </div>

    {{-- ── Filters ── --}}
    <div class="flex flex-wrap gap-3">
        <flux:input
            wire:model.live.debounce.400ms="search"
            placeholder="Search description…"
            icon="magnifying-glass"
            class="w-full sm:w-64"
        />

        <flux:input
            wire:model.live.debounce.400ms="subjectFilter"
            placeholder="Model (e.g. Member)"
            class="w-full sm:w-44"
        />

        <select
            wire:model.live="eventFilter"
            class="rounded-lg border border-zinc-700 bg-zinc-800 px-3 py-2 text-sm text-zinc-300 focus:border-zinc-500 focus:outline-none focus:ring-1 focus:ring-zinc-500"
        >
            <option value="">All events</option>
            <option value="created">Created</option>
            <option value="updated">Updated</option>
            <option value="deleted">Deleted</option>
            <option value="restored">Restored</option>
        </select>

        <flux:input wire:model.live="dateFrom" type="date" class="w-full sm:w-40" />
        <flux:input wire:model.live="dateTo"   type="date" class="w-full sm:w-40" />

        @if($search || $subjectFilter || $eventFilter || $dateFrom || $dateTo)
            <button
                wire:click="$set('search', ''); $set('subjectFilter', ''); $set('eventFilter', ''); $set('dateFrom', ''); $set('dateTo', '');"
                class="inline-flex items-center gap-1.5 rounded-lg border border-zinc-700 px-3 py-2 text-xs text-zinc-400 hover:border-zinc-600 hover:text-zinc-300"
            >
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                Clear filters
            </button>
        @endif
    </div>

    {{-- ── Table ── --}}
    <div class="overflow-x-auto rounded-xl border border-zinc-700/50 bg-zinc-800/30">
        <table class="min-w-full divide-y divide-zinc-700/50">
            <thead>
                <tr class="bg-zinc-800/60">
                    <th class="px-4 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-zinc-500">Event</th>
                    <th class="px-4 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-zinc-500">User</th>
                    <th class="px-4 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-zinc-500">Record</th>
                    <th class="px-4 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-zinc-500">Changes</th>
                    <th class="px-4 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-zinc-500">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-700/30 bg-zinc-900/60">
                @forelse($activities as $activity)
                    @php
                        $eventColor = match($activity->event) {
                            'created'  => 'bg-green-500/15 text-green-400 ring-green-500/20',
                            'updated'  => 'bg-blue-500/15 text-blue-400 ring-blue-500/20',
                            'deleted'  => 'bg-rose-500/15 text-rose-400 ring-rose-500/20',
                            'restored' => 'bg-amber-500/15 text-amber-400 ring-amber-500/20',
                            default    => 'bg-zinc-700/60 text-zinc-400 ring-zinc-600/20',
                        };
                        $newVals = collect($activity->properties->get('attributes', []));
                        $oldVals = collect($activity->properties->get('old', []));
                        $fields  = $newVals->keys()->take(3);
                    @endphp
                    <tr class="group transition-colors hover:bg-zinc-800/40">

                        {{-- Event badge + description --}}
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-semibold capitalize ring-1 {{ $eventColor }}">
                                {{ $activity->event ?? $activity->description }}
                            </span>
                            @if($activity->description && $activity->description !== $activity->event)
                                <p class="mt-0.5 text-xs text-zinc-600">{{ $activity->description }}</p>
                            @endif
                        </td>

                        {{-- User --}}
                        <td class="px-4 py-3">
                            @if($activity->causer)
                                <p class="text-sm font-medium text-zinc-300">{{ $activity->causer->name }}</p>
                                <p class="text-xs text-zinc-600">{{ $activity->causer->email }}</p>
                            @else
                                <span class="text-sm text-zinc-600">System</span>
                            @endif
                        </td>

                        {{-- Record = model + ID --}}
                        <td class="px-4 py-3">
                            @if($activity->subject_type)
                                <p class="text-sm font-medium text-zinc-300">{{ class_basename($activity->subject_type) }}</p>
                                @if($activity->subject_id)
                                    <p class="font-mono text-xs text-zinc-600">#{{ $activity->subject_id }}</p>
                                @endif
                            @else
                                <span class="text-zinc-700">—</span>
                            @endif
                        </td>

                        {{-- Changes: old → new --}}
                        <td class="px-4 py-3">
                            @if($fields->isNotEmpty())
                                <div class="space-y-1">
                                    @foreach($fields as $field)
                                        <div class="flex flex-wrap items-center gap-1 text-[11px]">
                                            <span class="text-zinc-600">{{ $field }}:</span>
                                            @if($oldVals->has($field))
                                                <span class="font-mono text-rose-400/70 line-through">{{ Str::limit((string) $oldVals->get($field, '∅'), 22) }}</span>
                                                <span class="text-zinc-700">→</span>
                                            @endif
                                            <span class="font-mono text-green-400/80">{{ Str::limit((string) $newVals->get($field, '∅'), 22) }}</span>
                                        </div>
                                    @endforeach
                                    @if($newVals->count() > 3)
                                        <p class="text-[10px] text-zinc-700">+{{ $newVals->count() - 3 }} more fields</p>
                                    @endif
                                </div>
                            @else
                                <span class="text-zinc-700">—</span>
                            @endif
                        </td>

                        {{-- Date --}}
                        <td class="px-4 py-3 text-right">
                            <p class="text-sm text-zinc-400">{{ $activity->created_at->format('d M Y') }}</p>
                            <p class="text-xs text-zinc-600">{{ $activity->created_at->format('H:i') }} &bull; {{ $activity->created_at->diffForHumans() }}</p>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-16 text-center">
                            <svg class="mx-auto h-10 w-10 text-zinc-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            <p class="mt-3 text-sm text-zinc-600">No activity records found.</p>
                            @if($search || $subjectFilter || $eventFilter || $dateFrom || $dateTo)
                                <p class="mt-1 text-xs text-zinc-700">Try clearing your filters.</p>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="flex items-center justify-between text-sm text-zinc-500">
        <p>Showing {{ $activities->firstItem() ?? 0 }}–{{ $activities->lastItem() ?? 0 }} of {{ $activities->total() }} {{ Str::plural('entry', $activities->total()) }}</p>
        <div>{{ $activities->links() }}</div>
    </div>

</div>
