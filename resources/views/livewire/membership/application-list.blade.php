<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Membership Applications</flux:heading>
            <flux:subheading>Review and process incoming applications</flux:subheading>
        </div>
        @can('members.create')
            <flux:button icon="plus" href="{{ route('admin.membership.apply') }}" wire:navigate>New Application</flux:button>
        @endcan
    </div>

    {{-- Filters --}}
    <div class="mb-4 flex flex-col gap-3 sm:flex-row">
        <flux:input wire:model.live="search" placeholder="Search name, email, org…" icon="magnifying-glass" class="sm:w-72" />
        <flux:select wire:model.live="statusFilter" class="sm:w-48">
            <option value="">All Statuses</option>
            <option value="pending">Pending</option>
            <option value="under-review">Under Review</option>
            <option value="chairman-approved">Chairman Approved</option>
            <option value="dg-approved">DG Approved</option>
            <option value="payment-pending">Payment Pending</option>
            <option value="active">Active</option>
            <option value="rejected">Rejected</option>
        </flux:select>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto rounded-xl border border-zinc-200 dark:border-zinc-700">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">#</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Applicant</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Organisation</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Category</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Chapter</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Submitted</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-900">
                @forelse($applications as $app)
                    @php
                        $statusColor = match($app->status) {
                            'pending','under-review'  => 'bg-yellow-100 text-yellow-800',
                            'chairman-approved','dg-approved' => 'bg-blue-100 text-blue-800',
                            'payment-pending'         => 'bg-orange-100 text-orange-800',
                            'active'                  => 'bg-green-100 text-green-800',
                            'rejected'                => 'bg-red-100 text-red-800',
                            default                   => 'bg-zinc-100 text-zinc-600',
                        };
                    @endphp
                    <tr>
                        <td class="px-4 py-3 text-zinc-500 text-sm">#{{ $app->id }}</td>
                        <td class="px-4 py-3 font-medium text-zinc-900 dark:text-white">{{ $app->applicant_name }}</td>
                        <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400 text-sm">{{ $app->organization }}</td>
                        <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400 text-sm">{{ $app->category?->name }}</td>
                        <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400 text-sm">{{ $app->chapter?->name }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium {{ $statusColor }}">
                                {{ ucwords(str_replace('-', ' ', $app->status)) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-zinc-500 text-sm">{{ $app->submitted_at?->format('d M Y') ?? '—' }}</td>
                        <td class="px-4 py-3 text-right">
                            <flux:button size="sm" href="{{ route('admin.membership.applications.show', $app) }}" wire:navigate>Review</flux:button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-zinc-500">No applications found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $applications->links() }}</div>
</div>
