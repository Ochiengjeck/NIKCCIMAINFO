<?php

namespace App\Livewire\Reports;

use App\Exports\CorridorActivityExport;
use App\Exports\EventAttendanceExport;
use App\Exports\FinancialStatementExport;
use App\Exports\MembershipStatisticsExport;
use App\Exports\NtbReportExport;
use App\Exports\TradeLeadsExport;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class ReportGenerator extends Component
{
    public string $reportType = 'membership';

    public string $format = 'xlsx';

    public string $dateFrom = '';

    public string $dateTo = '';

    public function mount(): void
    {
        $this->authorize('finance.export');
    }

    public function generate(): mixed
    {
        $filename = $this->reportType.'-report-'.now()->format('Y-m-d').'.'.$this->format;

        $export = match ($this->reportType) {
            'membership' => new MembershipStatisticsExport,
            'financial' => new FinancialStatementExport,
            'trade-leads' => new TradeLeadsExport,
            'ntb' => new NtbReportExport,
            'corridors' => new CorridorActivityExport,
            'events' => new EventAttendanceExport,
            default => new MembershipStatisticsExport,
        };

        return Excel::download($export, $filename);
    }

    public function render()
    {
        return view('livewire.reports.report-generator')
            ->layout('layouts.admin');
    }
}
