@props([
    'title',
    'description',
])

<div class="flex w-full flex-col gap-2 text-center">
    <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-500">Authentication</p>
    <flux:heading size="xl" class="!text-slate-900">{{ $title }}</flux:heading>
    <flux:subheading class="!text-slate-600">{{ $description }}</flux:subheading>
</div>
