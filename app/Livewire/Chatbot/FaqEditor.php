<?php

namespace App\Livewire\Chatbot;

use App\Models\ChatbotFaq;
use Livewire\Component;
use Livewire\WithPagination;

class FaqEditor extends Component
{
    use WithPagination;

    public bool $showForm = false;

    public ?int $editingId = null;

    public string $question = '';

    public string $answer = '';

    public string $category = '';

    public bool $isActive = true;

    public int $sortOrder = 0;

    public function mount(): void
    {
        $this->authorize('chatbot.configure');
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $faq = ChatbotFaq::findOrFail($id);
        $this->editingId = $id;
        $this->question = $faq->question;
        $this->answer = $faq->answer;
        $this->category = $faq->category ?? '';
        $this->isActive = $faq->is_active;
        $this->sortOrder = $faq->sort_order;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
            'category' => 'nullable|string|max:100',
            'sortOrder' => 'integer|min:0',
        ]);

        $data = [
            'question' => $this->question,
            'answer' => $this->answer,
            'category' => $this->category ?: null,
            'is_active' => $this->isActive,
            'sort_order' => $this->sortOrder,
        ];

        if ($this->editingId) {
            ChatbotFaq::findOrFail($this->editingId)->update($data);
            session()->flash('success', 'FAQ updated.');
        } else {
            ChatbotFaq::create($data);
            session()->flash('success', 'FAQ created.');
        }

        $this->resetForm();
        $this->showForm = false;
    }

    public function toggleActive(int $id): void
    {
        $faq = ChatbotFaq::findOrFail($id);
        $faq->update(['is_active' => ! $faq->is_active]);
    }

    public function delete(int $id): void
    {
        ChatbotFaq::findOrFail($id)->delete();
        session()->flash('success', 'FAQ deleted.');
    }

    protected function resetForm(): void
    {
        $this->editingId = null;
        $this->question = '';
        $this->answer = '';
        $this->category = '';
        $this->isActive = true;
        $this->sortOrder = 0;
    }

    public function render()
    {
        $faqs = ChatbotFaq::orderBy('sort_order')->paginate(20);

        return view('livewire.chatbot.faq-editor', ['faqs' => $faqs])
            ->layout('layouts.admin');
    }
}
