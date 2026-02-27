<div>
    {{-- Header --}}
    <div class="mb-6 flex items-center justify-between">
        <div>
            <flux:heading size="xl">User Management</flux:heading>
            <flux:subheading>Manage user accounts, roles, and chapter assignments</flux:subheading>
        </div>
        <div class="flex items-center gap-2">
            @can('users.assign-role')
                <flux:button href="{{ route('admin.system.roles') }}" wire:navigate variant="ghost" icon="shield-check">
                    Manage Roles
                </flux:button>
            @endcan
            @can('users.create')
                <flux:button wire:click="openCreate" icon="plus">Create User</flux:button>
            @endcan
        </div>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <flux:callout variant="success" class="mb-4">{{ session('success') }}</flux:callout>
    @endif

    {{-- Create User Slide-in --}}
    @if($showCreate)
        <div class="mb-6 rounded-xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <div class="mb-5 flex items-center justify-between">
                <flux:heading size="lg">New Backoffice User</flux:heading>
                <button wire:click="$set('showCreate', false)"
                    class="rounded-lg p-1 text-zinc-400 hover:bg-zinc-100 hover:text-zinc-600 dark:hover:bg-zinc-800">
                    <flux:icon name="x-mark" class="h-5 w-5" />
                </button>
            </div>
            <form wire:submit="createUser" class="space-y-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <flux:field>
                        <flux:label>Full Name <span class="text-red-500">*</span></flux:label>
                        <flux:input wire:model="newName" placeholder="e.g. Amina Osei" />
                        <flux:error name="newName" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Email Address <span class="text-red-500">*</span></flux:label>
                        <flux:input wire:model="newEmail" type="email" placeholder="user@example.com" />
                        <flux:error name="newEmail" />
                    </flux:field>
                    <flux:field class="sm:col-span-2">
                        <div class="mb-2 flex items-center gap-2">
                            <flux:checkbox wire:model.live="useDefaultPassword" id="useDefaultPassword" />
                            <label for="useDefaultPassword" class="text-sm text-zinc-600 dark:text-zinc-400">
                                Use default password <code class="rounded bg-zinc-100 px-1 text-xs dark:bg-zinc-800">password</code>
                            </label>
                        </div>
                        @if(!$useDefaultPassword)
                            <flux:input wire:model="newPassword" type="password" placeholder="Min. 8 characters" />
                            <flux:error name="newPassword" />
                        @endif
                    </flux:field>
                    <flux:field>
                        <flux:label>Role <span class="text-red-500">*</span></flux:label>
                        <flux:select wire:model="newRole">
                            <option value="">Select a role…</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </flux:select>
                        <flux:error name="newRole" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Chapter</flux:label>
                        <flux:select wire:model="newChapterId">
                            <option value="">Global (no chapter)</option>
                            @foreach($chapters as $chapter)
                                <option value="{{ $chapter->id }}">{{ $chapter->name }}</option>
                            @endforeach
                        </flux:select>
                        <flux:error name="newChapterId" />
                    </flux:field>
                </div>
                <flux:field>
                    <div class="flex items-center gap-2">
                        <flux:checkbox wire:model="newIsAdmin" id="newIsAdmin" />
                        <label for="newIsAdmin" class="text-sm text-zinc-600 dark:text-zinc-400">
                            Grant admin panel access (<code class="text-xs">is_admin</code>)
                        </label>
                    </div>
                </flux:field>
                <div class="flex gap-2 pt-1">
                    <flux:button type="submit" variant="primary" icon="user-plus">Create User</flux:button>
                    <flux:button wire:click="$set('showCreate', false)" variant="ghost">Cancel</flux:button>
                </div>
            </form>
        </div>
    @endif

    {{-- Stats Row --}}
    <div class="mb-6 grid grid-cols-2 gap-4 sm:grid-cols-4">
        <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
            <p class="text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Total Users</p>
            <p class="mt-1 text-3xl font-bold text-zinc-900 dark:text-white">{{ $stats['total'] }}</p>
        </div>
        <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
            <p class="text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Admins</p>
            <p class="mt-1 text-3xl font-bold text-green-600 dark:text-green-400">{{ $stats['admins'] }}</p>
        </div>
        <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
            <p class="text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Verified</p>
            <p class="mt-1 text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['verified'] }}</p>
        </div>
        <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
            <p class="text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">New This Month</p>
            <p class="mt-1 text-3xl font-bold text-amber-600 dark:text-amber-400">{{ $stats['recent'] }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="mb-4 flex flex-wrap gap-3">
        <flux:input wire:model.live="search" placeholder="Search by name or email…" icon="magnifying-glass" class="w-full sm:w-72" />
        <flux:select wire:model.live="roleFilter" class="w-full sm:w-44">
            <option value="">All roles</option>
            @foreach($roles as $role)
                <option value="{{ $role->name }}">{{ $role->name }}</option>
            @endforeach
        </flux:select>
        <flux:select wire:model.live="chapterFilter" class="w-full sm:w-44">
            <option value="">All chapters</option>
            @foreach($chapters as $chapter)
                <option value="{{ $chapter->id }}">{{ $chapter->name }}</option>
            @endforeach
        </flux:select>
        @if($search || $roleFilter || $chapterFilter)
            <flux:button wire:click="$set('search', ''); $set('roleFilter', ''); $set('chapterFilter', '')" variant="ghost" size="sm" icon="x-mark">
                Clear filters
            </flux:button>
        @endif
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto rounded-xl border border-zinc-200 dark:border-zinc-700">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-zinc-500">User</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-zinc-500">Chapter</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-zinc-500">Roles</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-zinc-500">Access</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-zinc-500">Joined</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-900">
                @forelse($users as $user)
                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                        {{-- Avatar + Name + Email --}}
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-green-700 to-green-500 shadow-sm">
                                    <span class="text-sm font-semibold text-white">{{ $user->initials() }}</span>
                                </div>
                                <div class="min-w-0">
                                    <p class="truncate font-medium text-zinc-900 dark:text-white">{{ $user->name }}</p>
                                    <p class="truncate text-xs text-zinc-500 dark:text-zinc-400">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        {{-- Chapter --}}
                        <td class="px-4 py-3">
                            <span class="inline-flex rounded-full bg-zinc-100 px-2 py-0.5 text-xs text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400">
                                {{ $user->chapter?->name ?? 'Global' }}
                            </span>
                        </td>
                        {{-- Roles --}}
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-1">
                                @forelse($user->roles->take(3) as $role)
                                    <span class="inline-flex rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        {{ $role->name }}
                                    </span>
                                @empty
                                    <span class="text-xs text-zinc-400">—</span>
                                @endforelse
                                @if($user->roles->count() > 3)
                                    <span class="inline-flex rounded-full bg-zinc-100 px-2 py-0.5 text-xs text-zinc-500 dark:bg-zinc-800">
                                        +{{ $user->roles->count() - 3 }} more
                                    </span>
                                @endif
                            </div>
                        </td>
                        {{-- Admin badge --}}
                        <td class="px-4 py-3">
                            @if($user->is_admin)
                                <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-200">
                                    <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                    Admin
                                </span>
                            @else
                                <span class="inline-flex rounded-full bg-zinc-100 px-2 py-0.5 text-xs text-zinc-500 dark:bg-zinc-800 dark:text-zinc-400">
                                    User
                                </span>
                            @endif
                        </td>
                        {{-- Joined --}}
                        <td class="px-4 py-3 text-sm text-zinc-500 dark:text-zinc-400">
                            {{ $user->created_at->format('d M Y') }}
                        </td>
                        {{-- View button --}}
                        <td class="px-4 py-3 text-right">
                            <flux:button
                                size="sm"
                                variant="ghost"
                                href="{{ route('admin.system.user-detail', $user->id) }}"
                                wire:navigate
                                icon="arrow-right"
                            >View</flux:button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center">
                            <flux:icon name="users" class="mx-auto mb-2 h-8 w-8 text-zinc-300 dark:text-zinc-600" />
                            <p class="text-sm text-zinc-500">No users found.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $users->links() }}</div>
</div>
