<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Corridor Tracker</flux:heading>
            <flux:subheading>Monitor AfCFTA trade corridor performance and activity</flux:subheading>
        </div>
    </div>

    {{-- Corridors Table --}}
    <div class="overflow-x-auto rounded-xl border border-zinc-200 dark:border-zinc-700">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Corridor</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Firms Onboarded</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Transaction Value</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Lead</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-900">
                @forelse($corridors as $corridor)
                    <tr>
                        <td class="px-4 py-3 font-medium text-zinc-900 dark:text-white">{{ $corridor->name }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium
                                {{ $corridor->status === 'active' ? 'bg-green-100 text-green-800' : ($corridor->status === 'pilot' ? 'bg-yellow-100 text-yellow-800' : 'bg-zinc-100 text-zinc-700') }}">
                                {{ ucfirst($corridor->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">{{ number_format($corridor->firms_onboarded) }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">${{ number_format($corridor->transaction_value_usd, 2) }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">{{ $corridor->lead?->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-right">
                            <flux:button size="sm" variant="ghost" wire:click="selectCorridor({{ $corridor->id }})">
                                {{ $selectedCorridorId === $corridor->id ? 'Collapse' : 'Details' }}
                            </flux:button>
                        </td>
                    </tr>

                    {{-- Expanded corridor detail --}}
                    @if($selectedCorridorId === $corridor->id && $selectedCorridor)
                        <tr>
                            <td colspan="6" class="bg-zinc-50 dark:bg-zinc-800 px-4 py-4">
                                <div class="space-y-4">
                                    <flux:heading size="sm">Activity Log — {{ $selectedCorridor->name }}</flux:heading>

                                    @if($selectedCorridor->logs->isEmpty())
                                        <p class="text-sm text-zinc-500">No logs yet.</p>
                                    @else
                                        <ul class="space-y-2">
                                            @foreach($selectedCorridor->logs->sortByDesc('logged_at') as $log)
                                                <li class="flex gap-3 text-sm">
                                                    <span class="text-zinc-400 w-36 flex-shrink-0">{{ $log->logged_at?->format('d M Y H:i') }}</span>
                                                    <span class="text-zinc-600 dark:text-zinc-300">{{ $log->note }}</span>
                                                    <span class="text-zinc-400 text-xs ml-auto">— {{ $log->user?->name }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif

                                    @can('corridors.edit')
                                        <div class="flex gap-3 items-end pt-2 border-t border-zinc-200 dark:border-zinc-700">
                                            <flux:input wire:model="newNote" placeholder="Add a log note…" class="flex-1" />
                                            <flux:button wire:click="addLog" variant="primary" size="sm">Add Log</flux:button>
                                        </div>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-zinc-500">No corridors found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $corridors->links() }}</div>
</div>
