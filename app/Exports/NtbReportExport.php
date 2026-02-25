<?php

namespace App\Exports;

use App\Models\Ntb;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class NtbReportExport implements FromQuery, WithHeadings
{
    public function query()
    {
        return Ntb::with(['chapter'])->latest();
    }

    public function headings(): array
    {
        return ['#', 'Title', 'Category', 'Chapter', 'Status', 'Reported Date'];
    }
}
