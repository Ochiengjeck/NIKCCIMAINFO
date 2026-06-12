<?php

namespace App\Livewire\Public;

use App\Models\NewsComment;
use Livewire\Component;

class NewsCommentForm extends Component
{
    public int $articleId;

    public string $name = '';

    public string $email = '';

    public string $body = '';

    public bool $submitted = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'body' => 'required|string|min:5|max:2000',
    ];

    public function mount(int $articleId): void
    {
        $this->articleId = $articleId;
    }

    public function submit(): void
    {
        $this->validate();

        NewsComment::create([
            'news_article_id' => $this->articleId,
            'author_name' => $this->name,
            'author_email' => $this->email,
            'body' => $this->body,
            'status' => 'pending',
            'ip_address' => request()->ip(),
        ]);

        $this->reset(['name', 'email', 'body']);
        $this->submitted = true;
    }

    public function render()
    {
        return view('livewire.public.news-comment-form');
    }
}
