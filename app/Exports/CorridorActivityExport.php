<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CorridorActivityExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        if (class_exists(\App\Models\Corridor::class)) {
            return \App\Models\Corridor::with(['chapter'])->latest()->get();
        }

        return collect([]);
    }

    public function headings(): array
    {
        return ['#', 'Name', 'Origin', 'Destination', 'Status', 'Created'];
    }
}
