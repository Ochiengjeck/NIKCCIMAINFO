<div>
    {{-- ===================== PAGE HEADER ===================== --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-start gap-3">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <flux:heading size="xl">Events &amp; Trade Missions</flux:heading>
                <flux:subheading>Manage NiKCCIMA flagship events and trade missions</flux:subheading>
            </div>
        </div>
        @can('events.create')
            <flux:button icon="plus" variant="primary" wire:click="create">New Event</flux:button>
        @endcan
    </div>

    @if(session('success'))<flux:callout variant="success" class="mb-4" dismissible>{{ session('success') }}</flux:callout>@endif

    {{-- ===================== CREATE / EDIT FORM ===================== --}}
    @if($showForm)
    <div class="mb-8 overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
        {{-- Form header --}}
        <div class="flex items-center justify-between border-b border-zinc-200 bg-zinc-50/70 px-6 py-4 dark:border-zinc-700 dark:bg-zinc-800/50">
            <div class="flex items-center gap-2.5">
                <flux:badge :color="$editingId ? 'amber' : 'green'" size="sm">{{ $editingId ? 'Editing' : 'New' }}</flux:badge>
                <flux:heading size="lg">{{ $editingId ? 'Edit Event' : 'Create Event' }}</flux:heading>
            </div>
            <flux:button wire:click="cancel" variant="ghost" size="sm" icon="x-mark">Close</flux:button>
        </div>

        <form wire:submit="save" class="divide-y divide-zinc-100 dark:divide-zinc-800">

            {{-- ---- Section: Basic Details ---- --}}
            <section class="px-6 py-6">
                <div class="grid gap-6 lg:grid-cols-[220px_1fr]">
                    <div>
                        <h3 class="text-sm font-semibold text-zinc-900 dark:text-white">Basic Details</h3>
                        <p class="mt-1 text-xs leading-relaxed text-zinc-500">Title, category and who owns this event.</p>
                    </div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        @if($canSelectChapter)
                        <flux:field class="sm:col-span-2">
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
                        <flux:field class="sm:col-span-2"><flux:label>Title</flux:label><flux:input wire:model="title" placeholder="e.g. Nigeria–Kenya Trade Summit 2026" /><flux:error name="title" /></flux:field>
                        <flux:field><flux:label>Type</flux:label><flux:select wire:model="type"><option value="flagship">Flagship</option><option value="trade-mission">Trade Mission</option><option value="sector-forum">Sector Forum</option></flux:select></flux:field>
                        <flux:field><flux:label>Status</flux:label><flux:select wire:model="status"><option value="draft">Draft</option><option value="published">Published</option><option value="ongoing">Ongoing</option><option value="completed">Completed</option><option value="cancelled">Cancelled</option></flux:select></flux:field>
                        <flux:field class="sm:col-span-2">
                            <flux:label>Organizer</flux:label>
                            <flux:select wire:model="organizer_id">
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </flux:select>
                            <flux:description>Defaults to you. Change it to credit another staff member as the event organiser (shown on the public event page).</flux:description>
                            <flux:error name="organizer_id" />
                        </flux:field>
                    </div>
                </div>
            </section>

            {{-- ---- Section: Schedule & Venue ---- --}}
            <section class="px-6 py-6">
                <div class="grid gap-6 lg:grid-cols-[220px_1fr]">
                    <div>
                        <h3 class="text-sm font-semibold text-zinc-900 dark:text-white">Schedule &amp; Venue</h3>
                        <p class="mt-1 text-xs leading-relaxed text-zinc-500">When and where the event takes place.</p>
                    </div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <flux:field><flux:label>Starts At</flux:label><flux:input type="datetime-local" wire:model="starts_at" /><flux:error name="starts_at" /></flux:field>
                        <flux:field><flux:label>Ends At</flux:label><flux:input type="datetime-local" wire:model="ends_at" /><flux:error name="ends_at" /></flux:field>
                        <flux:field><flux:label>Venue</flux:label><flux:input wire:model="venue" placeholder="e.g. Eko Hotel, Lagos" /></flux:field>
                        <flux:field><flux:label>Max Capacity</flux:label><flux:input type="number" wire:model="max_capacity" placeholder="Optional" /></flux:field>
                    </div>
                </div>
            </section>

            {{-- ---- Section: Registration ---- --}}
            <section class="px-6 py-6">
                <div class="grid gap-6 lg:grid-cols-[220px_1fr]">
                    <div>
                        <h3 class="text-sm font-semibold text-zinc-900 dark:text-white">Registration</h3>
                        <p class="mt-1 text-xs leading-relaxed text-zinc-500">Let visitors sign up directly from the event page.</p>
                    </div>
                    <div>
                        <flux:field>
                            <div class="rounded-xl border border-zinc-200 bg-zinc-50/60 p-4 dark:border-zinc-700 dark:bg-zinc-800/40">
                                <flux:checkbox wire:model="registration_enabled" label="Enable on-site registration" />
                                <flux:description class="mt-2">When on, visitors see a “Register Now” button and a registration form on this event’s page. The external Register / RSVP link (under Inquiry Channels) works independently.</flux:description>
                            </div>
                            <flux:error name="registration_enabled" />
                        </flux:field>
                    </div>
                </div>
            </section>

            {{-- ---- Section: Description ---- --}}
            <section class="px-6 py-6">
                <div class="grid gap-6 lg:grid-cols-[220px_1fr]">
                    <div>
                        <h3 class="text-sm font-semibold text-zinc-900 dark:text-white">Description</h3>
                        <p class="mt-1 text-xs leading-relaxed text-zinc-500">The full write-up shown on the public event page.</p>
                    </div>
                    <flux:field>
                        <x-trix-editor state-path="description" :value="$description" wire:key="event-desc-{{ $editingId ?? 'new' }}" />
                        <flux:error name="description" />
                    </flux:field>
                </div>
            </section>

            {{-- ---- Section: Media & Downloads ---- --}}
            <section class="px-6 py-6">
                <div class="grid gap-6 lg:grid-cols-[220px_1fr]">
                    <div>
                        <h3 class="text-sm font-semibold text-zinc-900 dark:text-white">Media &amp; Downloads</h3>
                        <p class="mt-1 text-xs leading-relaxed text-zinc-500">Poster, photo gallery and a downloadable brochure.</p>
                    </div>
                    <div class="space-y-6">
                        {{-- Poster / featured image --}}
                        <flux:field>
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

                        {{-- Gallery (images + PDFs) --}}
                        <flux:field>
                            <flux:label>Gallery</flux:label>
                            <flux:description>Add photos and PDFs — each file is added as soon as you upload or choose it. On the event page, images open in a lightbox and PDFs show as a view/download card.</flux:description>

                            @if($galleryIds)
                                <div class="mb-3 grid grid-cols-3 gap-3 sm:grid-cols-5">
                                    @foreach($galleryIds as $gid)
                                        @php $gItem = \App\Models\MediaItem::find($gid); @endphp
                                        @if($gItem)
                                            <div class="group relative overflow-hidden rounded-lg border border-zinc-200 dark:border-zinc-700">
                                                @if($gItem->type === 'image')
                                                    <img src="{{ $gItem->url() }}" alt="{{ $gItem->alt_text }}" class="aspect-square w-full object-cover" />
                                                @else
                                                    <div class="flex aspect-square w-full flex-col items-center justify-center gap-1 bg-zinc-50 p-2 text-center dark:bg-zinc-800">
                                                        <svg class="h-8 w-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                        </svg>
                                                        <span class="w-full truncate text-[10px] text-zinc-500">{{ $gItem->original_filename }}</span>
                                                    </div>
                                                @endif
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

                            <livewire:components.media-picker
                                wire:model="galleryPickerId"
                                disk="public"
                                type=""
                                folder="cms/events"
                                accept="image/*,application/pdf"
                                :key="'event-gallery-' . ($editingId ?? 'new') . '-' . count($galleryIds)"
                            />
                        </flux:field>

                        {{-- Brochure (PDF) --}}
                        <flux:field>
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
                    </div>
                </div>
            </section>

            {{-- ---- Section: Inquiry Channels ---- --}}
            <section class="px-6 py-6">
                <div class="grid gap-6 lg:grid-cols-[220px_1fr]">
                    <div>
                        <h3 class="text-sm font-semibold text-zinc-900 dark:text-white">Inquiry Channels</h3>
                        <p class="mt-1 text-xs leading-relaxed text-zinc-500">How visitors reach out from the public event page. Leave empty to use the default contact form.</p>
                    </div>
                    <flux:field>
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
                                <div class="rounded-xl border border-dashed border-zinc-200 px-4 py-6 text-center text-sm text-zinc-500 dark:border-zinc-700">
                                    No channels yet — the event will use the default contact form.
                                </div>
                            @endforelse
                        </div>

                        <div class="mt-3">
                            <flux:button type="button" wire:click="addInquiryChannel" icon="plus" size="sm" variant="ghost">Add channel</flux:button>
                        </div>
                    </flux:field>
                </div>
            </section>

            {{-- ---- Section: Event Resources ---- --}}
            <section class="px-6 py-6">
                <div class="grid gap-6 lg:grid-cols-[220px_1fr]">
                    <div>
                        <h3 class="text-sm font-semibold text-zinc-900 dark:text-white">Event Resources</h3>
                        <p class="mt-1 text-xs leading-relaxed text-zinc-500">Optional documents &amp; presentations from the event (PDF, Word, Excel, PowerPoint). Mark each as free to download or paid.</p>
                    </div>
                    <flux:field>
                        {{-- Existing / added resource rows --}}
                        <div class="space-y-4">
                            @forelse($resources as $i => $r)
                                <div class="rounded-xl border border-zinc-200 p-4 dark:border-zinc-700">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="flex-1 space-y-3">
                                            <div class="flex items-center gap-2 text-xs text-zinc-500">
                                                <flux:icon.document-text class="h-4 w-4 flex-none" />
                                                <span class="truncate">{{ $r['file_name'] ?? $r['file_path'] }}</span>
                                            </div>

                                            <flux:field>
                                                <flux:label>Title</flux:label>
                                                <flux:input wire:model="resources.{{ $i }}.title" placeholder="e.g. Keynote Slides" />
                                                <flux:error name="resources.{{ $i }}.title" />
                                            </flux:field>

                                            <div class="flex flex-wrap items-end gap-4">
                                                <flux:checkbox wire:model.live="resources.{{ $i }}.is_paid" label="Paid resource" />

                                                @if(!empty($r['is_paid']))
                                                    <flux:field class="w-32">
                                                        <flux:label>Price</flux:label>
                                                        <flux:input type="number" step="0.01" min="0" wire:model="resources.{{ $i }}.price" placeholder="0.00" />
                                                        <flux:error name="resources.{{ $i }}.price" />
                                                    </flux:field>
                                                    <flux:field class="w-28">
                                                        <flux:label>Currency</flux:label>
                                                        <flux:select wire:model="resources.{{ $i }}.currency">
                                                            <option value="USD">USD</option>
                                                            <option value="NGN">NGN</option>
                                                            <option value="KES">KES</option>
                                                        </flux:select>
                                                        <flux:error name="resources.{{ $i }}.currency" />
                                                    </flux:field>
                                                @endif
                                            </div>
                                        </div>
                                        <flux:button type="button" wire:click="removeResource({{ $i }})" variant="ghost" icon="trash" size="sm" class="text-red-500" />
                                    </div>
                                </div>
                            @empty
                                <div class="rounded-xl border border-dashed border-zinc-200 px-4 py-6 text-center text-sm text-zinc-500 dark:border-zinc-700">
                                    No resources yet — this section is optional.
                                </div>
                            @endforelse
                        </div>

                        {{-- Add-one picker (mirrors the gallery flow) --}}
                        <div class="mt-4 rounded-xl border border-zinc-200 p-4 dark:border-zinc-700">
                            <flux:label>Add a file (PDF, Word, Excel, PowerPoint)</flux:label>
                            <div class="mt-2 flex items-end gap-3">
                                <div class="flex-1">
                                    <livewire:components.media-picker
                                        wire:model="resourcePickerId"
                                        disk="public"
                                        type="document"
                                        folder="cms/events"
                                        accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx"
                                        :key="'event-resource-picker-' . ($editingId ?? 'new') . '-' . count($resources)"
                                    />
                                </div>
                                <flux:button type="button" wire:click="addResource" icon="plus" size="sm">Add resource</flux:button>
                            </div>
                            <flux:error name="resourcePickerId" />
                        </div>
                    </flux:field>
                </div>
            </section>

            {{-- ---- Sticky footer actions ---- --}}
            <div class="flex items-center justify-end gap-3 border-t border-zinc-200 bg-zinc-50/70 px-6 py-4 dark:border-zinc-700 dark:bg-zinc-800/50">
                <flux:button wire:click="cancel" variant="ghost">Cancel</flux:button>
                <flux:button type="submit" variant="primary">{{ $editingId ? 'Update Event' : 'Create Event' }}</flux:button>
            </div>
        </form>
    </div>
    @endif

    {{-- ===================== EVENTS LIST ===================== --}}
    <div class="overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-800/60">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">Event</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">Type</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">Status</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">Schedule</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">Venue</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">Registration</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 bg-white dark:divide-zinc-800 dark:bg-zinc-900">
                    @forelse($events as $event)
                    <tr class="transition hover:bg-zinc-50/70 dark:hover:bg-zinc-800/40">
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                @if($event->featured_image)
                                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($event->featured_image) }}"
                                        class="h-10 w-10 flex-shrink-0 rounded-lg object-cover ring-1 ring-zinc-200 dark:ring-zinc-700" alt="" />
                                @else
                                    <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-zinc-100 dark:bg-zinc-800">
                                        <svg class="h-4 w-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                        </svg>
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <span class="block font-medium text-zinc-900 dark:text-white">{{ $event->title }}</span>
                                    @if($event->organizer)
                                        <span class="block text-xs text-zinc-400">by {{ $event->organizer->name }}</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3.5 text-sm text-zinc-500">{{ ucwords(str_replace('-',' ',$event->type)) }}</td>
                        <td class="px-5 py-3.5">
                            @php $sc = match($event->status) { 'published'=>'bg-green-100 text-green-800','ongoing'=>'bg-blue-100 text-blue-800','completed'=>'bg-zinc-100 text-zinc-600','cancelled'=>'bg-red-100 text-red-800',default=>'bg-yellow-100 text-yellow-800' }; @endphp
                            <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $sc }}">{{ ucfirst($event->status) }}</span>
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="block text-sm text-zinc-700 dark:text-zinc-300">{{ $event->starts_at->format('d M Y') }}</span>
                            <span class="block text-xs text-zinc-400">{{ $event->starts_at->format('H:i') }}</span>
                        </td>
                        <td class="px-5 py-3.5 text-sm text-zinc-500">{{ $event->venue ?? '—' }}</td>
                        <td class="px-5 py-3.5">
                            @if($event->registration_enabled)
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                                    <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span> On
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-zinc-100 px-2.5 py-0.5 text-xs font-medium text-zinc-500 dark:bg-zinc-800">
                                    <span class="h-1.5 w-1.5 rounded-full bg-zinc-400"></span> Off
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-right">@can('events.edit')<flux:button size="sm" variant="ghost" icon="pencil-square" wire:click="edit({{ $event->id }})">Edit</flux:button>@endcan</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-16 text-center">
                            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-zinc-100 dark:bg-zinc-800">
                                <svg class="h-6 w-6 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <p class="mt-3 text-sm font-medium text-zinc-700 dark:text-zinc-300">No events yet</p>
                            <p class="mt-1 text-sm text-zinc-500">Create your first event to get started.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">{{ $events->links() }}</div>
</div>
