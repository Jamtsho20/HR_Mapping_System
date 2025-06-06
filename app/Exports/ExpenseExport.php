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

        return ExpenseApplication::with('details')->with(['audit_logs' => function ($query) {
            $query->where('status', 3);
        }])->filter($this->request, false)->whereStatus(3)->get()->flatMap(function ($expense) use (&$serialNo, $statusClasses) {
        return $expense->details->isNotEmpty()
            ? $expense->details->map(function ($detail) use (&$serialNo, $expense, $statusClasses) {
                return $this->buildRow($detail, $expense, $serialNo, $statusClasses);
            })
            : collect([
                $this->buildRow(null, $expense, $serialNo, $statusClasses)
            ]);
        });
    }

    public function headings(): array
    {
        return [
            'Sl No',
            'Applied On',
            'Employee Name',
            'Employee ID',
            'Designation',
            'Department',
            'Region',
            'Office Location',
            'Expense Type',
            'SAP Trans No',
            'Vechicle Type',
            'Vehicle No',
            'Expense No',
            'Initial Reading (km)',
            'Final Reading (km)',
            'Qty (ltrs)',
            'Rate (Nu.)',
            'Amount (Nu.)',
            'Receipt Date',
            'Mileage',
            'Travel Type',
            'Travel Mode',
            'Travel From Date',
            'Travel To Date',
            'Travel From',
            'Travel To',
            'Travel Distance (km)',
            'Description',
            'Status',
            'Approved By',
            'Approved On'
        ];
    }

    private function buildRow($detail, $expense, &$serialNo, $statusClasses){
        return[
            $serialNo++,
            getDisplayDateFormat($expense->created_at),
            $expense->employee->emp_name,
            $expense->employee->username,
            $expense->employee->empJob->designation->name,
            $expense->employee->empJob->department->name,
            $expense->employee->empJob->office->region->name,
            $expense->employee->empJob->office->name,
            $expense->type->name,
            optional(json_decode(optional($expense->audit_logs->first())->sap_response, true))['data']['JdtNum'] ?? config('global.null_value'),
            $expense->vehicle->vehicleType->name ?? config('global.null_value'),
            $expense->vehicle->vehicle_no ?? config('global.null_value'),
            $expense->transaction_no ?? config('global.null_value'),
            $detail->initial_reading ?? config('global.null_value'),
            $detail->final_reading ?? config('global.null_value'),
            $detail->quantity ?? config('global.null_value'),
            $detail->rate ?? config('global.null_value'),
            formatAmount(optional($detail)->amount ?? $expense->amount, false) ?? config('global.null_value'),
            getDisplayDateFormat($detail)->date,
            $detail->mileage ?? config('global.null_value'),
            $expense->travel_type ?? config('global.null_value'),
            $expense->travel_mode ?? config('global.null_value'),
            getDisplayDateFormat($expense->travel_from_date),
            getDisplayDateFormat($expense->travel_to_date),
            $expense->travel_from ?? config('global.null_value'),
            $expense->travel_to ?? config('global.null_value'),
            $expense->travel_distance ?? config('global.null_value'),
            $expense->description ?? config('global.null_value'),
            $statusClasses[$expense->status],
            $expense->expense_approved_by->emp_name ?? config('global.null_value'),
            getDisplayDateFormat($expense->updated_at) ?? config('global.null_value'),
        ];
    }
}
