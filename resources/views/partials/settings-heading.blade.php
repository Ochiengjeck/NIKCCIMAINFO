@php
    $user  = auth()->user();
    $photo = $user->profilePhotoUrl();
@endphp

<div class="mb-8">
    <div class="flex flex-wrap items-center gap-4">
        {{-- Avatar --}}
        @if ($photo)
            <img
                src="{{ $photo }}"
                alt="{{ $user->name }}"
                class="h-[72px] w-[72px] rounded-full object-cover ring-2 ring-zinc-700 shrink-0"
            />
        @else
            <div class="flex h-[72px] w-[72px] shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-zinc-700 to-zinc-800 ring-2 ring-zinc-700 text-xl font-semibold text-zinc-300">
                {{ $user->initials() }}
            </div>
        @endif

        {{-- Identity block --}}
        <div class="min-w-0">
            <div class="flex flex-wrap items-center gap-2">
                <h1 class="text-lg font-semibold text-zinc-100">{{ $user->name }}</h1>
                @if ($user->job_title)
                    <span class="text-[12px] text-zinc-500">·</span>
                    <span class="text-[13px] text-zinc-400">{{ $user->job_title }}</span>
                @endif
                @if ($user->chapter)
                    <span class="inline-flex items-center gap-1 rounded-full bg-green-900/40 px-2 py-0.5 text-[10px] font-medium text-green-300">
                        <span class="h-1.5 w-1.5 rounded-full bg-green-400"></span>
                        {{ $user->chapter->name }}
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 rounded-full bg-blue-900/30 px-2 py-0.5 text-[10px] font-medium text-blue-300">
                        <span class="h-1.5 w-1.5 rounded-full bg-blue-400"></span>
                        Global
                    </span>
                @endif
            </div>
            <p class="mt-0.5 text-[12px] text-zinc-500">
                {{ $user->email }}
                <span class="mx-1.5 text-zinc-700">·</span>
                Member since {{ $user->created_at->format('M Y') }}
            </p>
        </div>
    </div>

    <flux:separator variant="subtle" class="mt-6" />
</div>
