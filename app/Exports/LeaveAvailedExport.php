<?php

namespace App\Exports;

use App\Models\LeaveApplication;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class LeaveAvailedExport implements FromCollection, WithHeadings
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
        return LeaveApplication::filter($this->request,false)->get()->map(function ($leaveReports) use (&$serialNo) {
            return [
                $serialNo++,
                $leaveReports->employee->name,
                $leaveReports->employee->empJob->designation->name,
                $leaveReports->employee->empJob->department->name,
                $leaveReports->leaveType->name,
                $leaveReports->employee->empJob->office->name,
                $leaveReports->from_date,
                $leaveReports->to_date,
            ];
        });
    }
    public function headings(): array
    {
        return [
            'Sl No',
            'Employee Name',
            'Designation',      
            'Department',
            'Leave Type',
            'Location',
            'From Date',
            'To Date',

        ];
    }
}
