<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MembershipCategory extends Model
{
    /** Selectable groups when the corporate/individual toggle is on. */
    public const GROUPS = [
        'corporate' => 'Corporate',
        'individual' => 'Individual',
    ];

    protected $fillable = [
        'name', 'slug', 'description',
        'fee_usd', 'fee_ngn',
        'corporate_enabled', 'corporate_fee_usd', 'corporate_fee_ngn',
        'individual_enabled', 'individual_fee_usd', 'individual_fee_ngn',
        'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'fee_usd' => 'decimal:2',
            'fee_ngn' => 'decimal:2',
            'corporate_enabled' => 'boolean',
            'corporate_fee_usd' => 'decimal:2',
            'corporate_fee_ngn' => 'decimal:2',
            'individual_enabled' => 'boolean',
            'individual_fee_usd' => 'decimal:2',
            'individual_fee_ngn' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    /** Whether this category is offered to the given group (null/flat is always available). */
    public function availableForGroup(?string $group): bool
    {
        return match ($group) {
            'corporate' => (bool) $this->corporate_enabled,
            'individual' => (bool) $this->individual_enabled,
            default => true,
        };
    }

    /** USD fee for the given group (falls back to the flat fee). */
    public function feeUsd(?string $group = null): ?string
    {
        return match ($group) {
            'corporate' => $this->corporate_fee_usd,
            'individual' => $this->individual_fee_usd,
            default => $this->fee_usd,
        };
    }

    /** NGN fee for the given group (falls back to the flat fee). */
    public function feeNgn(?string $group = null): ?string
    {
        return match ($group) {
            'corporate' => $this->corporate_fee_ngn,
            'individual' => $this->individual_fee_ngn,
            default => $this->fee_ngn,
        };
    }

    public function isFree(?string $group = null): bool
    {
        $usd = (float) $this->feeUsd($group);
        $ngn = (float) $this->feeNgn($group);

        return $usd <= 0 && $ngn <= 0;
    }

    /** Human price label — "$250 (₦300,000)" / "$250" / "₦300,000" / "Free". */
    public function priceLabel(?string $group = null): string
    {
        if ($this->isFree($group)) {
            return 'Free';
        }

        $usd = (float) $this->feeUsd($group);
        $ngn = (float) $this->feeNgn($group);

        $parts = [];
        if ($usd > 0) {
            $parts[] = '$'.number_format($usd, 2);
        }
        if ($ngn > 0) {
            $parts[] = '₦'.number_format($ngn, 0);
        }

        // Show USD as the primary figure and NGN in parentheses as a secondary line.
        if (count($parts) === 2) {
            return $parts[0].' ('.$parts[1].')';
        }

        return $parts[0] ?? 'Free';
    }

    /**
     * USD-only price label for public-facing surfaces (website + application form).
     * "$250.00" / "Free" / "On request" (when no USD price has been set yet).
     */
    public function priceLabelUsd(?string $group = null): string
    {
        $usd = (float) $this->feeUsd($group);

        if ($usd > 0) {
            return '$'.number_format($usd, 2);
        }

        return $this->isFree($group) ? 'Free' : 'On request';
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
