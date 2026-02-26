@props([
    'status',
])

@if ($status)
    <div {{ $attributes->merge(['class' => 'rounded-xl border border-green-500/30 bg-green-500/10 px-4 py-3 text-sm font-medium text-green-300']) }}>
        {{ $status }}
    </div>
@endif
