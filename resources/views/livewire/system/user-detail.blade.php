<div>
    {{-- Back nav --}}
    <div class="mb-5">
        <flux:button href="{{ route('admin.system.users') }}" wire:navigate variant="ghost" icon="arrow-left" size="sm">
            Back to Users
        </flux:button>
    </div>

    {{-- Hero Header Card --}}
    <div class="mb-6 rounded-xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div class="flex items-center gap-5">
                {{-- Avatar --}}
                <div class="flex h-20 w-20 flex-shrink-0 items-center justify-center rounded-full
                    bg-gradient-to-br from-green-700 to-green-500 shadow-md">
                    <span class="text-2xl font-bold text-white">{{ $user->initials() }}</span>
                </div>
                {{-- Identity --}}
                <div>
                    <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $user->name }}</h1>
                    <p class="mt-0.5 text-sm text-zinc-500 dark:text-zinc-400">{{ $user->email }}</p>
                    @if($user->job_title)
                        <p class="mt-0.5 text-sm text-zinc-400 dark:text-zinc-500">{{ $user->job_title }}</p>
                    @endif
                    {{-- Status badges --}}
                    <div class="mt-2 flex flex-wrap gap-1.5">
                        @if($user->chapter)
                            <span class="inline-flex rounded-full bg-zinc-100 px-2.5 py-0.5 text-xs font-medium text-zinc-700 dark:bg-zinc-800 dark:text-zinc-300">
                                {{ $user->chapter->name }}
                            </span>
                        @else
                            <span class="inline-flex rounded-full bg-zinc-100 px-2.5 py-0.5 text-xs text-zinc-500 dark:bg-zinc-800 dark:text-zinc-400">
                                Global
                            </span>
                        @endif
                        @if($user->is_admin)
                            <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-200">
                                <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                Admin
                            </span>
                        @endif
                        @if($user->email_verified_at)
                            <span class="inline-flex rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                Verified
                            </span>
                        @else
                            <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-800 dark:bg-amber-900 dark:text-amber-200">
                                Unverified
                            </span>
                        @endif
                        <span class="inline-flex rounded-full bg-zinc-100 px-2.5 py-0.5 text-xs text-zinc-500 dark:bg-zinc-800 dark:text-zinc-400">
                            Joined {{ $user->created_at->format('d M Y') }}
                        </span>
                    </div>
                </div>
            </div>
            {{-- Edit button --}}
            @can('users.create')
                @unless($editingProfile)
                    <div class="flex-shrink-0">
                        <flux:button wire:click="editProfile" variant="ghost" icon="pencil" size="sm">
                            Edit Profile
                        </flux:button>
                    </div>
                @endunless
            @endcan
        </div>
    </div>

    {{-- Flash --}}
    @if($flashMessage)
        <flux:callout variant="{{ $flashVariant }}" class="mb-5">{{ $flashMessage }}</flux:callout>
    @endif

    {{-- Main Grid --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- LEFT — Profile Details --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <flux:heading size="lg" class="mb-5">Profile Details</flux:heading>

                @if($editingProfile)
                    <form wire:submit="saveProfile" class="space-y-4">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <flux:field>
                                <flux:label>Full Name <span class="text-red-500">*</span></flux:label>
                                <flux:input wire:model="name" />
                                <flux:error name="name" />
                            </flux:field>
                            <flux:field>
                                <flux:label>Email Address <span class="text-red-500">*</span></flux:label>
                                <flux:input wire:model="email" type="email" />
                                <flux:error name="email" />
                            </flux:field>
                            <flux:field>
                                <flux:label>Phone</flux:label>
                                <flux:input wire:model="phone" placeholder="+234…" />
                                <flux:error name="phone" />
                            </flux:field>
                            <flux:field>
                                <flux:label>Job Title</flux:label>
                                <flux:input wire:model="jobTitle" placeholder="e.g. Trade Officer" />
                                <flux:error name="jobTitle" />
                            </flux:field>
                            <flux:field>
                                <flux:label>Location</flux:label>
                                <flux:input wire:model="location" placeholder="e.g. Lagos, Nigeria" />
                                <flux:error name="location" />
                            </flux:field>
                            <flux:field>
                                <flux:label>Timezone</flux:label>
                                <flux:input wire:model="timezone" placeholder="e.g. Africa/Lagos" />
                                <flux:error name="timezone" />
                            </flux:field>
                            <flux:field>
                                <flux:label>Chapter</flux:label>
                                <flux:select wire:model="chapterId">
                                    <option value="">Global (no chapter)</option>
                                    @foreach($chapters as $chapter)
                                        <option value="{{ $chapter->id }}">{{ $chapter->name }}</option>
                                    @endforeach
                                </flux:select>
                                <flux:error name="chapterId" />
                            </flux:field>
                            <flux:field>
                                <flux:label>Admin Panel Access</flux:label>
                                <div class="mt-2 flex items-center gap-2">
                                    <flux:checkbox wire:model="isAdmin" id="isAdmin" />
                                    <label for="isAdmin" class="text-sm text-zinc-600 dark:text-zinc-400">
                                        Grant <code class="text-xs">is_admin</code> flag
                                    </label>
                                </div>
                            </flux:field>
                        </div>
                        <div class="flex gap-2 pt-2">
                            <flux:button type="submit" variant="primary" icon="check">Save Changes</flux:button>
                            <flux:button wire:click="cancelProfile" variant="ghost">Cancel</flux:button>
                        </div>
                    </form>
                @else
                    <dl class="grid grid-cols-1 gap-x-8 gap-y-5 sm:grid-cols-2">
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wide text-zinc-400 dark:text-zinc-500">Full Name</dt>
                            <dd class="mt-1 text-sm font-medium text-zinc-900 dark:text-white">{{ $user->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wide text-zinc-400 dark:text-zinc-500">Email</dt>
                            <dd class="mt-1 text-sm text-zinc-700 dark:text-zinc-300">{{ $user->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wide text-zinc-400 dark:text-zinc-500">Phone</dt>
                            <dd class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ $user->phone ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wide text-zinc-400 dark:text-zinc-500">Job Title</dt>
                            <dd class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ $user->job_title ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wide text-zinc-400 dark:text-zinc-500">Location</dt>
                            <dd class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ $user->location ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wide text-zinc-400 dark:text-zinc-500">Timezone</dt>
                            <dd class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ $user->timezone ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wide text-zinc-400 dark:text-zinc-500">Chapter</dt>
                            <dd class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ $user->chapter?->name ?? 'Global' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wide text-zinc-400 dark:text-zinc-500">Email Verified</dt>
                            <dd class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                                {{ $user->email_verified_at ? $user->email_verified_at->format('d M Y') : 'Not verified' }}
                            </dd>
                        </div>
                    </dl>
                @endif
            </div>

            {{-- Permissions Panel --}}
            <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                    <flux:heading size="lg">Permissions</flux:heading>
                    <div class="flex flex-wrap gap-2">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                            <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                            {{ $rolePermCount }} via roles
                        </span>
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-800 dark:bg-amber-900 dark:text-amber-200">
                            <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                            {{ $directPermCount }} direct
                        </span>
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-200">
                            <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                            {{ $effectiveCount }} effective
                        </span>
                    </div>
                </div>

                <p class="mb-5 text-xs text-zinc-500 dark:text-zinc-400">
                    <span class="inline-flex items-center gap-1">
                        <span class="inline-block h-2.5 w-2.5 rounded-sm bg-blue-200 dark:bg-blue-800"></span>
                        Blue = inherited from role (remove the role to lose these)
                    </span>
                    &nbsp;&middot;&nbsp;
                    <span class="inline-flex items-center gap-1">
                        <span class="inline-block h-2.5 w-2.5 rounded-sm bg-amber-200 dark:bg-amber-800"></span>
                        Amber = directly granted (click × to revoke)
                    </span>
                </p>

                <div class="space-y-6">
                    @foreach($permissionMap as $group => $perms)
                        <div>
                            <p class="mb-3 text-xs font-semibold uppercase tracking-wider text-zinc-400 dark:text-zinc-500">
                                {{ ucfirst($group) }}
                            </p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($perms as $perm)
                                    @if($perm['via_role'])
                                        {{-- Blue: inherited from role --}}
                                        <span
                                            title="Inherited from role: {{ $perm['role_source'] }}"
                                            class="inline-flex items-center gap-1 rounded-full bg-blue-100 px-2.5 py-1 text-xs font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            {{ $perm['short'] }}
                                            <span class="text-[10px] text-blue-400 dark:text-blue-500"
                                                title="{{ $perm['role_source'] }}">
                                                ({{ $perm['role_source'] }})
                                            </span>
                                        </span>
                                    @elseif($perm['direct'])
                                        {{-- Amber: directly granted, revocable --}}
                                        <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-2.5 py-1 text-xs font-medium text-amber-800 dark:bg-amber-900 dark:text-amber-200">
                                            {{ $perm['short'] }}
                                            @can('users.assign-role')
                                                <button
                                                    wire:click="revokePermission('{{ $perm['name'] }}')"
                                                    wire:confirm="Revoke '{{ $perm['name'] }}' from this user?"
                                                    class="ml-0.5 text-amber-500 transition-colors hover:text-red-500"
                                                    title="Revoke"
                                                >&times;</button>
                                            @endcan
                                        </span>
                                    @else
                                        {{-- Gray: not granted — show add button if authorized --}}
                                        @can('users.assign-role')
                                            <button
                                                wire:click="grantPermission('{{ $perm['name'] }}')"
                                                title="Grant {{ $perm['name'] }}"
                                                class="inline-flex items-center gap-1 rounded-full border border-dashed border-zinc-300 px-2.5 py-1 text-xs text-zinc-400 transition-colors hover:border-green-400 hover:bg-green-50 hover:text-green-700 dark:border-zinc-600 dark:text-zinc-500 dark:hover:border-green-600 dark:hover:bg-green-950 dark:hover:text-green-400">
                                                <span>+</span>
                                                {{ $perm['short'] }}
                                            </button>
                                        @endcan
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- RIGHT — Roles --}}
        <div>
            <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <flux:heading size="lg" class="mb-4">Roles</flux:heading>

                {{-- Assigned roles --}}
                <div class="mb-4 space-y-2">
                    @forelse($user->roles as $role)
                        <div class="flex items-center justify-between rounded-lg border border-blue-100 bg-blue-50 px-3 py-2.5 dark:border-blue-900 dark:bg-blue-950">
                            <div class="flex items-center gap-2">
                                <flux:icon name="shield-check" class="h-4 w-4 text-blue-500" />
                                <span class="text-sm font-medium text-blue-900 dark:text-blue-200">{{ $role->name }}</span>
                            </div>
                            @if($role->name !== 'super-admin')
                                @can('users.assign-role')
                                    <button
                                        wire:click="removeRole('{{ $role->name }}')"
                                        wire:confirm="Remove role '{{ $role->name }}' from {{ $user->name }}?"
                                        class="rounded p-0.5 text-blue-400 transition-colors hover:bg-blue-100 hover:text-red-500 dark:hover:bg-blue-900"
                                        title="Remove role"
                                    >
                                        <flux:icon name="x-mark" class="h-3.5 w-3.5" />
                                    </button>
                                @endcan
                            @endif
                        </div>
                    @empty
                        <p class="rounded-lg border border-dashed border-zinc-300 px-3 py-4 text-center text-sm text-zinc-400 dark:border-zinc-700 dark:text-zinc-500">
                            No roles assigned
                        </p>
                    @endforelse
                </div>

                {{-- Add role --}}
                @can('users.assign-role')
                    @if($availableRoles->isNotEmpty())
                        <div class="space-y-2 border-t border-zinc-100 pt-4 dark:border-zinc-800">
                            <p class="text-xs text-zinc-500 dark:text-zinc-400">Assign a role to inherit its default permissions</p>
                            <flux:select wire:model="addRoleName">
                                <option value="">Select role to assign…</option>
                                @foreach($availableRoles as $role)
                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                @endforeach
                            </flux:select>
                            <flux:button wire:click="addRole" variant="primary" class="w-full" icon="plus">
                                Assign Role
                            </flux:button>
                        </div>
                    @else
                        <p class="text-xs text-zinc-400 dark:text-zinc-500">All available roles are assigned.</p>
                    @endif
                @endcan
            </div>
        </div>

    </div>
</div>
