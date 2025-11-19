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

        $request->merge([
            'year' => $request->get('year', \Carbon\Carbon::now()->format('Y-m'))
        ]);

        $salaries = FinalPaySlip::whereHas('employee', function ($q) {
            $q->where('is_active', 1);
        })->filter($request)->paginate(config('global.pagination'))->withQueryString();

        return view('report.salary-report.index', compact('privileges', 'salaries', 'employee'));
    }

    // public function exportSalary(Request $request)
    // {
    //     $options = [
    //         'isRemoteEnabled' => true,
    //         'isHtml5ParserEnabled' => true
    //     ];
    //     $salaries = FinalPaySlip::filter($request)->get();

    //     $totalNet = $totalBasic = $totalGross = $totalOver = $totalAdd = $totalCash = $totalCorporate = $totalCritical = $totalDifficulty = $totalHouse = $totalMedical = 0;
    //     $totalAdvSalary = $totalAdvStaff = $totalGSLI = $totalHealth = $totalPF = $totalSalaryTax = $totalSamsundDed = $totalSIFA = $totalSSSS = $totalNet = $totalBOB =  $totalTbank = $totalBnb = $totalNPPF = $totalBDFC = $totalRICB = $totalDPNB = $totalSifaLoan = 0;

    //     // Iterate through salaries once to calculate all totals
    //     foreach ($salaries as $salary) {
    //         $details = $salary->details;

    //         // Basic Pay and Gross Pay (assuming they are the same for simplicity)
    //         $totalBasic += $details['basic_pay'] ?? 0;
    //         $totalGross += $details['gross_pay'] ?? 0;
    //         $totalNet += $details['net_pay'] ?? 0;



    //         // Allowances
    //         $totalAdd += $details['allowances']['Add. Work Allowance'] ?? 0;
    //         $totalCash += $details['allowances']['Cash Allowance'] ?? 0;
    //         $totalCorporate += $details['allowances']['Corporate Allowance'] ?? 0;
    //         $totalCritical += $details['allowances']['Critical Allowance'] ?? 0;
    //         $totalDifficulty += $details['allowances']['Difficulty Allowance'] ?? 0;
    //         $totalHouse += $details['allowances']['House Allowance'] ?? 0;
    //         $totalMedical += $details['allowances']['Medical Allowance'] ?? 0;

    //         // Deductions
    //         $totalAdvSalary += $details['deductions']['Adv. Salary'] ?? 0;
    //         $totalAdvStaff += $details['deductions']['Adv. Salary'] ?? 0; // This seems to be the same as 'Adv. Salary', but if not, adjust
    //         $totalGSLI += $details['deductions']['GSLI'] ?? 0;
    //         $totalHealth += $details['deductions']['H/Tax'] ?? 0;
    //         $totalPF += $details['deductions']['PF Contr'] ?? 0;
    //         $totalSalaryTax += $details['deductions']['Salary Tax'] ?? 0;
    //         $totalSamsundDed += $details['deductions']['Samsung Ded'] ?? 0;
    //         $totalSIFA += $details['deductions']['SIFA'] ?? 0;
    //         $totalSSSS += $details['deductions']['SSSS'] ?? 0;
    //         $totalNet += $details['net_pay'] ?? 0;
    //         $totalBOB += $details['deductions']['Loan BOB'] ?? 0;
    //         $totalTbank += $details['deductions']['Loan TBank'] ?? 0;
    //         $totalBnb += $details['deductions']['Loan BNB'] ?? 0;
    //         $totalNPPF += $details['deductions']['Loan NPPF'] ?? 0;
    //         $totalBDFC += $details['deductions']['Loan BDFC'] ?? 0;
    //         $totalRICB += $details['deductions']['Loan RICB'] ?? 0;
    //         $totalDPNB += $details['deductions']['Loan DPNB'] ?? 0;
    //         $totalSifaLoan += $details['deductions']['Loan SIFA'] ?? 0;
    //     }

    //     // Generate the PDF view and pass the data
    //     $pdf = Pdf::loadView('export-report.salary-report-pdf', compact(
    //         'salaries',
    //         'totalNet',
    //         'totalBasic',
    //         'totalGross',
    //         'totalOver',
    //         'totalAdd',
    //         'totalCash',
    //         'totalCorporate',
    //         'totalCritical',
    //         'totalDifficulty',
    //         'totalHouse',
    //         'totalMedical',
    //         'totalAdvSalary',
    //         'totalAdvStaff',
    //         'totalGSLI',
    //         'totalHealth',
    //         'totalPF',
    //         'totalSalaryTax',
    //         'totalSamsundDed',
    //         'totalSIFA',
    //         'totalSSSS',
    //         'totalNet',
    //         'totalBOB',
    //         'totalTbank',
    //         'totalBnb',
    //         'totalNPPF',
    //         'totalBDFC',
    //         'totalRICB',
    //         'totalDPNB',
    //         'totalSifaLoan'
    //     ))->setOptions($options)->setPaper('a4', 'landscape');

    //     // Return the PDF download
    //     return $pdf->download('Salary-Report.pdf');
    // }
    public function exportSalary(Request $request)
    {
        $options = [
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true
        ];

        // Initialize totals array to store all aggregates
        $totals = [
            'basic' => 0,
            'gross' => 0,
            'net' => 0,
            'over' => 0,
            'allowances' => [
                'add' => 0,
                'cash' => 0,
                'corporate' => 0,
                'critical' => 0,
                'difficulty' => 0,
                'house' => 0,
                'medical' => 0
            ],
            'deductions' => [
                'adv_salary' => 0,
                'gsli' => 0,
                'health' => 0,
                'pf' => 0,
                'salary_tax' => 0,
                'samsung' => 0,
                'sifa' => 0,
                'ssss' => 0,
                'loans' => [
                    'bob' => 0,
                    'tbank' => 0,
                    'bnb' => 0,
                    'nppf' => 0,
                    'bdfc' => 0,
                    'ricb' => 0,
                    'dpnb' => 0,
                    'sifa' => 0
                ]
            ]
        ];

        // Use chunk to process records in batches
        FinalPaySlip::filter($request)
            ->chunk(50, function ($salaries) use (&$totals) {
                foreach ($salaries as $salary) {
                    $details = $salary->details;

                    // Update basic totals
                    $totals['basic'] += $details['basic_pay'] ?? 0;
                    $totals['gross'] += $details['gross_pay'] ?? 0;
                    $totals['net'] += $details['net_pay'] ?? 0;

                    // Update allowances
                    $totals['allowances']['add'] += $details['allowances']['Add. Work Allowance'] ?? 0;
                    $totals['allowances']['cash'] += $details['allowances']['Cash Allowance'] ?? 0;
                    $totals['allowances']['corporate'] += $details['allowances']['Corporate Allowance'] ?? 0;
                    $totals['allowances']['critical'] += $details['allowances']['Critical Allowance'] ?? 0;
                    $totals['allowances']['difficulty'] += $details['allowances']['Difficulty Allowance'] ?? 0;
                    $totals['allowances']['house'] += $details['allowances']['House Allowance'] ?? 0;
                    $totals['allowances']['medical'] += $details['allowances']['Medical Allowance'] ?? 0;

                    // Update deductions
                    $totals['deductions']['adv_salary'] += $details['deductions']['Adv. Salary'] ?? 0;

                    $totals['deductions']['gsli'] += $details['deductions']['GSLI'] ?? 0;
                    $totals['deductions']['health'] += $details['deductions']['H/Tax'] ?? 0;
                    $totals['deductions']['pf'] += $details['deductions']['PF Contr'] ?? 0;
                    $totals['deductions']['salary_tax'] += $details['deductions']['Salary Tax'] ?? 0;
                    $totals['deductions']['samsung'] += $details['deductions']['Samsung Ded'] ?? 0;
                    $totals['deductions']['sifa'] += $details['deductions']['SIFA'] ?? 0;
                    $totals['deductions']['ssss'] += $details['deductions']['SSSS'] ?? 0;

                    // Update loan deductions
                    $totals['deductions']['loans']['bob'] += $details['deductions']['Loan BOB'] ?? 0;
                    $totals['deductions']['loans']['tbank'] += $details['deductions']['Loan TBank'] ?? 0;
                    $totals['deductions']['loans']['bnb'] += $details['deductions']['Loan BNB'] ?? 0;
                    $totals['deductions']['loans']['nppf'] += $details['deductions']['Loan NPPF'] ?? 0;
                    $totals['deductions']['loans']['bdfc'] += $details['deductions']['Loan BDFC'] ?? 0;
                    $totals['deductions']['loans']['ricb'] += $details['deductions']['Loan RICB'] ?? 0;
                    $totals['deductions']['loans']['dpnb'] += $details['deductions']['Loan DPNB'] ?? 0;
                    $totals['deductions']['loans']['sifa'] += $details['deductions']['Loan SIFA'] ?? 0;
                }
            });

        // Generate PDF with chunk processing for salary records
        $pdf = Pdf::loadView('export-report.salary-report-pdf', [
            'salaries' => FinalPaySlip::whereHas('employee', function ($q) {
                $q->where('is_active', 1);
            })->filter($request)->cursor(),
            'totals' => $totals
        ])->setOptions($options)->setPaper('a4', 'landscape');

        return $pdf->download('Salary-Report.pdf');
    }


    public function exportSalaryExcel(Request $request)
    {
        return Excel::download(new SalaryPaySlipExport($request), 'salary-report.xlsx');
    }
    public function printSalary(Request $request)
    {
        $options = [
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true
        ];

        // Initialize totals array to store all aggregates
        $totals = [
            'basic' => 0,
            'gross' => 0,
            'net' => 0,
            'over' => 0,
            'allowances' => [
                'add' => 0,
                'cash' => 0,
                'corporate' => 0,
                'critical' => 0,
                'difficulty' => 0,
                'house' => 0,
                'medical' => 0
            ],
            'deductions' => [
                'adv_salary' => 0,
                'gsli' => 0,
                'health' => 0,
                'pf' => 0,
                'salary_tax' => 0,
                'samsung' => 0,
                'sifa' => 0,
                'ssss' => 0,
                'loans' => [
                    'bob' => 0,
                    'tbank' => 0,
                    'bnb' => 0,
                    'nppf' => 0,
                    'bdfc' => 0,
                    'ricb' => 0,
                    'dpnb' => 0,
                    'sifa' => 0
                ]
            ]
        ];

        // Use chunk to process records in batches
        FinalPaySlip::filter($request)
            ->chunk(50, function ($salaries) use (&$totals) {
                foreach ($salaries as $salary) {
                    $details = $salary->details;

                    // Update basic totals
                    $totals['basic'] += $details['basic_pay'] ?? 0;
                    $totals['gross'] += $details['gross_pay'] ?? 0;
                    $totals['net'] += $details['net_pay'] ?? 0;

                    // Update allowances
                    $totals['allowances']['add'] += $details['allowances']['Add. Work Allowance'] ?? 0;
                    $totals['allowances']['cash'] += $details['allowances']['Cash Allowance'] ?? 0;
                    $totals['allowances']['corporate'] += $details['allowances']['Corporate Allowance'] ?? 0;
                    $totals['allowances']['critical'] += $details['allowances']['Critical Allowance'] ?? 0;
                    $totals['allowances']['difficulty'] += $details['allowances']['Difficulty Allowance'] ?? 0;
                    $totals['allowances']['house'] += $details['allowances']['House Allowance'] ?? 0;
                    $totals['allowances']['medical'] += $details['allowances']['Medical Allowance'] ?? 0;

                    // Update deductions
                    $totals['deductions']['adv_salary'] += $details['deductions']['Adv. Salary'] ?? 0;
                    $totals['deductions']['gsli'] += $details['deductions']['GSLI'] ?? 0;
                    $totals['deductions']['health'] += $details['deductions']['H/Tax'] ?? 0;
                    $totals['deductions']['pf'] += $details['deductions']['PF Contr'] ?? 0;
                    $totals['deductions']['salary_tax'] += $details['deductions']['Salary Tax'] ?? 0;
                    $totals['deductions']['samsung'] += $details['deductions']['Samsung Ded'] ?? 0;
                    $totals['deductions']['sifa'] += $details['deductions']['SIFA'] ?? 0;
                    $totals['deductions']['ssss'] += $details['deductions']['SSSS'] ?? 0;

                    // Update loan deductions
                    $totals['deductions']['loans']['bob'] += $details['deductions']['Loan BOB'] ?? 0;
                    $totals['deductions']['loans']['tbank'] += $details['deductions']['Loan TBank'] ?? 0;
                    $totals['deductions']['loans']['bnb'] += $details['deductions']['Loan BNB'] ?? 0;
                    $totals['deductions']['loans']['nppf'] += $details['deductions']['Loan NPPF'] ?? 0;
                    $totals['deductions']['loans']['bdfc'] += $details['deductions']['Loan BDFC'] ?? 0;
                    $totals['deductions']['loans']['ricb'] += $details['deductions']['Loan RICB'] ?? 0;
                    $totals['deductions']['loans']['dpnb'] += $details['deductions']['Loan DPNB'] ?? 0;
                    $totals['deductions']['loans']['sifa'] += $details['deductions']['Loan SIFA'] ?? 0;
                }
            });

        // Generate PDF with chunk processing for salary records
        $pdf = Pdf::loadView('export-report.salary-report-pdf', [
            'salaries' => FinalPaySlip::filter($request)->cursor(),
            'totals' => $totals
        ])->setOptions($options)->setPaper('a4', 'landscape');

        // Return the PDF as a stream to display it in the browser
        return $pdf->stream('Salary-Report.pdf');
    }
}
