<?php

namespace App\Http\Controllers\Reports;

use App\Exports\LoanExport;
use App\Http\Controllers\Controller;
use App\Models\BankLoan;
use App\Models\FinalPaySlip;
use App\Models\MasPayHead;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LoanReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct()
    {
        $this->middleware('permission:report/loan-report,view')->only('index');
        $this->middleware('permission:report/loan-report,create')->only('store');
        $this->middleware('permission:report/loan-report,edit')->only('update');
        $this->middleware('permission:report/loan-report,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $employee = employeeList();
        $banks = MasPayHead::whereIn('id', [12, 13])->get();
        $loans = FinalPaySlip::join('loan_e_m_i_deductions', 'final_pay_slips.mas_employee_id', '=', 'loan_e_m_i_deductions.mas_employee_id')
            ->join('mas_pay_heads', 'loan_e_m_i_deductions.mas_pay_head_id', '=', 'mas_pay_heads.id') // Join mas_pay_head with loan_e_m_i_deductions on mas_pay_head_id
            ->whereIn('loan_e_m_i_deductions.mas_pay_head_id', [12, 13])
            ->filter($request) // Apply the filters
            ->select('final_pay_slips.*', 'loan_e_m_i_deductions.*', 'mas_pay_heads.name as pay_head_name') // Select the columns you need, including pay_head name
            ->paginate(config('global.pagination')) // Paginate the results
            ->withQueryString(); // Retain the query string in the pagination links


        return view('report.loan-report.index', compact('privileges', 'loans', 'employee', 'banks'));
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


    public function exportLoan(Request $request)
    {

        // Load all bookings with their dzongkhag names
        $loans = FinalPaySlip::join('loan_e_m_i_deductions', 'final_pay_slips.mas_employee_id', '=', 'loan_e_m_i_deductions.mas_employee_id')
            ->join('mas_pay_heads', 'loan_e_m_i_deductions.mas_pay_head_id', '=', 'mas_pay_heads.id') // Join mas_pay_head with loan_e_m_i_deductions on mas_pay_head_id
            ->whereIn('loan_e_m_i_deductions.mas_pay_head_id', [12, 13])
            ->filter($request) // Apply the filters
            ->select('final_pay_slips.*', 'loan_e_m_i_deductions.*', 'mas_pay_heads.name as pay_head_name')->get();



        // Generate the PDF view and pass the data
        $pdf = Pdf::loadView('export-report.loan-report-pdf', compact('loans'))->setPaper('a4', 'landscape');;

        // Return the PDF download
        return $pdf->download('Loan-Report.pdf');
    }

    public function exportLoanExcel(Request $request)
    {
        return Excel::download(new LoanExport($request), 'loan-report.xlsx');
    }
    public function printLoan(Request $request)
    {
        $loans = FinalPaySlip::join('loan_e_m_i_deductions', 'final_pay_slips.mas_employee_id', '=', 'loan_e_m_i_deductions.mas_employee_id')
            ->join('mas_pay_heads', 'loan_e_m_i_deductions.mas_pay_head_id', '=', 'mas_pay_heads.id') // Join mas_pay_head with loan_e_m_i_deductions on mas_pay_head_id
            ->whereIn('loan_e_m_i_deductions.mas_pay_head_id', [12, 13])
            ->filter($request) // Apply the filters
            ->select('final_pay_slips.*', 'loan_e_m_i_deductions.*', 'mas_pay_heads.name as pay_head_name')->get();
        // Generate the PDF view and pass the data
        $pdf = Pdf::loadView('export-report.loan-report-pdf', compact('loans'))->setPaper('a4', 'landscape');;


        // Return the PDF as a stream to display it in the browser
        return $pdf->stream('Loan-Report.pdf');
    }
}
