<?php

namespace App\Http\Controllers\Reports;

use App\Exports\CashExport;
use App\Http\Controllers\Controller;
use App\Models\FinalPaySlip;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class CashReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:report/cash-report,view')->only('index');
        $this->middleware('permission:report/cash-report,create')->only('store');
        $this->middleware('permission:report/cash-report,edit')->only('update');
        $this->middleware('permission:report/cash-report,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $employee = employeeList();


        $cashes =
            FinalPaySlip::whereHas('employee.empJob', function ($query) {
                $query->where('salary_disbursement_mode', 1);
            })
            ->join('mas_employees', 'final_pay_slips.mas_employee_id', '=', 'mas_employees.id')
            ->join('mas_employee_jobs', 'mas_employees.id', '=', 'mas_employee_jobs.mas_employee_id')
            ->leftJoin('mas_pay_group_details', function ($join) {
                $join->on('mas_employee_jobs.mas_grade_id', '=', 'mas_pay_group_details.mas_grade_id')
                    ->where('mas_pay_group_details.mas_pay_group_id', 4);
            })
            ->where('mas_employees.is_active', 1)
            ->select(
                'final_pay_slips.*',
                'mas_employees.name',
                'mas_employee_jobs.account_number',
                'mas_employee_jobs.bank',
                DB::raw('(JSON_UNQUOTE(JSON_EXTRACT(final_pay_slips.details, "$.net_pay")) - COALESCE(mas_pay_group_details.amount, 0)) as net_pay_after_eteeru')
            )
            ->filter($request)
            ->paginate(config('global.pagination'))
            ->withQueryString();


        return view('report.cash-report.index', compact('privileges', 'cashes', 'employee'));
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
    public function exportCash(Request $request)
    {

        // Load all bookings with their dzongkhag names
        $cashes = FinalPaySlip::whereHas('employee.empJob', function ($query) {
            $query->where('salary_disbursement_mode', 1);
        })
            ->join('mas_employees', 'final_pay_slips.mas_employee_id', '=', 'mas_employees.id')
            ->join('mas_employee_jobs', 'mas_employees.id', '=', 'mas_employee_jobs.mas_employee_id')
            ->leftJoin('mas_pay_group_details', function ($join) {
                $join->on('mas_employee_jobs.mas_grade_id', '=', 'mas_pay_group_details.mas_grade_id')
                    ->where('mas_pay_group_details.mas_pay_group_id', 4);
            })
            ->where('mas_employees.is_active', 1)

            ->select(
                'final_pay_slips.*',
                'mas_employees.name',
                'mas_employee_jobs.account_number',
                'mas_employee_jobs.bank',
                DB::raw('(JSON_UNQUOTE(JSON_EXTRACT(final_pay_slips.details, "$.net_pay")) - COALESCE(mas_pay_group_details.amount, 0)) as net_pay_after_eteeru')
            )
            ->filter($request)->get();

        $totalCashes = $cashes->sum(function ($cash) {
            return $cash->net_pay_after_eteeru ?? 0;
        });
        $pdf = Pdf::loadView('export-report.cash-report-pdf', compact('cashes', 'totalCashes'))->setPaper('a4', 'landscape');




        // Return the PDF download
        return $pdf->download('Cash-Report.pdf');
    }

    public function exportCashExcel(Request $request)
    {
        return Excel::download(new CashExport($request), 'cash-report.xlsx');
    }
    public function printCash(Request $request)
    {
        $cashes = FinalPaySlip::whereHas('employee.empJob', function ($query) {
            $query->where('salary_disbursement_mode', 1);
        })
            ->join('mas_employees', 'final_pay_slips.mas_employee_id', '=', 'mas_employees.id')
            ->join('mas_employee_jobs', 'mas_employees.id', '=', 'mas_employee_jobs.mas_employee_id')
            ->leftJoin('mas_pay_group_details', function ($join) {
                $join->on('mas_employee_jobs.mas_grade_id', '=', 'mas_pay_group_details.mas_grade_id')
                    ->where('mas_pay_group_details.mas_pay_group_id', 4);
            })
            ->where('mas_employees.is_active', 1)
            ->select(
                'final_pay_slips.*',
                'mas_employees.name',
                'mas_employee_jobs.account_number',
                'mas_employee_jobs.bank',
                DB::raw('(JSON_UNQUOTE(JSON_EXTRACT(final_pay_slips.details, "$.net_pay")) - COALESCE(mas_pay_group_details.amount, 0)) as net_pay_after_eteeru')
            )
            ->filter($request)->get();

        $totalCashes = $cashes->sum(function ($cash) {
            return $cash->net_pay_after_eteeru ?? 0;
        });
        $pdf = Pdf::loadView('export-report.cash-report-pdf', compact('cashes', 'totalCashes'))->setPaper('a4', 'landscape');


        // Return the PDF as a stream to display it in the browser
        return $pdf->stream('Cash-Report.pdf');
    }
}
