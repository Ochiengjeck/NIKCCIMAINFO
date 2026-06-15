<div>
    {{-- ===================== PAGE HEADER ===================== --}}
    <div class="mb-6 flex items-start gap-3">
        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-crimson-50 text-crimson-600 dark:bg-crimson-500/10 dark:text-crimson-400">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
        </div>
        <div>
            <flux:heading size="xl">Registration Desk</flux:heading>
            <flux:subheading>Event check-in and attendance management</flux:subheading>
        </div>
    </div>

    @if(session('success'))<flux:callout variant="success" class="mb-4" dismissible>{{ session('success') }}</flux:callout>@endif

    {{-- ===================== TOOLBAR ===================== --}}
    <div class="mb-5 rounded-2xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <flux:field class="sm:w-96">
                <flux:label>Event</flux:label>
                <flux:select wire:model.live="selectedEventId">
                    <option value="">Select event…</option>
                    @foreach($events as $e)<option value="{{ $e->id }}">{{ $e->title }} ({{ $e->starts_at->format('d M Y') }})</option>@endforeach
                </flux:select>
            </flux:field>
            @if($selectedEventId)
                <flux:field class="sm:w-72">
                    <flux:label>Search</flux:label>
                    <flux:input wire:model.live="search" placeholder="Search name or ticket…" icon="magnifying-glass" />
                </flux:field>
            @endif
        </div>
    </div>

    @php $selectedEvent = $selectedEventId ? $events->firstWhere('id', (int) $selectedEventId) : null; @endphp

    @if($selectedEventId && $registrations->count())
        {{-- ---- Context / stats bar ---- --}}
        <div class="mb-4 flex flex-col gap-3 rounded-2xl border border-zinc-200 bg-white px-5 py-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-semibold text-zinc-900 dark:text-white">{{ $selectedEvent?->title ?? 'Selected event' }}</p>
                @if($selectedEvent)
                    <p class="text-xs text-zinc-500">{{ $selectedEvent->starts_at->format('d M Y, H:i') }}@if($selectedEvent->venue) · {{ $selectedEvent->venue }}@endif</p>
                @endif
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-3 py-1 text-sm font-medium text-blue-700 dark:bg-blue-500/10 dark:text-blue-300">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    {{ $registrations->total() }} {{ \Illuminate\Support\Str::plural('registrant', $registrations->total()) }}
                </span>
            </div>
        </div>

        {{-- ---- Registrations table ---- --}}
        <div class="overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                    <thead class="bg-zinc-50 dark:bg-zinc-800/60">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">Ticket #</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">Participant</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">Organisation</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">WhatsApp</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">Post-OOC11 2026</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">Ticket Type</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">Status</th>
                            <th class="px-5 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 bg-white dark:divide-zinc-800 dark:bg-zinc-900">
                        @foreach($registrations as $reg)
                        <tr class="transition hover:bg-zinc-50/70 dark:hover:bg-zinc-800/40">
                            <td class="px-5 py-3.5 font-mono text-sm text-zinc-500">{{ $reg->ticket_number }}</td>
                            <td class="px-5 py-3.5">
                                <span class="block font-medium text-zinc-900 dark:text-white">{{ $reg->attendee_name }}</span>
                                <span class="block text-xs text-zinc-400">{{ $reg->attendee_email }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-sm text-zinc-500">
                                <span class="block">{{ $reg->organisation ?: '—' }}</span>
                                @if($reg->designation)<span class="block text-xs text-zinc-400">{{ $reg->designation }}</span>@endif
                            </td>
                            <td class="px-5 py-3.5 text-sm text-zinc-500">{{ $reg->whatsapp_number ?: '—' }}</td>
                            <td class="px-5 py-3.5 text-sm">
                                @if($reg->ooc11_engagement)<span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium bg-green-100 text-green-800">Yes</span>
                                @else<span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium bg-zinc-100 text-zinc-600">No</span>@endif
                            </td>
                            <td class="px-5 py-3.5 text-sm text-zinc-500">{{ $reg->ticket?->name ?? 'General' }}</td>
                            <td class="px-5 py-3.5">
                                @if($reg->checked_in_at)<span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-medium bg-green-100 text-green-800"><span class="h-1.5 w-1.5 rounded-full bg-green-500"></span> Checked In {{ $reg->checked_in_at->format('H:i') }}</span>
                                @else<span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-medium bg-yellow-100 text-yellow-800"><span class="h-1.5 w-1.5 rounded-full bg-yellow-500"></span> Not Checked In</span>@endif
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                @unless($reg->checked_in_at)<flux:button size="sm" variant="primary" wire:click="checkIn({{ $reg->id }})">Check In</flux:button>@endunless
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-4">{{ $registrations->links() }}</div>
    @elseif($selectedEventId)
        {{-- ---- Empty: event chosen, no registrations ---- --}}
        <div class="rounded-2xl border border-dashed border-zinc-200 bg-white py-16 text-center dark:border-zinc-700 dark:bg-zinc-900">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-zinc-100 dark:bg-zinc-800">
                <svg class="h-6 w-6 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <p class="mt-3 text-sm font-medium text-zinc-700 dark:text-zinc-300">No registrations found</p>
            <p class="mt-1 text-sm text-zinc-500">@if($search)No one matches “{{ $search }}”.@else No one has registered for this event yet.@endif</p>
        </div>
    @else
        {{-- ---- Empty: no event chosen ---- --}}
        <div class="rounded-2xl border border-dashed border-zinc-200 bg-white py-16 text-center dark:border-zinc-700 dark:bg-zinc-900">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-zinc-100 dark:bg-zinc-800">
                <svg class="h-6 w-6 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <p class="mt-3 text-sm font-medium text-zinc-700 dark:text-zinc-300">Select an event to begin</p>
            <p class="mt-1 text-sm text-zinc-500">Choose an event above to view and check in its registrants.</p>
        </div>
    @endif
</div>
