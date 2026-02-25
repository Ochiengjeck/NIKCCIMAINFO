<div>
    <flux:heading size="xl" class="mb-1">Voting Booth</flux:heading>
    <flux:subheading class="mb-6">Cast your vote on pending board resolutions</flux:subheading>
    @if(session('success'))<flux:callout variant="success" class="mb-4" dismissible>{{ session('success') }}</flux:callout>@endif
    <div class="space-y-4">
        @forelse($resolutions as $res)
        @php $myVote = $res->votes->first(); @endphp
        <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:heading size="lg" class="mb-2">{{ $res->title }}</flux:heading>
            @if($res->voting_deadline)<p class="text-xs text-zinc-500 mb-3">Deadline: {{ $res->voting_deadline->format('d M Y') }}</p>@endif
            <p class="text-sm text-zinc-700 dark:text-zinc-300 mb-4">{{ $res->resolution_text }}</p>
            @if($myVote)
            <flux:callout variant="success">Your vote: <strong>{{ ucfirst($myVote->vote) }}</strong> ({{ $myVote->voted_at->format('d M Y H:i') }})</flux:callout>
            <div class="mt-3 flex gap-2">
                @foreach(['for','against','abstain'] as $option)
                <flux:button size="sm" wire:click="vote({{ $res->id }},'{{ $option }}')" variant="{{ $myVote->vote === $option ? 'primary' : 'ghost' }}">{{ ucfirst($option) }}</flux:button>
                @endforeach
            </div>
            @else
            <div class="flex gap-2">
                <flux:button wire:click="vote({{ $res->id }},'for')" variant="primary">Vote For</flux:button>
                <flux:button wire:click="vote({{ $res->id }},'against')" variant="danger">Vote Against</flux:button>
                <flux:button wire:click="vote({{ $res->id }},'abstain')" variant="ghost">Abstain</flux:button>
            </div>
            @endif
        </div>
        @empty
        <div class="text-center py-12">
            <flux:heading>No pending resolutions</flux:heading>
            <flux:subheading>All resolutions have been voted on or there are none to vote on.</flux:subheading>
        </div>
        @endforelse
    </div>
</div>
