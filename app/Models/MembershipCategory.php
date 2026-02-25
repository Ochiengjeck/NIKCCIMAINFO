<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MembershipCategory extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'fee_ngn', 'fee_kes', 'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'fee_ngn' => 'decimal:2',
            'fee_kes' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function members(): HasMany
    {
        return $this->hasMany(Member::class, 'category_id');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(MembershipApplication::class, 'category_id');
    }
}
