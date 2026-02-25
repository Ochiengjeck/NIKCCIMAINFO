<?php

namespace App\Console\Commands;

use App\Models\Chapter;
use App\Services\KpiCalculationService;
use Illuminate\Console\Command;

class KpiSnapshotCommand extends Command
{
    protected $signature = 'kpi:snapshot';

    protected $description = 'Record KPI snapshots for all chapters';

    public function handle(KpiCalculationService $svc): int
    {
        $svc->snapshot(null); // global

        Chapter::where('is_active', true)->each(fn ($c) => $svc->snapshot($c->id));

        $this->info('KPI snapshots recorded.');

        return self::SUCCESS;
    }
}
