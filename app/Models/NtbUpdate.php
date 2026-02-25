<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NtbUpdate extends Model
{
    protected $fillable = ['ntb_id', 'user_id', 'note', 'status_changed_to'];

    public function ntb(): BelongsTo
    {
        return $this->belongsTo(Ntb::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
