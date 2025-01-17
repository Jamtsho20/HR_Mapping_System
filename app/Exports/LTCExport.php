<?php

namespace App\Exports;

use App\Models\LeaveTravelConcession;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LTCExport implements FromCollection, WithHeadings
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

        return LeaveTravelConcession::filter($this->request)
            ->with(['ltcDetails.employee.empJob.designation', 'ltcDetails.employee.empJob.office', 'ltcDetails.employee.empJob.gradeStep'])
            ->get()
            ->flatMap(function ($ltc) use (&$serialNo) {
                if ($ltc->ltcDetails->isEmpty()) {
                    return [[
                        'Serial No' => $serialNo++,
                        'Code' => 'N/A',
                        'Employee Name' => 'N/A',
                        'Designation' => 'N/A',
                        'Office' => 'N/A',
                        'Date of Appointment' => 'N/A',
                        'Grade Step' => 'N/A',
                        'Basic Pay' => 'N/A',
                        'Due Date' => $ltc->for_month,
                    ]];
                }

                return $ltc->ltcDetails->map(function ($detail) use (&$serialNo, $ltc) {
                    return [
                        'Serial No' => $serialNo++,
                        'Code' => $detail->employee->username ?? 'N/A',
                        'Employee Name' => $detail->employee->name ?? 'N/A',
                        'Designation' => $detail->employee->empJob->designation->name ?? 'N/A',
                        'Office' => $detail->employee->empJob->office->name ?? 'N/A',
                        'Date of Appointment' => $detail->employee->date_of_appointment ?? 'N/A',
                        'Grade Step' => $detail->employee->empJob->gradeStep->name ?? 'N/A',
                        'Basic Pay' => $detail->employee->empJob->basic_pay ?? 'N/A',
                        'Due Date' => $ltc->for_month,
                    ];
                });
            });
    }


    public function headings(): array
    {
        return [
            'Serial No',
            'Code',
            'Employee Name',
            'Designation',
            'Office',
            'Date of Appointment',
            'Grade Step',
            'Basic Pay',
            'Due Date'
        ];
    }
}
