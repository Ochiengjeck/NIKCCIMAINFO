@props([
    'title',
    'description',
])

<div class="flex w-full flex-col gap-1.5 text-center">
    <p class="text-[10px] font-semibold uppercase tracking-[0.22em] text-green-400/70">Member Access</p>
    <h2 class="text-2xl font-bold text-white" style="font-family: 'Playfair Display', serif;">{{ $title }}</h2>
    <p class="text-sm leading-relaxed text-zinc-400">{{ $description }}</p>
</div>
