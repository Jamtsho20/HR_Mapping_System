<?php

namespace App\Http\Controllers\Reports;

use App\Exports\SamsungDeductionExport;
use App\Http\Controllers\Controller;
use App\Models\FinalPaySlip;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class SamsungDeductionReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:report/samsung-deduction-report,view')->only('index');
        $this->middleware('permission:report/samsung-deduction-report,create')->only('store');
        $this->middleware('permission:report/samsung-deduction-report,edit')->only('update');
        $this->middleware('permission:report/samsung-deduction-report,delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $privileges = $request->instance();
        $employee = employeeList();

        // $paySlips = FinalPaySlip::leftJoin('loan_e_m_i_deductions', 'final_pay_slips.mas_employee_id', '=', 'loan_e_m_i_deductions.mas_employee_id')
        //     ->join('mas_pay_heads', 'loan_e_m_i_deductions.mas_pay_head_id', '=', 'mas_pay_heads.id') // Join mas_pay_head with loan_e_m_i_deductions on mas_pay_head_id
        //     ->where('loan_e_m_i_deductions.mas_pay_head_id', 16)
        //     ->where('loan_e_m_i_deductions.is_paid_off', 0)
        //     ->whereRaw("DATE_FORMAT(loan_e_m_i_deductions.start_date, '%Y-%m-%d') <= ?", [now()->format('Y-m-01')])
        //     ->whereRaw("DATE_FORMAT(loan_e_m_i_deductions.end_date, '%Y-%m-%d') >= ?", [now()->format('Y-m-01')])  // Compare Year-Month
        //     ->filter($request) // Apply the filters
        //     ->selectRaw('final_pay_slips.*, loan_e_m_i_deductions.*, mas_pay_heads.name as pay_head_name')
        //     ->paginate(config('global.pagination')) // Paginate the results
        //     ->withQueryString(); // Retain the query string in the pagination links

        $paySlips = $this->prepareQuery($request)
            ->paginate(config('global.pagination'))
            ->withQueryString();

        // dd($test);

        return view('report.samsung-deduction-report.index', compact('privileges', 'paySlips', 'employee'));
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

    public function exportSamsungDeduction(Request $request)
    {
        // Load all bookings with their dzongkhag names
        $samsungDeductions = $this->prepareQuery($request)
            ->get();

        $totalSamsung = $samsungDeductions->sum(function ($device) {
            return $device->emi_amount ?? 0;
        });
        // Generate the PDF view and pass the data
        $pdf = Pdf::loadView('export-report.samsung-deductions-report-pdf', compact('samsungDeductions', 'totalSamsung'))->setPaper('a4', 'landscape');;


        // Return the PDF download
        return $pdf->download('SamsungDeduction-Report.pdf');
    }

    public function exportSamsungDeductionExcel(Request $request)
    {
        $samsungDeductions =  $this->prepareQuery($request)
            ->get();


        return Excel::download(new SamsungDeductionExport($request, $samsungDeductions), 'samsung-deduction-report.xlsx');
    }

    public function printSamsungDeduction(Request $request)
    {
        $samsungDeductions = $this->prepareQuery($request)
            ->get();
        // dd($samsungDeductions->first());

        $totalSamsung = $samsungDeductions->sum(function ($device) {
            return $device->emi_amount ?? 0;
        });
        // Generate the PDF view and pass the data
        $pdf = Pdf::loadView('export-report.samsung-deductions-report-pdf', compact('samsungDeductions', 'totalSamsung'))->setPaper('a4', 'landscape');;


        // Return the PDF as a stream to display it in the browser
        return $pdf->stream('SamsungDeduction-Report.pdf');
    }

    private function prepareQuery(Request $request)
    {
        return FinalPaySlip::join('loan_e_m_i_deductions', function ($join) {
            $join->on('final_pay_slips.mas_employee_id', '=', 'loan_e_m_i_deductions.mas_employee_id')
                ->where('loan_e_m_i_deductions.mas_pay_head_id', 16)
                ->where('loan_e_m_i_deductions.is_paid_off', 0)
                ->whereColumn('final_pay_slips.for_month', '>=', 'loan_e_m_i_deductions.start_date')
                ->whereColumn('final_pay_slips.for_month', '<=', 'loan_e_m_i_deductions.end_date');
        })
            ->join('mas_employees', 'loan_e_m_i_deductions.mas_employee_id', '=', 'mas_employees.id')
            ->where('mas_employees.is_active', 1)
            ->leftJoin('mas_pay_heads', 'loan_e_m_i_deductions.mas_pay_head_id', '=', 'mas_pay_heads.id')
            ->select(
                'final_pay_slips.*',
                DB::raw('IFNULL(loan_e_m_i_deductions.amount, 0) as emi_amount'),
                'loan_e_m_i_deductions.loan_number',
                'loan_e_m_i_deductions.start_date',
                'loan_e_m_i_deductions.end_date',
                'loan_e_m_i_deductions.recurring_months',
                'mas_pay_heads.name as pay_head_name'
            )
            ->filter($request);
    }
}
