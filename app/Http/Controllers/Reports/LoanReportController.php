<?php

namespace App\Http\Controllers\Reports;

use App\Exports\LoanExport;
use App\Http\Controllers\Controller;
use App\Models\FinalPaySlip;
use App\Models\MasPayHead;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

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
        $bankCode = ['BOB_Loan', 'TBank_loan'];
        $privileges = $request->instance();
        $employee = employeeList();
        $banks = MasPayHead::whereIn('id', [17, 18, 19, 20, 21, 22, 23, 24])->get();

        $loans = $this->prepareQuery($request)
            ->paginate(config('global.pagination'))
            ->withQueryString();

        return view('report.loan-report.index', compact('privileges', 'loans', 'employee', 'banks'));
    }

    public function exportLoan(Request $request)
    {
        // Load all bookings with their dzongkhag names
        $loans = $this->prepareQuery($request)->get();

        // $loans = $loans->filter(function ($loan) use ($loans) {
        //     return $this->filterData($loans, $loan);
        // });

        $totalLoans = $loans->sum(function ($loan) {
            return $loan->amount ?? 0;
        });

        // Get the bank name from the request (fallback to 'Loan-Report' if not provided)
        $payHeadId = $request->input('mas_pay_head_id');

        // Get the bank name using the ID
        $bankName = null;
        if ($payHeadId) {
            $bank = MasPayHead::find($payHeadId);
            $bankName = $bank ? $bank->name : 'Loan-Report';
        } else {
            $bankName = 'Loan-Report';
        }

        $safeBankName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $bankName); // Sanitize for filename

        // Generate the PDF view
        $pdf = Pdf::loadView('export-report.loan-report-pdf', compact('loans', 'totalLoans'))->setPaper('a4', 'landscape');

        // Return the PDF download with the bank name in filename
        return $pdf->download("{$safeBankName}.pdf");
    }

    public function exportLoanExcel(Request $request)
    {
        $loans = $this->prepareQuery($request)->get();

        // $loans = $loans->filter(function ($loan) use ($loans) {
        //     return $this->filterData($loans, $loan);
        // });

        return Excel::download(new LoanExport($request, $loans), 'loan-report.xlsx');
    }

    // public function printLoan(Request $request)
    // {
    //     $loans = $this->prepareQuery($request)->get();

    //     $totalLoans = $loans->sum(fn($loan) => $loan->amount ?? 0);
    //     // Bank name
    //     $payHeadId = $request->input('mas_pay_head_id');
    //     $bankName = $payHeadId ? optional(MasPayHead::find($payHeadId))->name : 'Loan-Report';
    //     $safeBankName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $bankName);

    //     // PDF
    //     $pdf = Pdf::loadView('export-report.loan-report-pdf', compact('loans', 'totalLoans'))
    //         ->setPaper('a4', 'landscape');

    //     return $pdf->stream("{$safeBankName}.pdf");
    // }

    public function printLoan(Request $request)
    {
        $loans = $this->prepareQuery($request)->get();

        // Sum from salary_emi_amount to match salary report
        //$totalLoans = $loans->sum(fn($loan) => $loan->amount ?? 0);
        $totalLoans = $loans->sum(fn($loan) => $loan->salary_emi_amount ? (float)$loan->salary_emi_amount : 0);

        // Bank name
        $payHeadId = $request->input('mas_pay_head_id');
        $bankName = $payHeadId ? optional(MasPayHead::find($payHeadId))->name : 'Loan-Report';
        $safeBankName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $bankName);

        // PDF
        $pdf = Pdf::loadView('export-report.loan-report-pdf', compact('loans', 'totalLoans'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream("{$safeBankName}.pdf");
    }


    private function prepareQuery(Request $request)
    {
        $year = $request->year . '-01';
        // $year = $this->getSelectedMonth($request);
        $query = FinalPaySlip::join('loan_e_m_i_deductions', 'final_pay_slips.mas_employee_id', '=', 'loan_e_m_i_deductions.mas_employee_id')
            ->join('mas_pay_heads', 'loan_e_m_i_deductions.mas_pay_head_id', '=', 'mas_pay_heads.id')
            ->join('mas_loan_types', 'loan_e_m_i_deductions.loan_type_id', '=', 'mas_loan_types.id')
            ->join('mas_employees', 'loan_e_m_i_deductions.mas_employee_id', '=', 'mas_employees.id')
            ->whereIn('loan_e_m_i_deductions.mas_pay_head_id', [17, 18, 19, 20, 21, 22, 23, 24])

            // year filter with month
            ->where(function ($q) use ($year) {
                $q->where(function ($q2) use ($year) {
                    $q2->where('loan_e_m_i_deductions.is_paid_off', 0)
                        ->whereDate('loan_e_m_i_deductions.end_date', '>=', $year)
                        ->whereDate('loan_e_m_i_deductions.start_date', '<=', $year);
                })
                    ->orWhere(function ($q3) use ($year) {
                        $q3->where('loan_e_m_i_deductions.is_paid_off', 1)
                            ->whereDate('loan_e_m_i_deductions.start_date', '<=', $year)
                            ->whereDate('loan_e_m_i_deductions.paid_off_at', '>=', $year)
                            ->whereRaw("DATE_FORMAT(final_pay_slips.for_month, '%Y-%m') = DATE_FORMAT(?, '%Y-%m')", [$year])
                            ->whereRaw("JSON_EXTRACT(final_pay_slips.details, CONCAT('$.deductions.\"', mas_pay_heads.name, '\"')) = loan_e_m_i_deductions.amount");
                    });
            })

            // hide old loan if new loan exists
            // ->whereRaw("
            //     NOT (
            //         loan_e_m_i_deductions.is_paid_off = 1
            //         AND EXISTS (
            //             SELECT 1 
            //             FROM loan_e_m_i_deductions AS new_loan
            //             WHERE 
            //                 new_loan.mas_employee_id = loan_e_m_i_deductions.mas_employee_id
            //                 AND new_loan.mas_pay_head_id = loan_e_m_i_deductions.mas_pay_head_id
            //                 AND new_loan.amount = loan_e_m_i_deductions.amount
            //                 AND new_loan.is_paid_off = 0
            //                 AND DATE_FORMAT(new_loan.start_date, '%Y-%m') = DATE_FORMAT(loan_e_m_i_deductions.paid_off_at, '%Y-%m')
            //         )
            //     )
            // ")

            ->where('mas_employees.is_active', 1)
            ->filter($request)
            ->select(
                'final_pay_slips.*',
                'loan_e_m_i_deductions.*',
                'mas_pay_heads.name as pay_head_name',
                'mas_loan_types.name as loan_type'
            )
            ->selectRaw("
                JSON_EXTRACT(final_pay_slips.details, CONCAT('$.deductions.\"', mas_pay_heads.name, '\"')) AS salary_emi_amount
            ");

        //     // Apply "hide old loan if new loan exists" only for SIFA loans
        $query->when($request->has('mas_pay_head_id') && $request->mas_pay_head_id == 24, function ($q) {
                $q->whereRaw("
                NOT (
                    loan_e_m_i_deductions.is_paid_off = 1
                    AND EXISTS (
                        SELECT 1 
                        FROM loan_e_m_i_deductions AS new_loan
                        WHERE 
                            new_loan.mas_employee_id = loan_e_m_i_deductions.mas_employee_id
                            AND new_loan.mas_pay_head_id = loan_e_m_i_deductions.mas_pay_head_id
                            AND new_loan.amount = loan_e_m_i_deductions.amount
                            AND new_loan.is_paid_off = 0
                            AND DATE_FORMAT(new_loan.start_date, '%Y-%m') = DATE_FORMAT(loan_e_m_i_deductions.paid_off_at, '%Y-%m')
                    )
                )
            ");
        });

        return $query;
    }
    // private function prepareQuery(Request $request)
    // {
    //     $month = $this->getSelectedMonth($request)->format('Y-m');
    //     // $month = $request->year . '-01';

    //     $query = FinalPaySlip::join('loan_e_m_i_deductions', 'final_pay_slips.mas_employee_id', '=', 'loan_e_m_i_deductions.mas_employee_id')
    //         ->join('mas_pay_heads', 'loan_e_m_i_deductions.mas_pay_head_id', '=', 'mas_pay_heads.id')
    //         ->join('mas_loan_types', 'loan_e_m_i_deductions.loan_type_id', '=', 'mas_loan_types.id')
    //         ->join('mas_employees', 'loan_e_m_i_deductions.mas_employee_id', '=', 'mas_employees.id')
    //         ->whereIn('loan_e_m_i_deductions.mas_pay_head_id', [17, 18, 19, 20, 21, 22, 23, 24])
    //         ->whereRaw("DATE_FORMAT(final_pay_slips.for_month, '%Y-%m') = ?", [$month])
    //         ->whereRaw("JSON_EXTRACT(final_pay_slips.details, CONCAT('$.deductions.\"', mas_pay_heads.name, '\"')) IS NOT NULL")
    //         ->where('mas_employees.is_active', 1)
    //         ->filter($request)
    //         ->select(
    //             'final_pay_slips.*',
    //             'loan_e_m_i_deductions.*',
    //             'mas_pay_heads.name as pay_head_name',
    //             'mas_loan_types.name as loan_type'
    //         )
    //         ->selectRaw("
    //         JSON_EXTRACT(final_pay_slips.details, CONCAT('$.deductions.\"', mas_pay_heads.name, '\"')) AS salary_emi_amount
    //     ");

    //     // Apply "hide old loan if new loan exists" only for SIFA loans
    //     $query->when($request->has('mas_pay_head_id') && $request->mas_pay_head_id == 24, function ($q) {
    //         $q->whereRaw("
    //         NOT (
    //             loan_e_m_i_deductions.is_paid_off = 1
    //             AND EXISTS (
    //                 SELECT 1 
    //                 FROM loan_e_m_i_deductions AS new_loan
    //                 WHERE 
    //                     new_loan.mas_employee_id = loan_e_m_i_deductions.mas_employee_id
    //                     AND new_loan.mas_pay_head_id = loan_e_m_i_deductions.mas_pay_head_id
    //                     AND new_loan.amount = loan_e_m_i_deductions.amount
    //                     AND new_loan.is_paid_off = 0
    //                     AND DATE_FORMAT(new_loan.start_date, '%Y-%m') = DATE_FORMAT(loan_e_m_i_deductions.paid_off_at, '%Y-%m')
    //             )
    //         )
    //     ");
    //     });

    //     return $query;
    // }




    private function getSelectedMonth(Request $request)
    {
        $dates = explode(' - ', $request->get('year'));
        return Carbon::createFromFormat('Y-m', trim($dates[0]));
    }
}
