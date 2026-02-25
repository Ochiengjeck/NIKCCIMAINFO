<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatbotFaq extends Model
{
    protected $fillable = [
        'question',
        'answer',
        'category',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
