<?php

namespace App\Http\Controllers\Reports;

use App\Exports\SamsungDeductionExport;
use App\Http\Controllers\Controller;
use App\Models\FinalPaySlip;
use App\Models\MasPayHead;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Sum;

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
        $samsungDeductions = FinalPaySlip::leftJoin('loan_e_m_i_deductions', 'final_pay_slips.mas_employee_id', '=', 'loan_e_m_i_deductions.mas_employee_id')
            ->join('mas_pay_heads', 'loan_e_m_i_deductions.mas_pay_head_id', '=', 'mas_pay_heads.id') // Join mas_pay_head with loan_e_m_i_deductions on mas_pay_head_id
            ->where('loan_e_m_i_deductions.mas_pay_head_id', 16)
            ->where('loan_e_m_i_deductions.is_paid_off', 0)
            ->whereRaw("DATE_FORMAT(loan_e_m_i_deductions.start_date, '%Y-%m-%d') <= ?", [now()->format('Y-m-01')])
            ->whereRaw("DATE_FORMAT(loan_e_m_i_deductions.end_date, '%Y-%m-%d') >= ?", [now()->format('Y-m-01')])  // Compare Year-Month
            ->filter($request) // Apply the filters
            ->selectRaw('final_pay_slips.for_month, loan_e_m_i_deductions.*, mas_pay_heads.name as pay_head_name')
            // ->select('final_pay_slips.for_month', 'loan_e_m_i_deductions.*', 'mas_pay_heads.name as pay_head_name') // Select the columns you need, including pay_head name
            ->paginate(config('global.pagination')) // Paginate the results
            ->withQueryString(); // Retain the query string in the pagination links
        // $totalSamsung = $samsungDeductions->sum(function ($device) {
        //     return $device->amount ?? 0;
        // });


        return view('report.samsung-deduction-report.index', compact('privileges', 'samsungDeductions', 'employee'));
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
        $samsungDeductions = FinalPaySlip::leftJoin('loan_e_m_i_deductions', 'final_pay_slips.mas_employee_id', '=', 'loan_e_m_i_deductions.mas_employee_id')
            ->Join('mas_pay_heads', 'loan_e_m_i_deductions.mas_pay_head_id', '=', 'mas_pay_heads.id') // Join mas_pay_head with loan_e_m_i_deductions on mas_pay_head_id
            ->where('loan_e_m_i_deductions.mas_pay_head_id', 16)
            ->where('loan_e_m_i_deductions.is_paid_off', 0)
            ->whereRaw("DATE_FORMAT(loan_e_m_i_deductions.start_date, '%Y-%m-%d') <= ?", [now()->format('Y-m-01')])
            ->whereRaw("DATE_FORMAT(loan_e_m_i_deductions.end_date, '%Y-%m-%d') >= ?", [now()->format('Y-m-01')])  // Compare Year-Month
            ->filter($request) // Apply the filters
            ->selectRaw('final_pay_slips.for_month, loan_e_m_i_deductions.*, mas_pay_heads.name as pay_head_name')
            // ->select('final_pay_slips.for_month', 'loan_e_m_i_deductions.*', 'mas_pay_heads.name as pay_head_name') // Select the columns you need, including pay_head name
            ->get();



        $totalSamsung = $samsungDeductions->sum(function ($device) {
            return $device->amount ?? 0;
        });
        // Generate the PDF view and pass the data
        $pdf = Pdf::loadView('export-report.samsung-deductions-report-pdf', compact('samsungDeductions', 'totalSamsung'))->setPaper('a4', 'landscape');;


        // Return the PDF download
        return $pdf->download('SamsungDeduction-Report.pdf');
    }

    public function exportSamsungDeductionExcel(Request $request)
    {
        return Excel::download(new SamsungDeductionExport($request), 'samsung-deduction-report.xlsx');
    }
    public function printSamsungDeduction(Request $request)
    {
        $samsungDeductions = FinalPaySlip::leftJoin('loan_e_m_i_deductions', 'final_pay_slips.mas_employee_id', '=', 'loan_e_m_i_deductions.mas_employee_id')
            ->Join('mas_pay_heads', 'loan_e_m_i_deductions.mas_pay_head_id', '=', 'mas_pay_heads.id') // Join mas_pay_head with loan_e_m_i_deductions on mas_pay_head_id
            ->where('loan_e_m_i_deductions.mas_pay_head_id', 16)
            ->where('loan_e_m_i_deductions.is_paid_off', 0)
            ->whereRaw("DATE_FORMAT(loan_e_m_i_deductions.start_date, '%Y-%m-%d') <= ?", [now()->format('Y-m-01')])
            ->whereRaw("DATE_FORMAT(loan_e_m_i_deductions.end_date, '%Y-%m-%d') >= ?", [now()->format('Y-m-01')])  // Compare Year-Month
            ->filter($request) // Apply the filters
            ->selectRaw('final_pay_slips.for_month, loan_e_m_i_deductions.*, mas_pay_heads.name as pay_head_name')
            // ->select('final_pay_slips.for_month', 'loan_e_m_i_deductions.*', 'mas_pay_heads.name as pay_head_name') // Select the columns you need, including pay_head name
            ->get();

        $totalSamsung = $samsungDeductions->sum(function ($device) {
            return $device->amount ?? 0;
        });
        // Generate the PDF view and pass the data
        $pdf = Pdf::loadView('export-report.samsung-deductions-report-pdf', compact('samsungDeductions', 'totalSamsung'))->setPaper('a4', 'landscape');;


        // Return the PDF as a stream to display it in the browser
        return $pdf->stream('SamsungDeduction-Report.pdf');
    }

}
