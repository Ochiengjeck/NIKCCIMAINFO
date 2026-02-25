<div>
    <div class="mb-6">
        <flux:heading size="xl">Roles & Permissions</flux:heading>
        <flux:subheading>Read-only matrix of all roles and their assigned permissions</flux:subheading>
    </div>

    <div class="overflow-x-auto rounded-xl border border-zinc-200 dark:border-zinc-700">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <th class="sticky left-0 bg-zinc-50 px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500 dark:bg-zinc-800">Role</th>
                    @foreach($permissions as $group => $perms)
                        <th class="px-3 py-3 text-center text-xs font-medium uppercase text-zinc-500" colspan="{{ $perms->count() }}">
                            {{ ucfirst($group) }}
                        </th>
                    @endforeach
                </tr>
                <tr>
                    <th class="sticky left-0 bg-zinc-50 px-4 py-2 dark:bg-zinc-800"></th>
                    @foreach($permissions as $group => $perms)
                        @foreach($perms as $permission)
                            <th class="px-3 py-2 text-center text-xs text-zinc-400" title="{{ $permission->name }}">
                                {{ str_replace($group.'.', '', $permission->name) }}
                            </th>
                        @endforeach
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-900">
                @foreach($roles as $role)
                    <tr>
                        <td class="sticky left-0 bg-white px-4 py-3 font-medium text-zinc-900 dark:bg-zinc-900 dark:text-white">
                            {{ $role->name }}
                        </td>
                        @foreach($permissions as $group => $perms)
                            @foreach($perms as $permission)
                                <td class="px-3 py-3 text-center">
                                    @if($role->hasPermissionTo($permission->name))
                                        <span class="text-green-500" title="Has permission">&#10003;</span>
                                    @else
                                        <span class="text-zinc-200 dark:text-zinc-700">&#8212;</span>
                                    @endif
                                </td>
                            @endforeach
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
