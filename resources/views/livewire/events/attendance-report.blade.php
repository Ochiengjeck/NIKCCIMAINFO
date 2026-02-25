<div>
    <flux:heading size="xl" class="mb-1">Attendance Reports</flux:heading>
    <flux:subheading class="mb-6">Registration and check-in statistics per event</flux:subheading>
    <div class="overflow-x-auto rounded-xl border border-zinc-200 dark:border-zinc-700">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Event</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Date</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Capacity</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Registered</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Checked In</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Attendance %</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-900">
                @forelse($events as $event)
                <tr>
                    <td class="px-4 py-3 font-medium text-zinc-900 dark:text-white">{{ $event->title }}</td>
                    <td class="px-4 py-3 text-sm text-zinc-500">{{ $event->starts_at->format('d M Y') }}</td>
                    <td class="px-4 py-3 text-sm">{{ $event->max_capacity ?? '∞' }}</td>
                    <td class="px-4 py-3 text-sm font-medium">{{ $event->registrations_count }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-green-700">{{ $event->checked_in_count }}</td>
                    <td class="px-4 py-3 text-sm">{{ $event->registrations_count > 0 ? round($event->checked_in_count / $event->registrations_count * 100) : 0 }}%</td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-zinc-500">No events found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $events->links() }}</div>
</div>
