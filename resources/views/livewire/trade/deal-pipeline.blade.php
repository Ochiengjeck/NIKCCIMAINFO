<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Deal Pipeline</flux:heading>
            <flux:subheading>Track trade deals from prospect to completion</flux:subheading>
        </div>
    </div>

    {{-- Stage filter tabs --}}
    <div class="mb-4 flex flex-wrap gap-2">
        @foreach(['', 'prospect', 'negotiation', 'agreed', 'completed', 'failed'] as $stage)
            <flux:button
                wire:click="setStage('{{ $stage }}')"
                size="sm"
                variant="{{ $selectedStage === $stage ? 'primary' : 'ghost' }}"
            >
                {{ $stage === '' ? 'All Stages' : ucfirst($stage) }}
            </flux:button>
        @endforeach
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto rounded-xl border border-zinc-200 dark:border-zinc-700">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Title</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Sector</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Stage</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Value (USD)</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Initiator</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Closed</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-900">
                @forelse($deals as $deal)
                    <tr>
                        <td class="px-4 py-3 font-medium text-zinc-900 dark:text-white">{{ $deal->title }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">{{ $deal->sector?->name ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium
                                @switch($deal->stage)
                                    @case('prospect') bg-zinc-100 text-zinc-700 @break
                                    @case('negotiation') bg-blue-100 text-blue-800 @break
                                    @case('agreed') bg-yellow-100 text-yellow-800 @break
                                    @case('completed') bg-green-100 text-green-800 @break
                                    @case('failed') bg-red-100 text-red-800 @break
                                @endswitch
                            ">
                                {{ ucfirst($deal->stage) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">
                            {{ $deal->value_usd ? '$'.number_format($deal->value_usd, 2) : '—' }}
                        </td>
                        <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">{{ $deal->initiator?->full_name ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-500">{{ $deal->closed_at?->format('d M Y') ?? '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-zinc-500">No deals found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $deals->links() }}</div>
</div>
