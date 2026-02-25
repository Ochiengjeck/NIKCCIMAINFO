<div>
    <div class="flex items-center justify-between mb-6">
        <div><flux:heading size="xl">Board Resolutions</flux:heading><flux:subheading>Create resolutions and track voting outcomes</flux:subheading></div>
        @can('governance.upload')<flux:button icon="plus" wire:click="$set('showForm',true)">New Resolution</flux:button>@endcan
    </div>
    @if(session('success'))<flux:callout variant="success" class="mb-4" dismissible>{{ session('success') }}</flux:callout>@endif
    @if($showForm)
    <div class="mb-6 rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
        <form wire:submit="save" class="grid grid-cols-1 gap-4">
            <flux:field><flux:label>Title</flux:label><flux:input wire:model="title" /><flux:error name="title" /></flux:field>
            <flux:field><flux:label>Resolution Text</flux:label><flux:textarea wire:model="resolution_text" rows="5" /><flux:error name="resolution_text" /></flux:field>
            <flux:field><flux:label>Voting Deadline</flux:label><flux:input type="date" wire:model="voting_deadline" /></flux:field>
            <div class="flex gap-3"><flux:button type="submit" variant="primary">Submit</flux:button><flux:button wire:click="$set('showForm',false)" variant="ghost">Cancel</flux:button></div>
        </form>
    </div>
    @endif
    <div class="space-y-4">
        @forelse($resolutions as $res)
        @php $tally = ['for'=>$res->votes->where('vote','for')->count(),'against'=>$res->votes->where('vote','against')->count(),'abstain'=>$res->votes->where('vote','abstain')->count()]; @endphp
        <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex items-start justify-between">
                <div>
                    <p class="font-medium text-zinc-900 dark:text-white">{{ $res->title }}</p>
                    <p class="text-xs text-zinc-500">Moved by {{ $res->movedBy?->name }} @if($res->voting_deadline) &bull; Deadline: {{ $res->voting_deadline->format('d M Y') }}@endif</p>
                </div>
                @php $sc = match($res->status){'passed'=>'bg-green-100 text-green-800','failed'=>'bg-red-100 text-red-800',default=>'bg-yellow-100 text-yellow-800'}; @endphp
                <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium {{ $sc }}">{{ ucwords(str_replace('-',' ',$res->status)) }}</span>
            </div>
            <p class="mt-3 text-sm text-zinc-700 dark:text-zinc-300 line-clamp-3">{{ $res->resolution_text }}</p>
            <div class="mt-3 flex gap-6 text-sm">
                <span class="text-green-600 font-medium">For: {{ $tally['for'] }}</span>
                <span class="text-red-600 font-medium">Against: {{ $tally['against'] }}</span>
                <span class="text-zinc-500">Abstain: {{ $tally['abstain'] }}</span>
            </div>
            @if($res->status === 'pending-vote')
            @can('governance.vote')
            <div class="mt-3 flex gap-2">
                <a href="{{ route('admin.governance.vote') }}" wire:navigate><flux:button size="sm" variant="primary">Cast Vote</flux:button></a>
            </div>
            @endcan
            @endif
        </div>
        @empty
        <p class="text-center text-zinc-500 py-8">No resolutions yet.</p>
        @endforelse
    </div>
    <div class="mt-4">{{ $resolutions->links() }}</div>
</div>
