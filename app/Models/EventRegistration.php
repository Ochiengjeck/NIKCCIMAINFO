<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventRegistration extends Model
{
    protected $fillable = [
        'event_id', 'ticket_id', 'member_id', 'attendee_name',
        'attendee_email', 'ticket_number', 'checked_in_at', 'transaction_id',
    ];

    protected function casts(): array
    {
        return [
            'checked_in_at' => 'datetime',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(EventTicket::class, 'ticket_id');
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public static function generateTicketNumber(): string
    {
        return 'TKT-'.strtoupper(substr(md5(uniqid()), 0, 8));
    }
}
