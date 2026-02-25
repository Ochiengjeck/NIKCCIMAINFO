<?php

namespace App\Exports;

use App\Models\FinancialTransaction;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FinancialStatementExport implements FromQuery, WithHeadings
{
    public function query()
    {
        return FinancialTransaction::with(['chapter', 'member'])->latest();
    }

    public function headings(): array
    {
        return ['#', 'Member', 'Type', 'Amount', 'Currency', 'Status', 'Reference', 'Date'];
    }
}
