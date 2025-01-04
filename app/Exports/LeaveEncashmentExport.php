<?php

namespace App\Exports;

use App\Models\LeaveEncashmentApplication;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LeaveEncashmentExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $request;
    public function __construct($request)
    {
        $this->request = $request;
    }
    public function collection()
    {
        $serialNo = 1;

        return LeaveEncashmentApplication::filter($this->request)
            ->where('status', 3)
            ->with('employeeLeave')
            ->get()->map(function ($leaveEncashment) use (&$serialNo) {
                return [

                    $serialNo++,
                    $leaveEncashment->employee->username,
                    $leaveEncashment->employee->name,
                    $leaveEncashment->employee->empJob->designation->name,
                    $leaveEncashment->employee->empJob->department->name,
                    $leaveEncashment->employee->empJob->office->name,
                    $leaveEncashment->leave_applied_for_encashment,
                    $leaveEncashment->employeeLeave->closing_balance,
                    $leaveEncashment->amount,
                ];
            });
    }


    public function headings(): array
    {
        return [
            'Serial No',
            'Code',
            'Employee Name',
            'Designation',
            'Department',
            'Location',
            'Leave Encashed',
            'EL closing Balance',
            'Basic Pay'
        ];
    }
}
