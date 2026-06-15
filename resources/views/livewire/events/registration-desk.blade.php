<div>
    <flux:heading size="xl" class="mb-1">Registration Desk</flux:heading>
    <flux:subheading class="mb-6">Event check-in and attendance management</flux:subheading>
    @if(session('success'))<flux:callout variant="success" class="mb-4" dismissible>{{ session('success') }}</flux:callout>@endif
    <div class="mb-4 flex gap-3">
        <flux:select wire:model.live="selectedEventId" class="w-80">
            <option value="">Select event…</option>
            @foreach($events as $e)<option value="{{ $e->id }}">{{ $e->title }} ({{ $e->starts_at->format('d M Y') }})</option>@endforeach
        </flux:select>
        @if($selectedEventId)<flux:input wire:model.live="search" placeholder="Search name or ticket…" icon="magnifying-glass" class="w-64" />@endif
    </div>
    @if($selectedEventId && $registrations->count())
    <div class="overflow-x-auto rounded-xl border border-zinc-200 dark:border-zinc-700">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Ticket #</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Email</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Organisation</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Designation</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">WhatsApp</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Post-OOC11 2026</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Ticket Type</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-900">
                @foreach($registrations as $reg)
                <tr>
                    <td class="px-4 py-3 font-mono text-sm">{{ $reg->ticket_number }}</td>
                    <td class="px-4 py-3 font-medium">{{ $reg->attendee_name }}</td>
                    <td class="px-4 py-3 text-sm text-zinc-500">{{ $reg->attendee_email }}</td>
                    <td class="px-4 py-3 text-sm text-zinc-500">{{ $reg->organisation ?: '—' }}</td>
                    <td class="px-4 py-3 text-sm text-zinc-500">{{ $reg->designation ?: '—' }}</td>
                    <td class="px-4 py-3 text-sm text-zinc-500">{{ $reg->whatsapp_number ?: '—' }}</td>
                    <td class="px-4 py-3 text-sm">
                        @if($reg->ooc11_engagement)<span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium bg-green-100 text-green-800">Yes</span>
                        @else<span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium bg-zinc-100 text-zinc-600">No</span>@endif
                    </td>
                    <td class="px-4 py-3 text-sm">{{ $reg->ticket?->name ?? 'General' }}</td>
                    <td class="px-4 py-3">
                        @if($reg->checked_in_at)<span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium bg-green-100 text-green-800">Checked In {{ $reg->checked_in_at->format('H:i') }}</span>
                        @else<span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium bg-yellow-100 text-yellow-800">Not Checked In</span>@endif
                    </td>
                    <td class="px-4 py-3 text-right">
                        @unless($reg->checked_in_at)<flux:button size="sm" variant="primary" wire:click="checkIn({{ $reg->id }})">Check In</flux:button>@endunless
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $registrations->links() }}</div>
    @elseif($selectedEventId)
    <p class="text-center text-zinc-500 py-8">No registrations found.</p>
    @endif
</div>
