<?php

namespace App\Services;

use App\Models\Deal;
use App\Models\Event;
use App\Models\FinancialTransaction;
use App\Models\KpiSnapshot;
use App\Models\Member;
use App\Models\MembershipApplication;
use App\Models\Ntb;

class KpiCalculationService
{
    public function snapshot(?int $chapterId = null): void
    {
        $period = now()->format('Y-m');
        $metrics = $this->compute($chapterId);

        foreach ($metrics as $m) {
            KpiSnapshot::updateOrCreate(
                ['chapter_id' => $chapterId, 'metric_key' => $m['key'], 'period' => $period],
                ['pillar' => $m['pillar'], 'metric_label' => $m['label'], 'value' => $m['value'], 'recorded_at' => now()]
            );
        }
    }

    public function compute(?int $chapterId): array
    {
        $q = fn ($model) => $model::when($chapterId, fn ($q) => $q->where('chapter_id', $chapterId));

        return [
            [
                'key' => 'members_total',
                'pillar' => 'membership',
                'label' => 'Total Members',
                'value' => $q(Member::class)->count(),
            ],
            [
                'key' => 'members_active',
                'pillar' => 'membership',
                'label' => 'Active Members',
                'value' => $q(Member::class)->where('status', 'active')->count(),
            ],
            [
                'key' => 'applications_pending',
                'pillar' => 'membership',
                'label' => 'Pending Applications',
                'value' => $q(MembershipApplication::class)->whereIn('status', ['pending', 'under-review'])->count(),
            ],
            [
                'key' => 'revenue_paid',
                'pillar' => 'finance',
                'label' => 'Revenue Paid (NGN)',
                'value' => $q(FinancialTransaction::class)->where('status', 'paid')->where('currency', 'NGN')->sum('amount'),
            ],
            [
                'key' => 'deals_completed',
                'pillar' => 'trade',
                'label' => 'Deals Completed',
                'value' => $q(Deal::class)->where('stage', 'completed')->count(),
            ],
            [
                'key' => 'ntbs_unresolved',
                'pillar' => 'policy',
                'label' => 'Unresolved NTBs',
                'value' => $q(Ntb::class)->whereNotIn('status', ['resolved', 'closed'])->count(),
            ],
            [
                'key' => 'events_completed',
                'pillar' => 'events',
                'label' => 'Events Completed',
                'value' => $q(Event::class)->where('status', 'completed')->count(),
            ],
        ];
    }
}
