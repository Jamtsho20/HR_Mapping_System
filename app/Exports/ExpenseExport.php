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
        return ExpenseApplication::with('details')->with(['audit_logs' => function ($query) {
            $query->where('status', 3);
        }])->filter($this->request, false)->whereStatus(3)->get()->flatMap(function ($expense) use (&$serialNo, $statusClasses) {
            return $expense->details->map(function ($detail) use (&$serialNo, $expense, $statusClasses) {
                return [
                    $serialNo++,
                    $expense->employee->username,
                    $expense->employee->name,
                    $expense->employee->empJob->designation->name,
                    $expense->employee->empJob->department->name,
                    $expense->type->name,
                    \Carbon\Carbon::parse($expense->transaction_date)->format('d-F-Y'),
                    $expense->vehicle->vehicleType->name ?? '-',
                    $expense->vehicle->vehicle_no ?? '-',
                    $expense->transaction_no,
                    $detail->initial_reading,
                    $detail->final_reading,
                    $detail->quantity,
                    $detail->rate,
                    $detail->amount ?? $expense->amount,
                    $detail->mileage,
                    $expense->travel_type,
                    $expense->travel_mode,
                    $expense->travel_from_date,
                    $expense->travel_to_date,
                    $expense->travel_from,
                    $expense->travel_to,
                    $expense->travel_disatnce,
                    $expense->description,
                    $statusClasses[$expense->status],
                    $expense->expense_approved_by->name ?? '-',


                ];
            });
        });
    }
    public function headings(): array
    {
        return [
            'Sl No',
            'Employee ID',
            'Employee Name',
            'Designation',
            'Department',
            'Expense Type',
            'Date',
            'Vechicle Type',
            'Vehicle No',
            'Expense No',
            'Initial Reading',
            'FInal Reading',
            'Qty',
            'Rate',
            'Amount',
            'Mileage',
            'Travel Type',
            'Travel Mode',
            'Travel From Date',
            'Travel To Date',
            'Travel From',
            'Travel To',
            'Travel Distance',
            'Description',
            'Status',
            'Approved By',
        ];
    }
}
