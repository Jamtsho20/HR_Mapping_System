<?php

namespace App\Exports;

use App\Models\ExpenseApplication;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExpenseExport implements FromCollection, WithHeadings
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

        $statusClasses = [
            -1 => 'Rejected',
            0 => 'Cancelled',
            1 => 'Submitted',
            2 => 'Verified',
            3 => 'Approved',
        ];

        // Access the request data to apply filters
        return ExpenseApplication::filter($this->request, false)->get()->map(function ($expense) use (&$serialNo, $statusClasses) {
            return [
                $serialNo++,
                $expense->employee->name,
                $expense->employee->empJob->designation->name,
                $expense->employee->empJob->department->name,
                $expense->expenseType->name,
                $expense->expense_amount,
                $expense->description,
                $statusClasses[$expense->status],
                $expense->expense_approved_by->name,


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
            'Expense Type',
            'Expense Amount',
            'Description',
            'Status',
            'Approved By',


        ];
    }
}
