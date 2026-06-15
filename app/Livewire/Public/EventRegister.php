<?php

namespace App\Livewire\Public;

use App\Models\Event;
use App\Models\EventRegistration;
use Livewire\Component;

class EventRegister extends Component
{
    public Event $event;

    public string $attendee_name = '';

    public string $attendee_email = '';

    public string $organisation = '';

    public string $designation = '';

    public string $whatsapp_number = '';

    public bool $ooc11_engagement = false;

    public bool $submitted = false;

    public ?string $ticketNumber = null;

    protected $rules = [
        'attendee_name' => 'required|string|max:255',
        'attendee_email' => 'required|email|max:255',
        'organisation' => 'nullable|string|max:255',
        'designation' => 'nullable|string|max:255',
        'whatsapp_number' => 'nullable|string|max:255',
        'ooc11_engagement' => 'boolean',
    ];

    public function submit(): void
    {
        $this->validate();

        $registration = EventRegistration::create([
            'event_id' => $this->event->id,
            'attendee_name' => $this->attendee_name,
            'attendee_email' => $this->attendee_email,
            'organisation' => $this->organisation ?: null,
            'designation' => $this->designation ?: null,
            'whatsapp_number' => $this->whatsapp_number ?: null,
            'ooc11_engagement' => $this->ooc11_engagement,
            'ticket_number' => EventRegistration::generateTicketNumber(),
        ]);

        $this->ticketNumber = $registration->ticket_number;
        $this->reset(['attendee_name', 'attendee_email', 'organisation', 'designation', 'whatsapp_number', 'ooc11_engagement']);
        $this->submitted = true;
    }

    public function render()
    {
        return view('livewire.public.event-register');
    }
}
