<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MembershipCategory extends Model
{
    public const TYPES = [
        'corporate' => 'Corporate',
        'individual' => 'Individual',
    ];

    protected $fillable = [
        'name', 'slug', 'member_type', 'description', 'fee_ngn', 'fee_kes', 'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'fee_ngn' => 'decimal:2',
            'fee_kes' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    /** Human label combining type and tier, e.g. "Corporate — Gold". */
    public function displayName(): string
    {
        $type = self::TYPES[$this->member_type] ?? null;

        return $type ? "{$type} — {$this->name}" : $this->name;
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('member_type', $type);
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
