<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventResource extends Model
{
    protected $fillable = [
        'event_id', 'title', 'file_path', 'file_name', 'mime_type', 'size',
        'is_paid', 'price', 'currency', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_paid' => 'boolean',
            'price' => 'decimal:2',
            'size' => 'integer',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Human-friendly price label for the public listing — "Free" for
     * complimentary resources, otherwise "<CURRENCY> <amount>".
     */
    public function priceLabel(): string
    {
        if (! $this->is_paid) {
            return 'Free';
        }

        if ($this->price === null) {
            return 'Paid';
        }

        return $this->currency.' '.number_format((float) $this->price, 2);
    }

    /**
     * Format the file size for display (delegates to a simple B/KB/MB scale).
     */
    public function humanSize(): string
    {
        $bytes = (int) $this->size;

        if ($bytes <= 0) {
            return '';
        }

        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 1).' MB';
        }

        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 0).' KB';
        }

        return $bytes.' B';
    }
}
