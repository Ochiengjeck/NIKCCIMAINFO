{{--
    CMS-MANAGED SECTIONS — Admin → CMS → Pages → "Events & Missions"
    banner_image : Banner background (PLACEHOLDER UNTIL UPLOADED)
    hero_title   : Page heading
    hero_subtitle: Subheading
    intro_body   : Sidebar callout text (shown in right sidebar card)
--}}
<x-layouts::website :title="'Events &amp; Missions — NiKCCIMA'">

    {{-- ===================== HERO ===================== --}}
    <section class="relative overflow-hidden py-24 text-white lg:py-32">
        @if($page?->section('banner_image'))
            <div class="absolute inset-0">
                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($page->section('banner_image')) }}"
                     alt=""
                     class="h-full w-full object-cover"
                     aria-hidden="true">
                <div class="absolute inset-0 bg-zinc-950/70"></div>
            </div>
        @else
            <div class="absolute inset-0 bg-gradient-to-br from-zinc-900 via-zinc-800 to-crimson-950"></div>
        @endif

        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
            <span class="block text-xs font-bold uppercase tracking-widest text-brand-200 mb-3">Calendar</span>
            <h1 class="font-serif text-4xl font-bold leading-tight sm:text-5xl lg:text-6xl mb-4">
                {{ $page?->section('hero_title', 'Events &amp; Missions') }}
            </h1>
            <p class="max-w-2xl mx-auto text-lg text-zinc-300 sm:text-xl">
                {{ $page?->section('hero_subtitle', 'Join NiKCCIMA flagship events, bilateral trade missions, and sector forums driving measurable corridor outcomes.') }}
            </p>
        </div>
    </section>

    {{-- ===================== UPCOMING EVENTS ===================== --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4">

            <span class="block text-xs font-bold uppercase tracking-widest text-crimson-700 text-center mb-3">Events &amp; Missions</span>
            <h2 class="text-3xl lg:text-4xl font-bold font-serif text-zinc-900 text-center mb-4">Upcoming Events</h2>
            <div class="w-20 h-1.5 bg-brand-500 rounded-full mx-auto mb-14"></div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 max-w-7xl mx-auto">

                {{-- Left: event table (8 cols) --}}
                <div class="lg:col-span-8">

                    {{-- Header row --}}
                    <div class="flex items-center border-b-2 border-crimson-700 pb-4 mb-2 text-sm font-bold uppercase tracking-wide text-crimson-700 gap-4">
                        <span class="flex-1">Event</span>
                        <span class="w-40 hidden md:block">Venue</span>
                        <span class="w-28">Date</span>
                        <span class="w-24 hidden sm:block">Type</span>
                    </div>

                    @forelse($upcoming as $event)
                        <div class="flex items-start border-b border-zinc-200 py-4 gap-4 group hover:bg-zinc-50 transition-colors">
                            @if($event->featured_image)
                                <a href="{{ route('events.show', $event->id) }}" class="hidden sm:block flex-shrink-0">
                                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($event->featured_image) }}"
                                         alt="" class="h-14 w-20 rounded-lg object-cover" />
                                </a>
                            @endif
                            <div class="flex-1">
                                <a href="{{ route('events.show', $event->id) }}" class="font-semibold text-zinc-900 group-hover:text-crimson-700 transition-colors text-[15px]">{{ $event->title }}</a>
                                @if($event->description)
                                    <p class="text-sm text-zinc-500 mt-1 line-clamp-1">{{ Str::limit($event->description, 80) }}</p>
                                @endif
                            </div>
                            <span class="w-40 text-zinc-600 text-sm hidden md:block pt-0.5">{{ $event->venue ?? 'TBC' }}</span>
                            <span class="w-28 text-zinc-600 text-sm pt-0.5">{{ $event->starts_at->format('d/m/Y') }}</span>
                            <span class="w-24 pt-0.5 hidden sm:block">
                                <span class="inline-block text-xs px-2 py-0.5 rounded-full {{ in_array(strtolower($event->type ?? ''), ['flagship','summit']) ? 'bg-crimson-100 text-crimson-700' : 'bg-brand-100 text-brand-700' }}">
                                    {{ ucfirst($event->type ?? 'Event') }}
                                </span>
                            </span>
                        </div>
                    @empty
                        <div class="py-16 text-center text-zinc-500">No upcoming events scheduled. Please check back soon.</div>
                    @endforelse

                    <div class="mt-6">{{ $upcoming->links() }}</div>
                </div>

                {{-- Right: sidebar (4 cols) --}}
                <div class="lg:col-span-4">
                    <div class="rounded bg-zinc-50 p-8 shadow-[0_0_15px_rgba(0,0,0,0.1)] sticky top-24">
                        <h4 class="text-xl font-bold font-serif text-zinc-900 mb-4 leading-snug">{{ $page?->section('intro_body', 'Get in touch with us for any queries about our events and missions.') }}</h4>
                        <p class="text-[15px] leading-[26px] text-zinc-600 mb-8">Trade missions, bilateral summits, B2B matchmaking sessions, and AfCFTA corridor events.</p>
                        <a href="{{ route('contact') }}" class="inline-block bg-crimson-700 text-white px-8 py-3 rounded text-sm font-medium hover:opacity-90 transition-all">Contact Us</a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ===================== PAST EVENTS ===================== --}}
    @if($past->isNotEmpty())
        <section class="py-20 bg-zinc-50">
            <div class="max-w-7xl mx-auto px-4">

                <span class="block text-xs font-bold uppercase tracking-widest text-crimson-700 text-center mb-3">Past Events</span>
                <div class="w-20 h-1.5 bg-brand-500 rounded-full mx-auto mb-14"></div>

                {{-- Header row --}}
                <div class="flex items-center border-b-2 border-zinc-300 pb-4 mb-2 text-sm font-bold uppercase tracking-wide text-zinc-500 gap-4">
                    <span class="flex-1">Event</span>
                    <span class="w-40 hidden md:block">Venue</span>
                    <span class="w-28">Date</span>
                    <span class="w-24 hidden sm:block">Type</span>
                </div>

                @foreach($past as $event)
                    <div class="flex items-start border-b border-zinc-200 py-4 gap-4">
                        @if($event->featured_image)
                            <a href="{{ route('events.show', $event->id) }}" class="hidden sm:block flex-shrink-0">
                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($event->featured_image) }}"
                                     alt="" class="h-14 w-20 rounded-lg object-cover opacity-90" />
                            </a>
                        @endif
                        <div class="flex-1">
                            <a href="{{ route('events.show', $event->id) }}" class="font-semibold text-zinc-600 hover:text-zinc-800 transition-colors text-[15px]">{{ $event->title }}</a>
                            @if($event->description)
                                <p class="text-sm text-zinc-400 mt-1 line-clamp-1">{{ Str::limit($event->description, 80) }}</p>
                            @endif
                        </div>
                        <span class="w-40 text-zinc-500 text-sm hidden md:block pt-0.5">{{ $event->venue ?? 'TBC' }}</span>
                        <span class="w-28 text-zinc-500 text-sm pt-0.5">{{ $event->starts_at->format('d/m/Y') }}</span>
                        <span class="w-24 pt-0.5 hidden sm:block">
                            <span class="inline-block text-xs px-2 py-0.5 rounded-full bg-zinc-200 text-zinc-600">
                                {{ ucfirst($event->type ?? 'Event') }}
                            </span>
                        </span>
                    </div>
                @endforeach

            </div>
        </section>
    @endif

</x-layouts::website>
