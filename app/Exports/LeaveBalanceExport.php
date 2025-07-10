<?php

namespace App\Exports;

use App\Models\EmployeeLeave;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LeaveBalanceExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $request;

    // Constructor to inject the Request
    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $serialNo = 1;

        // Access the request data to apply filters
        return EmployeeLeave::filter($this->request)->get()->map(function ($leaveBalance) use (&$serialNo) {
            return [
                $serialNo++,
                $leaveBalance->employee->username ?? '-',
                $leaveBalance->employee->name ?? '-',
                $leaveBalance->employee->empJob->designation->name ?? '-',
                $leaveBalance->employee->empJob->department->name ?? '-',
                $leaveBalance->employee->empJob->office->name ?? '-',
                $leaveBalance->leaveType->name ?? '-',
                $leaveBalance->opening_balance ?? '-',
                $leaveBalance->current_entitlement ?? '-',
                $leaveBalance->leaves_availed ?? '-',
                $leaveBalance->closing_balance ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Sl No',
            'Code',
            'Employee Name',
            'Designation',
            'Department',
            'Location',
            'Leave Type',
            'Opening Bal',
            'Current Entitlement',
            'Leaves Availed.',
            'Closing Balance'
        ];
    }
}
