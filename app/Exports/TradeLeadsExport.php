<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TradeLeadsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // TradeLead model will be available once Phase 3 is fully set up
        if (class_exists(\App\Models\TradeLead::class)) {
            return \App\Models\TradeLead::with(['chapter', 'sector'])->latest()->get();
        }

        return collect([]);
    }

    public function headings(): array
    {
        return ['#', 'Title', 'Sector', 'Chapter', 'Type', 'Status', 'Posted By', 'Date'];
    }
}
