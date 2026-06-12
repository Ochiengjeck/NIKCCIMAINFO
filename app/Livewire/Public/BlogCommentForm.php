<?php

namespace App\Livewire\Public;

use App\Models\BlogComment;
use Livewire\Component;

class BlogCommentForm extends Component
{
    public int $postId;

    public string $name = '';

    public string $email = '';

    public string $body = '';

    public bool $submitted = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'body' => 'required|string|min:5|max:2000',
    ];

    public function mount(int $postId): void
    {
        $this->postId = $postId;
    }

    public function submit(): void
    {
        $this->validate();

        BlogComment::create([
            'blog_post_id' => $this->postId,
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
        return view('livewire.public.blog-comment-form');
    }
}
