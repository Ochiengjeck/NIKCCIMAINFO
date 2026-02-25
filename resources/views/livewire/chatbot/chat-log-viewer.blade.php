<div>
    <div class="mb-6">
        <flux:heading size="xl">Chat Logs</flux:heading>
        <flux:subheading>Review chatbot conversation history and escalations</flux:subheading>
    </div>

    <div class="overflow-x-auto rounded-xl border border-zinc-200 dark:border-zinc-700">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Session ID</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Visitor IP</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Messages</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Escalated To</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Date</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-900">
                @forelse($logs as $log)
                    <tr>
                        <td class="px-4 py-3 font-mono text-xs text-zinc-600 dark:text-zinc-400">{{ Str::limit($log->session_id, 16) }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">{{ $log->visitor_ip ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">{{ count($log->messages ?? []) }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">{{ $log->escalatedTo?->name ?? '—' }}</td>
                        <td class="px-4 py-3">
                            @if($log->resolved_at)
                                <span class="inline-flex rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800">Resolved</span>
                            @elseif($log->escalated_at)
                                <span class="inline-flex rounded-full bg-yellow-100 px-2 py-0.5 text-xs font-medium text-yellow-800">Escalated</span>
                            @else
                                <span class="inline-flex rounded-full bg-zinc-100 px-2 py-0.5 text-xs font-medium text-zinc-600">Open</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-zinc-500">{{ $log->created_at->format('d M Y H:i') }}</td>
                        <td class="px-4 py-3 text-right">
                            <flux:button size="sm" variant="ghost" wire:click="expand({{ $log->id }})">
                                {{ $expandedId === $log->id ? 'Collapse' : 'View' }}
                            </flux:button>
                        </td>
                    </tr>
                    @if($expandedId === $log->id)
                        <tr class="bg-zinc-50 dark:bg-zinc-800">
                            <td colspan="7" class="px-4 py-4">
                                <div class="space-y-2">
                                    @foreach($log->messages ?? [] as $msg)
                                        <div class="rounded-lg p-3 {{ ($msg['role'] ?? 'user') === 'bot' ? 'bg-blue-50 dark:bg-blue-900/30' : 'bg-white dark:bg-zinc-700' }}">
                                            <span class="text-xs font-semibold uppercase text-zinc-500">{{ $msg['role'] ?? 'user' }}</span>
                                            <p class="mt-1 text-sm text-zinc-800 dark:text-zinc-200">{{ $msg['content'] ?? '' }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-zinc-500">No chat logs found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $logs->links() }}</div>
</div>
