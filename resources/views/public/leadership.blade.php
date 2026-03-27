<x-layouts::website :title="'Leadership — NiKCCIMA'">

    {{-- Hero --}}
    <section class="relative overflow-hidden py-32 text-white">
        <div class="absolute inset-0 bg-gradient-to-br from-zinc-900 via-zinc-800 to-crimson-950">
            <div class="absolute inset-0 bg-zinc-950/60"></div>
        </div>
        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="font-serif text-4xl font-bold lg:text-6xl mb-4">Our Leadership Team</h1>
            <p class="max-w-2xl mx-auto text-lg text-zinc-300">Meet the people driving the Nigeria-Kenya trade corridor.</p>
        </div>
    </section>

    {{-- Leadership Grid --}}
    <section class="py-20 bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            {{-- Section header --}}
            <span class="block text-xs font-bold uppercase tracking-widest text-crimson-700 text-center mb-3">Our Team</span>
            <h2 class="text-3xl lg:text-4xl font-bold font-serif text-zinc-900 text-center mb-4">Leadership Profiles</h2>
            <div class="w-20 h-1.5 bg-brand-500 rounded-full mx-auto mb-14"></div>

            @if($profiles->isEmpty())
                <div class="flex h-64 flex-col items-center justify-center rounded-2xl border border-dashed border-zinc-300 bg-zinc-50">
                    <svg class="mb-4 h-10 w-10 text-zinc-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <p class="text-sm text-zinc-500">Leadership profiles coming soon.</p>
                </div>
            @else
                @php
                    $byChapter = $profiles->groupBy(fn($p) => $p->chapter?->name ?? 'Global Leadership');
                @endphp

                @foreach($byChapter as $chapterName => $members)
                    <h3 class="text-xl font-bold font-serif text-zinc-900 border-b-2 border-crimson-700 pb-3 mb-8 mt-12 first:mt-0">{{ $chapterName }}</h3>

                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                        @foreach($members as $profile)
                            <div class="rounded bg-white p-10 shadow-[0_0_15px_rgba(0,0,0,0.1)] text-center hover:shadow-lg transition-all duration-300">

                                {{-- Photo or avatar --}}
                                @if($profile->photoUrl())
                                    <div class="flex justify-center mb-4">
                                        <img src="{{ $profile->photoUrl() }}" alt="{{ $profile->name }}" class="h-32 w-32 rounded-full object-cover">
                                    </div>
                                @else
                                    <div class="flex justify-center mb-4">
                                        <div class="h-32 w-32 rounded-full bg-gradient-to-br from-brand-600 to-brand-800 flex items-center justify-center">
                                            <span class="text-2xl font-bold text-white font-serif">
                                                {{ strtoupper(substr($profile->name, 0, 1)) }}{{ strtoupper(substr(strrchr($profile->name, ' ') ?: ' ', 1, 1)) }}
                                            </span>
                                        </div>
                                    </div>
                                @endif

                                <h4 class="text-lg font-bold font-serif text-zinc-900 mb-1">{{ $profile->name }}</h4>
                                <span class="block text-sm font-medium text-zinc-600 mt-1 mb-3">{{ $profile->position }}</span>

                                @if($profile->email)
                                    <a href="mailto:{{ $profile->email }}" class="text-sm text-brand-600 hover:text-crimson-700 transition-colors break-all">{{ $profile->email }}</a>
                                @endif

                            </div>
                        @endforeach
                    </div>
                @endforeach
            @endif

        </div>
    </section>

    {{-- CTA Strip --}}
    <section class="bg-crimson-700 py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl lg:text-4xl font-bold font-serif text-white mb-4">Join Our Network</h2>
            <div class="w-20 h-1.5 bg-white/50 rounded-full mx-auto mb-10"></div>
            <a href="{{ route('membership.apply') }}" class="inline-block bg-white text-crimson-700 px-8 py-3 rounded text-sm font-medium hover:opacity-90 transition-all">
                Become a Member
            </a>
        </div>
    </section>

</x-layouts::website>
