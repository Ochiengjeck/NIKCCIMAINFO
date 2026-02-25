<x-layouts::website :title="'Leadership — NiKCCIMA'">

    {{-- Hero --}}
    <section class="relative overflow-hidden py-24 text-white">
        <div class="absolute inset-0 bg-gradient-to-br from-green-900 to-green-800">
            <svg class="absolute inset-0 h-full w-full opacity-10" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="dp" x="0" y="0" width="24" height="24" patternUnits="userSpaceOnUse">
                        <circle cx="2" cy="2" r="1.5" fill="white"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#dp)"/>
            </svg>
        </div>
        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <span class="mb-4 inline-flex rounded-full bg-red-600 px-4 py-1.5 text-xs font-semibold uppercase tracking-widest text-white">Team</span>
            <h1 class="font-serif text-4xl font-bold lg:text-6xl">Leadership</h1>
            <p class="mt-4 max-w-2xl text-lg text-green-200">The governing council and executive leadership driving NiKCCIMA's bilateral mandate.</p>
        </div>
    </section>

    {{-- Leadership Grid --}}
    <section class="py-20 bg-white dark:bg-zinc-950">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            @if($profiles->isEmpty())
                <div class="flex h-64 flex-col items-center justify-center rounded-2xl border border-dashed border-zinc-300 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900">
                    <svg class="mb-4 h-10 w-10 text-zinc-300 dark:text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">Leadership profiles coming soon.</p>
                </div>
            @else
                @php
                    $byChapter = $profiles->groupBy(fn($p) => $p->chapter?->name ?? 'Global Leadership');
                @endphp

                @foreach($byChapter as $chapterName => $chapterProfiles)
                    <div class="mb-16">
                        <h2 class="mb-8 border-b border-zinc-200 dark:border-zinc-800 pb-3 font-serif text-2xl font-bold text-zinc-900 dark:text-white">
                            {{ $chapterName }}
                        </h2>
                        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                            @foreach($chapterProfiles as $profile)
                                <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6 text-center transition hover:shadow-md">

                                    {{-- Photo --}}
                                    @if($profile->photoUrl())
                                        <img src="{{ $profile->photoUrl() }}"
                                             alt="{{ $profile->name }}"
                                             class="mx-auto mb-4 h-24 w-24 rounded-full object-cover ring-4 ring-green-100 dark:ring-green-900">
                                    @else
                                        @php
                                            $nameParts = explode(' ', trim($profile->name));
                                            $initials = strtoupper(substr($nameParts[0], 0, 1));
                                            if (count($nameParts) > 1) {
                                                $initials .= strtoupper(substr(end($nameParts), 0, 1));
                                            }
                                        @endphp
                                        <div class="mx-auto mb-4 flex h-24 w-24 items-center justify-center rounded-full bg-gradient-to-br from-green-600 to-green-800 ring-4 ring-green-100 dark:ring-green-900">
                                            <span class="text-2xl font-bold text-white">{{ $initials }}</span>
                                        </div>
                                    @endif

                                    {{-- Name & Position --}}
                                    <h3 class="font-semibold text-zinc-900 dark:text-white">{{ $profile->name }}</h3>
                                    <p class="mb-3 text-sm text-green-700 dark:text-green-400">{{ $profile->position }}</p>

                                    {{-- Bio --}}
                                    @if($profile->bio)
                                        <p class="text-xs text-zinc-500 dark:text-zinc-400 line-clamp-3">{{ $profile->bio }}</p>
                                    @endif

                                    {{-- Email --}}
                                    @if($profile->email)
                                        <a href="mailto:{{ $profile->email }}"
                                           class="mt-3 inline-block text-xs text-green-700 dark:text-green-400 hover:underline break-all">
                                            {{ $profile->email }}
                                        </a>
                                    @endif

                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @endif

        </div>
    </section>

</x-layouts::website>
