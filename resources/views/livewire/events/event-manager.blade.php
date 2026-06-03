<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Events & Trade Missions</flux:heading>
            <flux:subheading>Manage NiKCCIMA flagship events and trade missions</flux:subheading>
        </div>
        @can('events.create')
            <flux:button icon="plus" wire:click="create">New Event</flux:button>
        @endcan
    </div>
    @if(session('success'))<flux:callout variant="success" class="mb-4" dismissible>{{ session('success') }}</flux:callout>@endif
    @if($showForm)
    <div class="mb-6 rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:heading size="lg" class="mb-4">{{ $editingId ? 'Edit Event' : 'New Event' }}</flux:heading>
        <form wire:submit="save" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            @if($canSelectChapter)
            <flux:field>
                <flux:label>Chapter</flux:label>
                <flux:select wire:model="chapter_id">
                    <option value="">Select chapter</option>
                    @foreach($chapters as $chapter)
                        <option value="{{ $chapter->id }}">{{ $chapter->name }}</option>
                    @endforeach
                </flux:select>
                <flux:error name="chapter_id" />
            </flux:field>
            @endif
            <flux:field class="sm:col-span-2"><flux:label>Title</flux:label><flux:input wire:model="title" /><flux:error name="title" /></flux:field>
            <flux:field><flux:label>Type</flux:label><flux:select wire:model="type"><option value="flagship">Flagship</option><option value="trade-mission">Trade Mission</option><option value="sector-forum">Sector Forum</option></flux:select></flux:field>
            <flux:field><flux:label>Status</flux:label><flux:select wire:model="status"><option value="draft">Draft</option><option value="published">Published</option><option value="ongoing">Ongoing</option><option value="completed">Completed</option><option value="cancelled">Cancelled</option></flux:select></flux:field>
            <flux:field><flux:label>Starts At</flux:label><flux:input type="datetime-local" wire:model="starts_at" /><flux:error name="starts_at" /></flux:field>
            <flux:field><flux:label>Ends At</flux:label><flux:input type="datetime-local" wire:model="ends_at" /><flux:error name="ends_at" /></flux:field>
            <flux:field><flux:label>Venue</flux:label><flux:input wire:model="venue" /></flux:field>
            <flux:field><flux:label>Max Capacity</flux:label><flux:input type="number" wire:model="max_capacity" /></flux:field>
            <flux:field class="sm:col-span-2">
                <flux:label>Description</flux:label>
                <x-trix-editor state-path="description" :value="$description" wire:key="event-desc-{{ $editingId ?? 'new' }}" />
                <flux:error name="description" />
            </flux:field>

            {{-- Poster / featured image --}}
            <flux:field class="sm:col-span-2">
                <flux:label>Poster / Featured Image</flux:label>
                <flux:description>Shown on the public events list and event page.</flux:description>
                <livewire:components.media-picker
                    wire:model="featuredImageId"
                    disk="public"
                    type="image"
                    folder="cms/events"
                    accept="image/*"
                    :key="'event-poster-' . ($editingId ?? 'new')"
                />
                <flux:error name="featuredImageId" />
            </flux:field>

            {{-- Photo gallery --}}
            <flux:field class="sm:col-span-2">
                <flux:label>Photo Gallery</flux:label>
                <flux:description>Add images one at a time — they appear in a gallery on the event page.</flux:description>

                @if($galleryIds)
                    <div class="mb-3 grid grid-cols-3 gap-3 sm:grid-cols-5">
                        @foreach($galleryIds as $gid)
                            @php $gItem = \App\Models\MediaItem::find($gid); @endphp
                            @if($gItem)
                                <div class="group relative overflow-hidden rounded-lg border border-zinc-200 dark:border-zinc-700">
                                    <img src="{{ $gItem->url() }}" alt="{{ $gItem->alt_text }}" class="aspect-square w-full object-cover" />
                                    <button type="button" wire:click="removeGalleryImage({{ $gid }})"
                                        class="absolute right-1 top-1 flex h-6 w-6 items-center justify-center rounded-full bg-red-600 text-white opacity-0 transition group-hover:opacity-100"
                                        title="Remove">
                                        &times;
                                    </button>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif

                <div class="flex items-end gap-3">
                    <div class="flex-1">
                        <livewire:components.media-picker
                            wire:model="galleryPickerId"
                            disk="public"
                            type="image"
                            folder="cms/events"
                            accept="image/*"
                            :key="'event-gallery-' . ($editingId ?? 'new') . '-' . count($galleryIds)"
                        />
                    </div>
                    <flux:button type="button" wire:click="addGalleryImage" icon="plus" size="sm">Add to gallery</flux:button>
                </div>
            </flux:field>

            {{-- Brochure (PDF) --}}
            <flux:field class="sm:col-span-2">
                <flux:label>Brochure (PDF)</flux:label>
                <flux:description>Offered as a download on the public event page.</flux:description>
                <livewire:components.media-picker
                    wire:model="brochureId"
                    disk="public"
                    type="document"
                    folder="cms/events"
                    accept="application/pdf"
                    :key="'event-brochure-' . ($editingId ?? 'new')"
                />
                <flux:error name="brochureId" />
            </flux:field>

            {{-- Inquiry channels --}}
            <flux:field class="sm:col-span-2">
                <flux:label>Inquiry Channels</flux:label>
                <flux:description>How visitors can inquire from the public event page. Leave empty to use the default contact form.</flux:description>

                <div class="space-y-3">
                    @forelse($inquiryChannels as $i => $channel)
                        <div class="flex items-start gap-3">
                            <flux:select wire:model="inquiryChannels.{{ $i }}.type" class="w-40">
                                <option value="email">Email</option>
                                <option value="phone">Phone</option>
                                <option value="whatsapp">WhatsApp</option>
                                <option value="url">Registration URL</option>
                            </flux:select>
                            <div class="flex-1">
                                <flux:input
                                    wire:model="inquiryChannels.{{ $i }}.value"
                                    :placeholder="match($channel['type'] ?? 'email') {
                                        'email' => 'name@example.com',
                                        'phone' => '+234 803 000 0000',
                                        'whatsapp' => '+254 790 000000',
                                        'url' => 'https://...',
                                        default => '',
                                    }"
                                />
                                <flux:error name="inquiryChannels.{{ $i }}.value" />
                                <flux:error name="inquiryChannels.{{ $i }}.type" />
                            </div>
                            <flux:button type="button" wire:click="removeInquiryChannel({{ $i }})" variant="ghost" icon="trash" size="sm" class="text-red-500" />
                        </div>
                    @empty
                        <p class="text-sm text-zinc-500">No channels yet — the event will use the default contact form.</p>
                    @endforelse
                </div>

                <div class="mt-3">
                    <flux:button type="button" wire:click="addInquiryChannel" icon="plus" size="sm" variant="ghost">Add channel</flux:button>
                </div>
            </flux:field>

            <div class="sm:col-span-2 flex gap-3"><flux:button type="submit" variant="primary">Save</flux:button><flux:button wire:click="cancel" variant="ghost">Cancel</flux:button></div>
        </form>
    </div>
    @endif
    <div class="overflow-x-auto rounded-xl border border-zinc-200 dark:border-zinc-700">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Title</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Type</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Starts</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Venue</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-900">
                @forelse($events as $event)
                <tr>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            @if($event->featured_image)
                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($event->featured_image) }}"
                                    class="h-9 w-9 flex-shrink-0 rounded-lg object-cover" alt="" />
                            @else
                                <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-zinc-100 dark:bg-zinc-800">
                                    <svg class="h-4 w-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                    </svg>
                                </div>
                            @endif
                            <span class="font-medium text-zinc-900 dark:text-white">{{ $event->title }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-sm text-zinc-500">{{ ucwords(str_replace('-',' ',$event->type)) }}</td>
                    <td class="px-4 py-3">
                        @php $sc = match($event->status) { 'published'=>'bg-green-100 text-green-800','ongoing'=>'bg-blue-100 text-blue-800','completed'=>'bg-zinc-100 text-zinc-600','cancelled'=>'bg-red-100 text-red-800',default=>'bg-yellow-100 text-yellow-800' }; @endphp
                        <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium {{ $sc }}">{{ ucfirst($event->status) }}</span>
                    </td>
                    <td class="px-4 py-3 text-sm text-zinc-500">{{ $event->starts_at->format('d M Y H:i') }}</td>
                    <td class="px-4 py-3 text-sm text-zinc-500">{{ $event->venue ?? '—' }}</td>
                    <td class="px-4 py-3 text-right">@can('events.edit')<flux:button size="sm" wire:click="edit({{ $event->id }})">Edit</flux:button>@endcan</td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-zinc-500">No events yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $events->links() }}</div>
</div>
