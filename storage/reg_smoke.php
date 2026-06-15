<?php

use App\Models\Event;
use App\Models\EventRegistration;

$e = Event::first();
if (! $e) {
    echo "NO EVENTS\n";

    return;
}

$r = EventRegistration::create([
    'event_id' => $e->id,
    'attendee_name' => 'Smoke Test',
    'attendee_email' => 'smoke@test.com',
    'organisation' => 'Acme',
    'designation' => 'CEO',
    'whatsapp_number' => '+254700000000',
    'ooc11_engagement' => true,
    'ticket_number' => EventRegistration::generateTicketNumber(),
]);

$fresh = $r->fresh();
echo 'OK id='.$fresh->id
    .' ticket='.$fresh->ticket_number
    .' org='.$fresh->organisation
    .' desig='.$fresh->designation
    .' wa='.$fresh->whatsapp_number
    .' ooc11='.var_export($fresh->ooc11_engagement, true)."\n";

$r->delete();
echo "cleaned up\n";
