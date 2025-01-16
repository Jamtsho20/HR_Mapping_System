<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EmployeeExport implements FromCollection, WithHeadings
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
        return User::filter($this->request, false)->get()->map(function ($employee) use (&$serialNo) {
            return [
                $serialNo++,
                $employee->username,
                $employee->name,
                $employee->empJob->department->name,
                $employee->empJob->section->name ?? '-',
                $employee->empJob->designation->name,
                $employee->empJob->gradeStep->name,
                $employee->empJob->office->name,
                $employee->date_of_appointment,
                $employee->contact_number,
                $employee->email,
                $employee->is_active ? 'Active' : 'Inactive',

            ];
        });
    }
    public function headings(): array
    {
        return [
            'Sl No',
            'Employee ID',
            'Employee Name',
            'Department',
            'Section',
            'Designation',
            'Grade',
            'Location',
            'DOJ',
            'Contact No',
            'Email',
            'Employee Status',


        ];
    }
}
