<?php

namespace App\Http\Controllers\Reports;

use App\Exports\SalaryPaySlipExport;
use App\Http\Controllers\Controller;
use App\Models\FinalPaySlip;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SalaryReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:report/salary-report,view')->only('index');
        $this->middleware('permission:report/salary-report,create')->only('store');
        $this->middleware('permission:report/salary-report,edit')->only('update');
        $this->middleware('permission:report/salary-report,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $employee = employeeList();
        $salaries = FinalPaySlip::filter($request)->paginate(config('global.pagination'))->withQueryString();

        return view('report.salary-report.index', compact('privileges', 'salaries', 'employee'));
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

    public function exportSalary(Request $request)
    {
        $salaries = FinalPaySlip::filter($request)->get();

        // Initialize variables to store totals
        $totalBasic = $totalGross = $totalOver = $totalAdd = $totalCash = $totalCorporate = $totalCritical = $totalDifficulty = $totalHouse = $totalMedical = 0;
        $totalAdvSalary = $totalAdvStaff = $totalGSLI = $totalHealth = $totalPF = $totalSalaryTax = $totalSamsundDed = $totalSIFA = $totalSSSS = $totalNet = $totalBOB =  $totalTbank = 0;

        // Iterate through salaries once to calculate all totals
        foreach ($salaries as $salary) {
            $details = $salary->details;

            // Basic Pay and Gross Pay (assuming they are the same for simplicity)
            $totalBasic += $details['basic_pay'] ?? 0;
            $totalGross += $details['basic_pay'] ?? 0;

            // Overtime
            $totalOver += $details['overtime_hours'] ?? 0;

            // Allowances
            $totalAdd += $details['allowances']['Add. Work Allowance'] ?? 0;
            $totalCash += $details['allowances']['Cash Allowance'] ?? 0;
            $totalCorporate += $details['allowances']['Corporate Allowance'] ?? 0;
            $totalCritical += $details['allowances']['Critical Allowance'] ?? 0;
            $totalDifficulty += $details['allowances']['Difficulty Allowance'] ?? 0;
            $totalHouse += $details['allowances']['House Allowance'] ?? 0;
            $totalMedical += $details['allowances']['Medical Allowance'] ?? 0;

            // Deductions
            $totalAdvSalary += $details['deductions']['Adv. Salary'] ?? 0;
            $totalAdvStaff += $details['deductions']['Adv. Salary'] ?? 0; // This seems to be the same as 'Adv. Salary', but if not, adjust
            $totalGSLI += $details['deductions']['GSLI'] ?? 0;
            $totalHealth += $details['deductions']['H/Tax'] ?? 0;
            $totalPF += $details['deductions']['PF Contr'] ?? 0;
            $totalSalaryTax += $details['deductions']['Salary Tax'] ?? 0;
            $totalSamsundDed += $details['deductions']['Samsung Ded'] ?? 0;
            $totalSIFA += $details['deductions']['SIFA'] ?? 0;
            $totalSSSS += $details['deductions']['SSSS'] ?? 0;
            $totalNet += $details['net_pay'] ?? 0;
            $totalBOB += $details['Loan BOB'] ?? 0;
            $totalTbank += $details['Loan TBank'] ?? 0;
        }

        // Generate the PDF view and pass the data
        $pdf = Pdf::loadView('export-report.salary-report-pdf', compact(
            'salaries',
            'totalBasic',
            'totalGross',
            'totalOver',
            'totalAdd',
            'totalCash',
            'totalCorporate',
            'totalCritical',
            'totalDifficulty',
            'totalHouse',
            'totalMedical',
            'totalAdvSalary',
            'totalAdvStaff',
            'totalGSLI',
            'totalHealth',
            'totalPF',
            'totalSalaryTax',
            'totalSamsundDed',
            'totalSIFA',
            'totalSSSS',
            'totalNet',
            'totalBOB',
            'totalTbank'
        ))->setPaper('a4', 'landscape');

        // Return the PDF download
        return $pdf->download('Salary-Report.pdf');
    }


    public function exportSalaryExcel(Request $request)
    {
        return Excel::download(new SalaryPaySlipExport($request), 'salary-report.xlsx');
    }
    public function printSalary(Request $request)
    {
        $salaries = FinalPaySlip::filter($request)->get();

        // Initialize variables to store totals
        $totalBasic = $totalGross = $totalOver = $totalAdd = $totalCash = $totalCorporate = $totalCritical = $totalDifficulty = $totalHouse = $totalMedical = 0;
        $totalAdvSalary = $totalAdvStaff = $totalGSLI = $totalHealth = $totalPF = $totalSalaryTax = $totalSamsundDed = $totalSIFA = $totalSSSS = $totalNet = $totalBOB =  $totalTbank = 0;

        // Iterate through salaries once to calculate all totals
        foreach ($salaries as $salary) {
            $details = $salary->details;

            // Basic Pay and Gross Pay (assuming they are the same for simplicity)
            $totalBasic += $details['basic_pay'] ?? 0;
            $totalGross += $details['basic_pay'] ?? 0;

            // Overtime
            $totalOver += $details['overtime_hours'] ?? 0;

            // Allowances
            $totalAdd += $details['allowances']['Add. Work Allowance'] ?? 0;
            $totalCash += $details['allowances']['Cash Allowance'] ?? 0;
            $totalCorporate += $details['allowances']['Corporate Allowance'] ?? 0;
            $totalCritical += $details['allowances']['Critical Allowance'] ?? 0;
            $totalDifficulty += $details['allowances']['Difficulty Allowance'] ?? 0;
            $totalHouse += $details['allowances']['House Allowance'] ?? 0;
            $totalMedical += $details['allowances']['Medical Allowance'] ?? 0;

            // Deductions
            $totalAdvSalary += $details['deductions']['Adv. Salary'] ?? 0;
            $totalAdvStaff += $details['deductions']['Adv. Salary'] ?? 0; // This seems to be the same as 'Adv. Salary', but if not, adjust
            $totalGSLI += $details['deductions']['GSLI'] ?? 0;
            $totalHealth += $details['deductions']['H/Tax'] ?? 0;
            $totalPF += $details['deductions']['PF Contr'] ?? 0;
            $totalSalaryTax += $details['deductions']['Salary Tax'] ?? 0;
            $totalSamsundDed += $details['deductions']['Samsung Ded'] ?? 0;
            $totalSIFA += $details['deductions']['SIFA'] ?? 0;
            $totalSSSS += $details['deductions']['SSSS'] ?? 0;
            $totalNet += $details['net_pay'] ?? 0;
            $totalBOB += $details['Loan BOB'] ?? 0;
            $totalTbank += $details['Loan TBank'] ?? 0;
        }

        // Generate the PDF view and pass the data
        $pdf = Pdf::loadView('export-report.salary-report-pdf', compact(
            'salaries',
            'totalBasic',
            'totalGross',
            'totalOver',
            'totalAdd',
            'totalCash',
            'totalCorporate',
            'totalCritical',
            'totalDifficulty',
            'totalHouse',
            'totalMedical',
            'totalAdvSalary',
            'totalAdvStaff',
            'totalGSLI',
            'totalHealth',
            'totalPF',
            'totalSalaryTax',
            'totalSamsundDed',
            'totalSIFA',
            'totalSSSS',
            'totalNet',
            'totalBOB',
            'totalTbank'
        ))->setPaper('a4', 'landscape');


        // Return the PDF as a stream to display it in the browser
        return $pdf->stream('Salary-Report.pdf');
    }
}
