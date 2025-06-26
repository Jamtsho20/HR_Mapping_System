<?php

namespace App\Exports;

use App\Models\MasPayGroupDetail;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EteeruExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $request;
    public function collection()
    {
        $serialNo = 1;

        return MasPayGroupDetail::where('mas_pay_group_id', 4)
            ->join('mas_employee_jobs', 'mas_pay_group_details.mas_grade_id', '=', 'mas_employee_jobs.mas_grade_id')
            ->join('mas_employees', 'mas_employee_jobs.mas_employee_id', '=', 'mas_employees.id')
            ->join('final_pay_slips', 'mas_employees.id', '=', 'final_pay_slips.mas_employee_id')
            ->select(
                'mas_employees.name',
                'mas_employees.contact_number',
                'mas_pay_group_details.amount',
                'final_pay_slips.for_month'
            )
            ->when(request()->employee_id, function ($query, $employee_id) {
                return $query->where('mas_employees.id', '=', $employee_id);
            })
            ->when(request()->year, function ($query, $year) {
                return $query->where('final_pay_slips.for_month', 'like', "{$year}%");
            })
            ->get()
            ->map(function ($data) use (&$serialNo) {
                return [
                    $serialNo++,
                    $data->name,
                    $data->contact_number,
                    $data->amount,
                    $data->for_month
                ];
            });
    }



    public function headings(): array
    {
        return [
            'SL No',
            'Name',
            'Contact Number',
            'Amount',
            'For Month'
        ];
    }
}
