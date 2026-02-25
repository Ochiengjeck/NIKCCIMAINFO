<?php

namespace App\Livewire\Public;

use App\Models\ChatbotFaq;
use App\Models\ChatLog;
use Illuminate\Support\Str;
use Livewire\Component;

class ChatWidget extends Component
{
    public bool $open = false;

    public string $input = '';

    public string $sessionId = '';

    public array $messages = [];

    public function mount(): void
    {
        $this->sessionId = (string) Str::uuid();
        $this->messages[] = [
            'role' => 'bot',
            'text' => 'Hi! I\'m the NiKCCIMA Assistant. Ask me about membership, trade opportunities, or our services.',
            'time' => now()->format('H:i'),
        ];
    }

    public function toggle(): void
    {
        $this->open = ! $this->open;
    }

    public function send(): void
    {
        $userInput = trim($this->input);
        if ($userInput === '') {
            return;
        }

        $this->messages[] = [
            'role' => 'user',
            'text' => $userInput,
            'time' => now()->format('H:i'),
        ];

        $this->input = '';

        $reply = $this->matchFaq($userInput);

        $this->messages[] = [
            'role' => 'bot',
            'text' => $reply,
            'time' => now()->format('H:i'),
        ];

        $this->saveLog();
    }

    protected function matchFaq(string $query): string
    {
        $faqs = ChatbotFaq::where('is_active', true)->orderBy('sort_order')->get();
        $best = null;
        $bestScore = 0;

        foreach ($faqs as $faq) {
            // Check for keyword presence first
            if (stripos($faq->question, $query) !== false || stripos($query, substr($faq->question, 0, 20)) !== false) {
                return $faq->answer;
            }

            // Use similar_text for fuzzy matching
            similar_text(strtolower($query), strtolower($faq->question), $pct);
            if ($pct > $bestScore) {
                $bestScore = $pct;
                $best = $faq;
            }
        }

        if ($best && $bestScore >= 40) {
            return $best->answer;
        }

        return 'I\'m not sure about that. For detailed assistance, please email us at trade@nikccima.org or use our Contact page.';
    }

    protected function saveLog(): void
    {
        $log = ChatLog::firstOrNew(['session_id' => $this->sessionId]);
        $log->visitor_ip = request()->ip();
        $log->messages = $this->messages;
        $log->save();
    }

    public function render()
    {
        return view('livewire.public.chat-widget');
    }
}
