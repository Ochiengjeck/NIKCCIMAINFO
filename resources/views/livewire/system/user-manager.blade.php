<div>
    <div class="mb-6">
        <flux:heading size="xl">User Management</flux:heading>
        <flux:subheading>Manage user accounts, roles, and chapter assignments</flux:subheading>
    </div>

    @if(session('success'))
        <flux:callout variant="success" class="mb-4">{{ session('success') }}</flux:callout>
    @endif

    {{-- Search --}}
    <div class="mb-4">
        <flux:input wire:model.live="search" placeholder="Search by name or email…" icon="magnifying-glass" class="sm:w-72" />
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto rounded-xl border border-zinc-200 dark:border-zinc-700">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Email</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Chapter</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Roles</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Admin</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Assign Role</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Assign Chapter</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-900">
                @forelse($users as $user)
                    <tr>
                        <td class="px-4 py-3 font-medium text-zinc-900 dark:text-white">{{ $user->name }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">{{ $user->email }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-500">{{ $user->chapter?->name ?? 'Global' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-1">
                                @foreach($user->roles as $role)
                                    <span class="inline-flex rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        {{ $role->name }}
                                    </span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium
                                {{ $user->is_admin ? 'bg-green-100 text-green-800' : 'bg-zinc-100 text-zinc-600' }}">
                                {{ $user->is_admin ? 'Yes' : 'No' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2">
                                <select
                                    x-data="{ role: '' }"
                                    x-model="role"
                                    class="rounded-md border border-zinc-200 bg-white px-2 py-1 text-xs text-zinc-700 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-300"
                                >
                                    <option value="">Select role…</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                <flux:button
                                    size="sm"
                                    x-on:click="$wire.assignRole({{ $user->id }}, role)"
                                >Assign</flux:button>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2">
                                <select
                                    x-data="{ chapterId: '{{ $user->chapter_id ?? '' }}' }"
                                    x-model="chapterId"
                                    class="rounded-md border border-zinc-200 bg-white px-2 py-1 text-xs text-zinc-700 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-300"
                                >
                                    <option value="">Global</option>
                                    @foreach($chapters as $chapter)
                                        <option value="{{ $chapter->id }}">{{ $chapter->name }}</option>
                                    @endforeach
                                </select>
                                <flux:button
                                    size="sm"
                                    x-on:click="$wire.assignChapter({{ $user->id }}, chapterId)"
                                >Assign</flux:button>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <flux:button
                                size="sm"
                                variant="ghost"
                                wire:click="toggleActive({{ $user->id }})"
                                wire:confirm="Toggle admin status for this user?"
                            >Toggle Admin</flux:button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-zinc-500">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $users->links() }}</div>
</div>
