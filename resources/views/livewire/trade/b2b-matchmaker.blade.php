<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">B2B Matchmaking</flux:heading>
            <flux:subheading>Member-to-member meeting requests and connections</flux:subheading>
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto rounded-xl border border-zinc-200 dark:border-zinc-700">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Requester</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Target</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Message</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Proposed Date</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-900">
                @forelse($requests as $request)
                    <tr>
                        <td class="px-4 py-3 font-medium text-zinc-900 dark:text-white">
                            {{ $request->requester?->full_name ?? '—' }}
                        </td>
                        <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">
                            {{ $request->target?->full_name ?? '—' }}
                        </td>
                        <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400 max-w-xs truncate">
                            {{ Str::limit($request->message, 80) }}
                        </td>
                        <td class="px-4 py-3 text-sm text-zinc-500">
                            {{ $request->proposed_date?->format('d M Y') ?? '—' }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium
                                @switch($request->status)
                                    @case('pending') bg-yellow-100 text-yellow-800 @break
                                    @case('accepted') bg-green-100 text-green-800 @break
                                    @case('declined') bg-red-100 text-red-800 @break
                                    @case('completed') bg-blue-100 text-blue-800 @break
                                @endswitch
                            ">
                                {{ ucfirst($request->status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-zinc-500">No B2B meeting requests found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $requests->links() }}</div>
</div>
