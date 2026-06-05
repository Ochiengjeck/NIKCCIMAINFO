<?php

namespace App\Models;

use App\Concerns\ChapterScoped;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Invoice extends Model
{
    use ChapterScoped, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    protected $fillable = [
        'chapter_id', 'member_id', 'application_id', 'currency', 'invoice_number', 'due_date',
        'line_items', 'subtotal', 'tax', 'total', 'status',
        'sent_at', 'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'line_items' => 'array',
            'sent_at' => 'datetime',
            'paid_at' => 'datetime',
            'due_date' => 'date',
            'subtotal' => 'decimal:2',
            'tax' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(MembershipApplication::class, 'application_id');
    }

    public static function generateInvoiceNumber(): string
    {
        return 'INV-'.date('Y').'-'.str_pad(static::count() + 1, 5, '0', STR_PAD_LEFT);
    }
}
