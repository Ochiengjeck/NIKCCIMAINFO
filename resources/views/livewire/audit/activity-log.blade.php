<div>
    <div class="mb-6">
        <flux:heading size="xl">Audit Log</flux:heading>
        <flux:subheading>Complete trail of all system activities and changes</flux:subheading>
    </div>

    {{-- Filters --}}
    <div class="mb-4 flex flex-col gap-3 sm:flex-row">
        <flux:input wire:model.live="search" placeholder="Search activity description…" icon="magnifying-glass" class="sm:w-72" />
        <flux:input wire:model.live="subjectFilter" placeholder="Filter by model (e.g. Member)" class="sm:w-48" />
        <flux:input wire:model.live="dateFrom" type="date" class="sm:w-40" />
        <flux:input wire:model.live="dateTo" type="date" class="sm:w-40" />
    </div>

    <div class="overflow-x-auto rounded-xl border border-zinc-200 dark:border-zinc-700">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Description</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">User</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Model</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Record ID</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Changes</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-900">
                @forelse($activities as $activity)
                    <tr>
                        <td class="px-4 py-3 text-sm font-medium text-zinc-900 dark:text-white">{{ $activity->description }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">{{ $activity->causer?->name ?? 'System' }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-500">
                            @if($activity->subject_type)
                                {{ class_basename($activity->subject_type) }}
                            @else
                                —
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-zinc-500">{{ $activity->subject_id ?? '—' }}</td>
                        <td class="px-4 py-3 text-xs text-zinc-400 max-w-xs">
                            @if($activity->properties && $activity->properties->get('attributes'))
                                @php $attrs = $activity->properties->get('attributes', []); @endphp
                                <span class="font-mono">{{ Str::limit(collect($attrs)->keys()->join(', '), 60) }}</span>
                            @else
                                —
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-zinc-500">{{ $activity->created_at->format('d M Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-zinc-500">No activity records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $activities->links() }}</div>
</div>
