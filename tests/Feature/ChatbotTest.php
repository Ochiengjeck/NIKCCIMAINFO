<?php

namespace Tests\Feature;

use App\Livewire\Public\ChatWidget;
use Database\Seeders\ChatbotFaqSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ChatbotTest extends TestCase
{
    use RefreshDatabase;

    private const FALLBACK = 'I\'m not sure about that. For detailed assistance, please email us at trade@nikccima.org or use our Contact page.';

    /** The bot's reply to a message is the last message in the thread. */
    private function ask(string $message): string
    {
        $component = Livewire::test(ChatWidget::class)
            ->set('input', $message)
            ->call('send');

        $messages = $component->get('messages');

        return end($messages)['text'];
    }

    public function test_seeded_faqs_cover_common_questions(): void
    {
        $this->seed(ChatbotFaqSeeder::class);

        // Representative phrasings a real visitor would type, across every topic.
        $queries = [
            'how do I become a member',
            'how much does membership cost',
            'what are the membership categories',
            'how do I pay my membership fee',
            'how do I report a trade barrier',
            'how can I export to kenya',
            'what events do you have',
            'how do I contact you',
            'what is afcfta',
            'where can I read your news',
            'how do I find trade opportunities',
            'i forgot my password',
        ];

        foreach ($queries as $query) {
            $reply = $this->ask($query);
            $this->assertNotSame(self::FALLBACK, $reply, "Chatbot fell back on: {$query}");
            $this->assertNotEmpty($reply);
        }
    }

    public function test_unknown_question_returns_fallback(): void
    {
        $this->seed(ChatbotFaqSeeder::class);

        // A short, dissimilar string scores below the matcher's 40% threshold.
        $reply = $this->ask('zxqw vblt');

        $this->assertSame(self::FALLBACK, $reply);
    }

    public function test_widget_greets_on_open(): void
    {
        $messages = Livewire::test(ChatWidget::class)->get('messages');

        $this->assertNotEmpty($messages);
        $this->assertSame('bot', $messages[0]['role']);
    }
}
