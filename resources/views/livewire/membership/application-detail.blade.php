<div>
    <div class="mb-6">
        <flux:button icon="arrow-left" href="{{ route('admin.membership.applications') }}" wire:navigate variant="ghost" size="sm">Applications</flux:button>
        <flux:heading size="xl" class="mt-2">Application #{{ $application->id }}</flux:heading>
        <flux:subheading>{{ $application->applicant_name }} &bull; {{ $application->organization }}</flux:subheading>
    </div>

    @if(session('success'))
        <flux:callout variant="success" class="mb-4" dismissible>{{ session('success') }}</flux:callout>
    @endif

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- Main Info --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Applicant Details --}}
            <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <flux:heading size="lg" class="mb-4">Applicant Details</flux:heading>
                <dl class="grid grid-cols-2 gap-4">
                    <div><dt class="text-xs text-zinc-500">Name / Organisation</dt><dd class="font-medium">{{ $application->applicant_name }}</dd></div>
                    <div><dt class="text-xs text-zinc-500">Contact Person</dt><dd>{{ $application->contact_person ?? '—' }}</dd></div>
                    <div><dt class="text-xs text-zinc-500">Email</dt><dd>{{ $application->email }}</dd></div>
                    <div><dt class="text-xs text-zinc-500">Phone / WhatsApp</dt><dd>{{ $application->phone ?? '—' }}</dd></div>
                    <div><dt class="text-xs text-zinc-500">Organisation</dt><dd>{{ $application->organization ?? '—' }}</dd></div>
                    <div><dt class="text-xs text-zinc-500">Country</dt><dd>{{ $application->country ?? '—' }}</dd></div>
                    <div class="col-span-2"><dt class="text-xs text-zinc-500">Address</dt><dd>{{ $application->address ?? '—' }}</dd></div>
                    <div><dt class="text-xs text-zinc-500">Website / Social</dt><dd class="break-words">{{ $application->website ?? '—' }}</dd></div>
                    <div><dt class="text-xs text-zinc-500">Recommended / Sponsored by</dt><dd>{{ $application->sponsored_by ?? '—' }}</dd></div>
                    <div><dt class="text-xs text-zinc-500">Chapter</dt><dd>{{ $application->chapter?->name }}</dd></div>
                    <div><dt class="text-xs text-zinc-500">Category</dt><dd>{{ $application->category?->name }}</dd></div>
                </dl>
            </div>

            {{-- Purpose --}}
            <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <flux:heading size="lg" class="mb-3">Purpose of Membership</flux:heading>
                <p class="text-zinc-700 dark:text-zinc-300 text-sm leading-relaxed">{{ $application->purpose_of_membership }}</p>
            </div>

            {{-- Business Profile --}}
            @if($application->business_profile)
                <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                    <flux:heading size="lg" class="mb-4">Business Profile</flux:heading>
                    <dl class="grid grid-cols-2 gap-4">
                        @foreach($application->business_profile as $key => $val)
                            <div>
                                <dt class="text-xs text-zinc-500">{{ ucwords(str_replace('_', ' ', $key)) }}</dt>
                                <dd class="text-sm">{{ $val ?: '—' }}</dd>
                            </div>
                        @endforeach
                    </dl>
                </div>
            @endif

            {{-- Approval History --}}
            <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <flux:heading size="lg" class="mb-4">Approval History</flux:heading>
                @forelse($application->approvals as $approval)
                    <div class="mb-3 flex items-start gap-3 border-b border-zinc-100 pb-3 dark:border-zinc-800 last:border-0 last:pb-0">
                        <div class="h-8 w-8 rounded-full flex items-center justify-center {{ $approval->action === 'approved' ? 'bg-green-100' : 'bg-red-100' }}">
                            <flux:icon name="{{ $approval->action === 'approved' ? 'check' : 'x-mark' }}" class="h-4 w-4 {{ $approval->action === 'approved' ? 'text-green-700' : 'text-red-700' }}" />
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium">{{ $approval->approver->name }} — {{ ucwords(str_replace('-', ' ', $approval->stage)) }}</p>
                            <p class="text-xs text-zinc-500">{{ $approval->actioned_at->format('d M Y H:i') }}</p>
                            @if($approval->notes)
                                <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">{{ $approval->notes }}</p>
                            @endif
                        </div>
                        <span class="text-xs font-medium {{ $approval->action === 'approved' ? 'text-green-600' : 'text-red-600' }}">
                            {{ ucfirst($approval->action) }}
                        </span>
                    </div>
                @empty
                    <p class="text-sm text-zinc-500">No approvals recorded yet.</p>
                @endforelse
            </div>
        </div>

        {{-- Sidebar Actions --}}
        <div class="space-y-4">

            {{-- Status Card --}}
            <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
                <flux:heading size="sm" class="mb-3">Status</flux:heading>
                <span class="inline-flex rounded-full px-3 py-1 text-sm font-medium bg-zinc-100 text-zinc-800 dark:bg-zinc-700 dark:text-zinc-200">
                    {{ ucwords(str_replace('-', ' ', $application->status)) }}
                </span>
                @if($application->rejection_reason)
                    <p class="mt-3 text-sm text-red-600">Reason: {{ $application->rejection_reason }}</p>
                @endif
            </div>

            {{-- Approval Action --}}
            @can('members.approve')
                @if(!in_array($application->status, ['active', 'rejected']))
                    <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
                        <flux:heading size="sm" class="mb-3">Review Action</flux:heading>
                        <flux:field class="mb-3">
                            <flux:label>Notes (optional)</flux:label>
                            <flux:textarea wire:model="notes" rows="3" />
                        </flux:field>

                        @if($application->status === 'payment-pending')
                            <flux:button wire:click="activateMembership" variant="primary" class="w-full mb-2">
                                Mark Payment Received & Activate
                            </flux:button>
                        @else
                            <flux:button wire:click="approve" variant="primary" class="w-full mb-2">
                                Approve
                            </flux:button>
                        @endif
                        <flux:button wire:click="confirmReject" variant="danger" class="w-full">Reject</flux:button>
                    </div>
                @endif
            @endcan
        </div>
    </div>

    {{-- Reject Modal --}}
    <flux:modal wire:model="showRejectModal" name="reject-modal">
        <flux:heading>Reject Application</flux:heading>
        <flux:subheading>Provide a reason for rejecting this application.</flux:subheading>
        <flux:field class="mt-4">
            <flux:label>Rejection Reason</flux:label>
            <flux:textarea wire:model="rejectionReason" rows="4" placeholder="State the reason clearly..." />
            <flux:error name="rejectionReason" />
        </flux:field>
        <div class="mt-4 flex gap-3">
            <flux:button wire:click="reject" variant="danger">Confirm Reject</flux:button>
            <flux:button wire:click="$set('showRejectModal', false)" variant="ghost">Cancel</flux:button>
        </div>
    </flux:modal>
</div>
