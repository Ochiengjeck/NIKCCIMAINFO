<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <flux:heading size="xl">Leadership Profiles</flux:heading>
            <flux:subheading>Manage executive and leadership team profiles</flux:subheading>
        </div>
        @can('cms.edit')
            <flux:button wire:click="openForm()" variant="primary" icon="plus">Add Profile</flux:button>
        @endcan
    </div>

    @if(session('success'))
        <flux:callout variant="success" class="mb-4">{{ session('success') }}</flux:callout>
    @endif

    {{-- Profile Form --}}
    @if($showForm)
        <div class="mb-6 rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:heading size="lg" class="mb-5">{{ $editingId ? 'Edit Profile' : 'Add Profile' }}</flux:heading>

            <form wire:submit="save" class="space-y-5">
                <div class="grid gap-4 lg:grid-cols-2">
                    <flux:field>
                        <flux:label>Name <span class="text-red-500">*</span></flux:label>
                        <flux:input wire:model="name" placeholder="Full name" />
                        <flux:error name="name" />
                    </flux:field>

                    <flux:field>
                        <flux:label>Position <span class="text-red-500">*</span></flux:label>
                        <flux:input wire:model="position" placeholder="e.g. Chapter President" />
                        <flux:error name="position" />
                    </flux:field>

                    <flux:field>
                        <flux:label>Email</flux:label>
                        <flux:input wire:model="email" type="email" placeholder="name@nikccima.org" />
                        <flux:error name="email" />
                    </flux:field>

                    <flux:field>
                        <flux:label>Chapter</flux:label>
                        <flux:select wire:model="chapterId">
                            <flux:select.option :value="null">Global (All Chapters)</flux:select.option>
                            @foreach($chapters as $chapter)
                                <flux:select.option :value="$chapter->id">{{ $chapter->name }}</flux:select.option>
                            @endforeach
                        </flux:select>
                    </flux:field>

                    <flux:field>
                        <flux:label>Sort Order</flux:label>
                        <flux:input wire:model="sortOrder" type="number" min="0" />
                    </flux:field>

                    <flux:field>
                        <flux:label>Visibility</flux:label>
                        <flux:checkbox wire:model="isActive" label="Show on public website" />
                    </flux:field>
                </div>

                <flux:field>
                    <flux:label>Bio</flux:label>
                    <flux:textarea wire:model="bio" rows="4" placeholder="Brief biography" />
                </flux:field>

                <flux:field>
                    <flux:label>Photo</flux:label>
                    <livewire:components.media-picker
                        wire:model="photoMediaItemId"
                        disk="public"
                        type="image"
                        folder="cms/leadership"
                        accept="image/*"
                        :key="'leader-picker-' . ($editingId ?? 'new')"
                    />
                    <flux:error name="photoMediaItemId" />
                </flux:field>

                <div class="flex gap-2">
                    <flux:button type="submit" variant="primary" icon="check">Save Profile</flux:button>
                    <flux:button wire:click="closeForm()" variant="ghost">Cancel</flux:button>
                </div>
            </form>
        </div>
    @endif

    {{-- Profile Cards --}}
    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        @forelse($profiles as $profile)
            @php
                $chapterName  = $profile->chapter?->name ?? '';
                $isNigeria    = str_contains(strtolower($chapterName), 'nigeria');
                $isKenya      = str_contains(strtolower($chapterName), 'kenya');
                $badgeClass   = $isNigeria
                    ? 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300'
                    : ($isKenya
                        ? 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300'
                        : 'bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400');
            @endphp
            <div class="flex flex-col rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
                {{-- Photo + Name --}}
                <div class="mb-4 flex items-start gap-4">
                    @if($profile->photoUrl())
                        <img src="{{ $profile->photoUrl() }}"
                            alt="{{ $profile->name }}"
                            class="h-16 w-16 flex-shrink-0 rounded-full object-cover shadow-sm" />
                    @else
                        <div class="flex h-16 w-16 flex-shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-green-700 to-green-600 shadow-sm">
                            <span class="text-xl font-semibold text-white">{{ strtoupper(substr($profile->name, 0, 2)) }}</span>
                        </div>
                    @endif
                    <div class="min-w-0 flex-1">
                        <p class="truncate font-semibold text-zinc-900 dark:text-white">{{ $profile->name }}</p>
                        <p class="text-sm text-zinc-500 line-clamp-2">{{ $profile->position }}</p>
                        @if($profile->chapter)
                            <span class="mt-1.5 inline-flex items-center rounded-full px-2 py-0.5 text-[11px] font-medium {{ $badgeClass }}">
                                {{ $profile->chapter->name }}
                            </span>
                        @endif
                    </div>
                </div>

                @if($profile->bio)
                    <p class="mb-4 flex-1 text-xs text-zinc-500 line-clamp-3">{{ $profile->bio }}</p>
                @endif

                <div class="mt-auto flex items-center justify-between">
                    <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium
                        {{ $profile->is_active ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : 'bg-zinc-100 text-zinc-500 dark:bg-zinc-700 dark:text-zinc-400' }}">
                        {{ $profile->is_active ? 'Active' : 'Hidden' }}
                    </span>
                    @can('cms.edit')
                        <div class="flex gap-1">
                            <flux:button wire:click="openForm({{ $profile->id }})" size="sm" variant="ghost" icon="pencil" />
                            <flux:button wire:click="delete({{ $profile->id }})" wire:confirm="Delete this profile?" size="sm" variant="ghost" icon="trash" class="text-red-500" />
                        </div>
                    @endcan
                </div>
            </div>
        @empty
            <div class="col-span-full flex h-40 items-center justify-center rounded-xl border border-dashed border-zinc-300 dark:border-zinc-700">
                <div class="text-center">
                    <flux:icon name="users" class="mx-auto mb-2 h-8 w-8 text-zinc-300" />
                    <p class="text-sm text-zinc-500">No leadership profiles yet. Add the first one.</p>
                </div>
            </div>
        @endforelse
    </div>
</div>
