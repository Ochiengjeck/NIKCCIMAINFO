<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EventAttendanceExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        if (class_exists(\App\Models\EventRegistration::class)) {
            return \App\Models\EventRegistration::with(['event', 'member'])->latest()->get();
        }

        return collect([]);
    }

    public function headings(): array
    {
        return ['#', 'Event', 'Member', 'Status', 'Registered At'];
    }
}
