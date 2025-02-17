<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\MasPayGroupDetail;
use Illuminate\Http\Request;

class eTeeruRemittanceReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:report/leave-balance-report,view')->only('index');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
        //  $taxSchedules = MasPayGroupDetail::filter($request)->paginate(config('global.pagination'))->withQueryString();
        $taxSchedules = MasPayGroupDetail::where('mas_pay_group_id', 4)
            ->join(
                'mas_employee_jobs',
                'mas_pay_group_details.mas_grade_id',
                '=',
                'mas_employee_jobs.mas_grade_id'
            )
            ->join('mas_employees', 'mas_employee_jobs.mas_employee_id', '=', 'mas_employees.id')
            ->join('final_pay_slips', 'mas_employees.id', '=', 'final_pay_slips.mas_employee_id')
            // Select the required fields
            ->select(
                'mas_employees.name',
                'mas_employees.contact_number',
                'mas_pay_group_details.amount',
                'final_pay_slips.for_month'
            ) // Select the required fields

            ->when($request->employee_id, function ($query, $name) {
                return $query->where('mas_employees.id', '=', $name);
            })

            // Filter `final_pay_slips` table (e.g., for specific month)
            ->when($request->year, function ($query, $month) {
                return $query->where('final_pay_slips.for_month', 'like', "{$month}%");
            })
            ->paginate(config('global.pagination'))
            ->withQueryString();



        $employee = employeeList();


        return view('report.eteeru-remittance.index', compact('privileges', 'employee', 'taxSchedules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
