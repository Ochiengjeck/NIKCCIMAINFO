<?php

namespace App\Livewire\Public;

use App\Models\ContactInquiry;
use Livewire\Component;

class ContactForm extends Component
{
    public string $name = '';

    public string $email = '';

    public string $subject = '';

    public string $message = '';

    public string $chapter = 'general';

    public bool $submitted = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'subject' => 'required|string|max:255',
        'message' => 'required|string|min:20',
        'chapter' => 'required|in:nigeria,kenya,general',
    ];

    public function submit(): void
    {
        $this->validate();

        ContactInquiry::create([
            'name' => $this->name,
            'email' => $this->email,
            'subject' => $this->subject,
            'message' => $this->message,
            'chapter' => $this->chapter,
            'status' => 'new',
        ]);

        $this->reset(['name', 'email', 'subject', 'message', 'chapter']);
        $this->chapter = 'general';
        $this->submitted = true;
    }

    public function render()
    {
        return view('livewire.public.contact-form');
    }
}
