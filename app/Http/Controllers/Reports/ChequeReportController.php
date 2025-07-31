<?php

namespace App\Http\Controllers\Reports;

use App\Exports\ChequeExport;
use App\Http\Controllers\Controller;
use App\Models\FinalPaySlip;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ChequeReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:report/cheque-report,view')->only('index');
        $this->middleware('permission:report/cheque-report,create')->only('store');
        $this->middleware('permission:report/cheque-report,edit')->only('update');
        $this->middleware('permission:report/cheque-report,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $employee = employeeList();


        // $cheques = FinalPaySlip::whereHas('employee.empJob', function ($query) {
        //     $query->where('salary_disbursement_mode', 2);
        // })->filter($request)->paginate(config('global.pagination'))->withQueryString();

        $cheques = FinalPaySlip::whereHas('employee.empJob', function ($query) {
            $query->where('salary_disbursement_mode', 2);
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



        return view('report.cheque-report.index', compact('privileges', 'cheques', 'employee'));
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

    public function exportCheque(Request $request)
    {

        // Load all bookings with their dzongkhag names
        $cheques = FinalPaySlip::whereHas('employee.empJob', function ($query) {
            $query->where('salary_disbursement_mode', 2);
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

        $totalCheques = $cheques->sum(function ($cheque) {
            return $cheque->net_pay_after_eteeru ?? 0;
        });

        $pdf = Pdf::loadView('export-report.cheque-report-pdf', compact('cheques', 'totalCheques'))->setPaper('a4', 'landscape');

        // Return the PDF download
        return $pdf->download('Cheque-Report.pdf');
    }

    public function exportChequeExcel(Request $request)
    {
        return Excel::download(new ChequeExport($request), 'cheque-report.xlsx');
    }
    public function printCheque(Request $request)
    {
        $cheques = FinalPaySlip::whereHas('employee.empJob', function ($query) {
            $query->where('salary_disbursement_mode', 2);
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

        $totalCheques =
            $cheques->sum(function ($cheque) {
                return $cheque->net_pay_after_eteeru ?? 0;
            });

        $pdf = Pdf::loadView('export-report.cheque-report-pdf', compact('cheques', 'totalCheques'))->setPaper('a4', 'landscape');


        // Return the PDF as a stream to display it in the browser
        return $pdf->stream('Cheque-Report.pdf');
    }
}
