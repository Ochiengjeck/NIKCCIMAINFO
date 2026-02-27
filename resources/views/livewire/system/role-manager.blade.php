<div>
    {{-- Header --}}
    <div class="mb-6 flex items-center justify-between">
        <div>
            <flux:heading size="xl">Roles & Permissions</flux:heading>
            <flux:subheading>Configure permissions for each role. Click a role to expand its permission set.</flux:subheading>
        </div>
        @can('users.assign-role')
            <flux:button href="{{ route('admin.system.users') }}" wire:navigate variant="ghost" icon="arrow-left">
                Back to Users
            </flux:button>
        @endcan
    </div>

    {{-- Flash --}}
    @if($flashMessage)
        <flux:callout variant="success" class="mb-5">{{ $flashMessage }}</flux:callout>
    @endif

    {{-- Summary --}}
    <p class="mb-5 text-sm text-zinc-500 dark:text-zinc-400">
        <span class="font-medium text-zinc-700 dark:text-zinc-300">{{ $roles->count() }}</span> roles
        &middot;
        <span class="font-medium text-zinc-700 dark:text-zinc-300">{{ $permissions->flatten()->count() }}</span> permissions
    </p>

    {{-- Role Accordion --}}
    <div class="space-y-3" x-data="{ open: null }">
        @foreach($roles as $role)
            @php
                $isSuperAdmin = $role->name === 'super-admin';
                $permCount = $role->permissions->count();
                $totalPerms = $permissions->flatten()->count();
            @endphp
            <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900"
                 wire:key="role-{{ $role->id }}">

                {{-- Accordion Header --}}
                <button
                    type="button"
                    class="flex w-full items-center justify-between px-5 py-4 text-left transition-colors hover:bg-zinc-50 dark:hover:bg-zinc-800/50"
                    x-on:click="open === '{{ $role->name }}' ? open = null : open = '{{ $role->name }}'"
                >
                    <div class="flex items-center gap-3">
                        {{-- Role icon --}}
                        <div class="flex h-9 w-9 items-center justify-center rounded-lg
                            {{ $isSuperAdmin ? 'bg-amber-100 dark:bg-amber-900' : 'bg-blue-100 dark:bg-blue-900' }}">
                            <flux:icon name="{{ $isSuperAdmin ? 'star' : 'shield-check' }}"
                                class="h-4 w-4 {{ $isSuperAdmin ? 'text-amber-600 dark:text-amber-400' : 'text-blue-600 dark:text-blue-400' }}" />
                        </div>
                        <div>
                            <p class="font-semibold text-zinc-900 dark:text-white">{{ $role->name }}</p>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400">
                                @if($isSuperAdmin)
                                    Full access — all permissions granted
                                @else
                                    {{ $permCount }} of {{ $totalPerms }} permissions assigned
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        @if($isSuperAdmin)
                            <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-800 dark:bg-amber-900 dark:text-amber-200">
                                all permissions
                            </span>
                        @else
                            {{-- Mini progress bar --}}
                            <div class="hidden sm:block">
                                <div class="h-1.5 w-24 overflow-hidden rounded-full bg-zinc-200 dark:bg-zinc-700">
                                    @php $pct = $totalPerms > 0 ? round($permCount / $totalPerms * 100) : 0; @endphp
                                    <div class="h-1.5 rounded-full bg-blue-500" style="width: {{ $pct }}%"></div>
                                </div>
                            </div>
                            <span class="rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                {{ $permCount }}
                            </span>
                        @endif
                        <flux:icon name="chevron-down"
                            class="h-4 w-4 text-zinc-400 transition-transform duration-200"
                            x-bind:class="open === '{{ $role->name }}' ? 'rotate-180' : ''" />
                    </div>
                </button>

                {{-- Accordion Body --}}
                <div
                    x-show="open === '{{ $role->name }}'"
                    x-collapse
                    x-cloak
                >
                    <div class="border-t border-zinc-100 px-5 py-5 dark:border-zinc-800">
                        @if($isSuperAdmin)
                            <p class="mb-4 rounded-lg bg-amber-50 px-4 py-3 text-sm text-amber-800 dark:bg-amber-950 dark:text-amber-300">
                                <strong>super-admin</strong> bypasses all permission checks via the gate-before hook.
                                This role's permissions cannot be modified individually.
                            </p>
                        @endif

                        <div class="space-y-5">
                            @foreach($permissions as $group => $perms)
                                <div>
                                    <p class="mb-3 text-xs font-semibold uppercase tracking-wider text-zinc-400 dark:text-zinc-500">
                                        {{ ucfirst($group) }}
                                    </p>
                                    <div class="grid grid-cols-2 gap-2 sm:grid-cols-3 lg:grid-cols-4">
                                        @foreach($perms as $permission)
                                            @php $checked = $role->hasPermissionTo($permission->name); @endphp
                                            <div class="flex items-center gap-2 rounded-lg px-3 py-2 transition-colors
                                                {{ $checked ? 'bg-green-50 dark:bg-green-950' : 'bg-zinc-50 dark:bg-zinc-800' }}"
                                                wire:key="perm-{{ $role->id }}-{{ $permission->id }}">
                                                @if($isSuperAdmin)
                                                    <input type="checkbox" checked disabled
                                                        class="h-4 w-4 cursor-not-allowed opacity-50" />
                                                @elsecan('users.assign-role')
                                                    <input
                                                        type="checkbox"
                                                        wire:click="togglePermission({{ $role->id }}, '{{ $permission->name }}')"
                                                        wire:loading.attr="disabled"
                                                        {{ $checked ? 'checked' : '' }}
                                                        class="h-4 w-4 cursor-pointer accent-green-500"
                                                        title="{{ $permission->name }}"
                                                    />
                                                @else
                                                    <span class="{{ $checked ? 'text-green-500' : 'text-zinc-300 dark:text-zinc-600' }}">
                                                        {!! $checked ? '&#10003;' : '&#8212;' !!}
                                                    </span>
                                                @endcan
                                                <span class="text-xs {{ $checked ? 'font-medium text-zinc-800 dark:text-zinc-200' : 'text-zinc-500 dark:text-zinc-400' }}">
                                                    {{ str_replace($group.'.', '', $permission->name) }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
