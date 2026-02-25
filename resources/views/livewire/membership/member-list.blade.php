<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Members</flux:heading>
            <flux:subheading>Active NiKCCIMA membership directory</flux:subheading>
        </div>
    </div>

    {{-- Filters --}}
    <div class="mb-4 flex flex-col gap-3 sm:flex-row">
        <flux:input wire:model.live="search" placeholder="Search name, number, email…" icon="magnifying-glass" class="sm:w-72" />
        <flux:select wire:model.live="statusFilter" class="sm:w-40">
            <option value="">All Statuses</option>
            <option value="active">Active</option>
            <option value="suspended">Suspended</option>
            <option value="expired">Expired</option>
        </flux:select>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto rounded-xl border border-zinc-200 dark:border-zinc-700">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Membership #</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Organisation</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Category</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Chapter</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Expires</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-900">
                @forelse($members as $member)
                    <tr>
                        <td class="px-4 py-3 font-mono text-sm text-zinc-700 dark:text-zinc-300">{{ $member->membership_number }}</td>
                        <td class="px-4 py-3 font-medium text-zinc-900 dark:text-white">{{ $member->full_name }}</td>
                        <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400 text-sm">{{ $member->organization ?? '—' }}</td>
                        <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400 text-sm">{{ $member->category?->name }}</td>
                        <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400 text-sm">{{ $member->chapter?->name }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium
                                {{ $member->status === 'active' ? 'bg-green-100 text-green-800' : ($member->status === 'suspended' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($member->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-zinc-500 text-sm">{{ $member->expires_at?->format('d M Y') ?? '—' }}</td>
                        <td class="px-4 py-3 text-right">
                            <flux:button size="sm" href="{{ route('admin.membership.members.show', $member) }}" wire:navigate>View</flux:button>
                            <flux:button size="sm" variant="ghost" href="{{ route('admin.membership.members.certificate', $member) }}" target="_blank">Certificate</flux:button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-zinc-500">No members found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $members->links() }}</div>
</div>
