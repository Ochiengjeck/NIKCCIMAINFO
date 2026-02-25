<div>
    <div class="mb-6">
        <flux:button icon="arrow-left" href="{{ route('admin.membership.members') }}" wire:navigate variant="ghost" size="sm">Members</flux:button>
        <flux:heading size="xl" class="mt-2">{{ $member->full_name }}</flux:heading>
        <flux:subheading>{{ $member->membership_number }} &bull; {{ $member->chapter?->name }} Chapter</flux:subheading>
    </div>

    @if(session('success'))
        <flux:callout variant="success" class="mb-4" dismissible>{{ session('success') }}</flux:callout>
    @endif

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <div class="flex items-center justify-between mb-6">
                    <flux:heading size="lg">Member Details</flux:heading>
                    @if(!$editing)
                        @can('members.edit')
                            <flux:button wire:click="edit" size="sm">Edit</flux:button>
                        @endcan
                    @endif
                </div>

                @if($editing)
                    <form wire:submit="save" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <flux:field>
                            <flux:label>First Name</flux:label>
                            <flux:input wire:model="first_name" />
                            <flux:error name="first_name" />
                        </flux:field>
                        <flux:field>
                            <flux:label>Last Name</flux:label>
                            <flux:input wire:model="last_name" />
                            <flux:error name="last_name" />
                        </flux:field>
                        <flux:field>
                            <flux:label>Email</flux:label>
                            <flux:input type="email" wire:model="email" />
                            <flux:error name="email" />
                        </flux:field>
                        <flux:field>
                            <flux:label>Phone</flux:label>
                            <flux:input wire:model="phone" />
                        </flux:field>
                        <flux:field class="sm:col-span-2">
                            <flux:label>Organisation</flux:label>
                            <flux:input wire:model="organization" />
                        </flux:field>
                        <flux:field>
                            <flux:label>Status</flux:label>
                            <flux:select wire:model="status">
                                <option value="active">Active</option>
                                <option value="suspended">Suspended</option>
                                <option value="expired">Expired</option>
                            </flux:select>
                        </flux:field>
                        <div class="sm:col-span-2 flex gap-3">
                            <flux:button type="submit" variant="primary">Save Changes</flux:button>
                            <flux:button wire:click="cancel" variant="ghost">Cancel</flux:button>
                        </div>
                    </form>
                @else
                    <dl class="grid grid-cols-2 gap-4">
                        <div><dt class="text-xs text-zinc-500">First Name</dt><dd class="font-medium">{{ $member->first_name }}</dd></div>
                        <div><dt class="text-xs text-zinc-500">Last Name</dt><dd class="font-medium">{{ $member->last_name }}</dd></div>
                        <div><dt class="text-xs text-zinc-500">Email</dt><dd>{{ $member->email }}</dd></div>
                        <div><dt class="text-xs text-zinc-500">Phone</dt><dd>{{ $member->phone ?? '—' }}</dd></div>
                        <div class="col-span-2"><dt class="text-xs text-zinc-500">Organisation</dt><dd>{{ $member->organization ?? '—' }}</dd></div>
                        <div><dt class="text-xs text-zinc-500">Category</dt><dd>{{ $member->category?->name }}</dd></div>
                        <div><dt class="text-xs text-zinc-500">Export Readiness</dt><dd>{{ $member->export_readiness_score }}/100</dd></div>
                        <div><dt class="text-xs text-zinc-500">Joined</dt><dd>{{ $member->joined_at->format('d M Y') }}</dd></div>
                        <div><dt class="text-xs text-zinc-500">Expires</dt><dd>{{ $member->expires_at?->format('d M Y') ?? '—' }}</dd></div>
                    </dl>
                @endif
            </div>
        </div>

        <div class="space-y-4">
            <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
                <flux:heading size="sm" class="mb-3">Status</flux:heading>
                <span class="inline-flex rounded-full px-3 py-1 text-sm font-medium
                    {{ $member->status === 'active' ? 'bg-green-100 text-green-800' : ($member->status === 'suspended' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                    {{ ucfirst($member->status) }}
                </span>
            </div>
            <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
                <flux:heading size="sm" class="mb-3">Actions</flux:heading>
                <a href="{{ route('admin.membership.members.certificate', $member) }}" target="_blank">
                    <flux:button class="w-full" icon="document-text">Download Certificate</flux:button>
                </a>
            </div>
        </div>
    </div>
</div>
