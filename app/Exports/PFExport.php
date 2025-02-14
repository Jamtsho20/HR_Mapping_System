<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PFExport implements FromCollection, WithHeadings
{
    protected $pfDeductionsWithPF;

    public function __construct($pfDeductionsWithPF)
    {
        $this->pfDeductionsWithPF = $pfDeductionsWithPF;
    }

    /**
     * Return a collection of data for export.
     */
    public function collection()
    {
        return collect($this->pfDeductionsWithPF->map(function ($pf) {
            return [
                $pf['employee_name'],
                $pf['pf_number'],
                $pf['CID'] ?? '-',
                $pf['basic_pay'] ?? '-',
                $pf['details']['deductions']['PF Contr'] ?? 0,
                $pf['employer_pf_amount'] ?? 0,
                $pf['total'] ?? 0,
            ];
        }));
    }

    /**
     * Return the headings for the Excel sheet.
     */
    public function headings(): array
    {
        return [
            'Employee Name',
            'PF Number',
            'CID',
            'Basic Pay',
            'Member Contribution',
            'Employer COntribution',
            'Total',
        ];
    }
}
