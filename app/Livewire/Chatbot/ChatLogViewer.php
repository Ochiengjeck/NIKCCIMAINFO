<?php

namespace App\Livewire\Chatbot;

use App\Models\ChatLog;
use Livewire\Component;
use Livewire\WithPagination;

class ChatLogViewer extends Component
{
    use WithPagination;

    public ?int $expandedId = null;

    public function mount(): void
    {
        $this->authorize('chatbot.view');
    }

    public function expand(int $id): void
    {
        $this->expandedId = $this->expandedId === $id ? null : $id;
    }

    public function render()
    {
        $logs = ChatLog::with('escalatedTo')
            ->latest()
            ->paginate(20);

        return view('livewire.chatbot.chat-log-viewer', ['logs' => $logs])
            ->layout('layouts.admin');
    }
}
