<?php

namespace App\Exports;

use App\Models\Member;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MembershipStatisticsExport implements FromQuery, WithHeadings
{
    public function query()
    {
        return Member::with(['chapter', 'category'])->latest();
    }

    public function headings(): array
    {
        return ['#', 'Name', 'Membership No', 'Category', 'Chapter', 'Status', 'Joined', 'Expires'];
    }
}
