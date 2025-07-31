<?php

namespace App\Http\Controllers\Reports;

use App\Exports\TaxScheduleExport;
use App\Http\Controllers\Controller;
use App\Models\FinalPaySlip;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class TaxScheduleReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:report/tax-schedule-report,view')->only('index');
        $this->middleware('permission:report/tax-schedule-report,create')->only('store');
        $this->middleware('permission:report/tax-schedule-report,edit')->only('update');
        $this->middleware('permission:report/tax-schedule-report,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $taxSchedules = FinalPaySlip::filter($request)->paginate(config('global.pagination'))->withQueryString();
        $employee = employeeList();


        return view('report.tax-schedule-report.index', compact('privileges', 'employee', 'taxSchedules'));
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
    public function exportTaxSchedule(Request $request)
    {


        $taxSchedules = FinalPaySlip::filter($request)->get();
        $totalAllowances = 0;

        foreach ($taxSchedules as $schedule) {
            $allowances = $schedule->details['allowances'] ?? [];
            $totalAllowances += array_sum(is_array($allowances) ? $allowances : []);
        }

        $totalHealth = $taxSchedules->sum(function ($health) {
            return $health->details['deductions']['H/Tax'] ?? 0;
        });
        $totalSalaryTax = $taxSchedules->sum(function ($salary) {
            return $salary->details['deductions']['Salary Tax'] ?? 0;
        });
        $totalBasic = $taxSchedules->sum(function ($salary) {
            return $salary->details['basic_pay'] ?? 0;
        });
        $totalGIS = $taxSchedules->sum(function ($salary) {
            return $salary->details['deductions']['GSLI'] ?? 0;
        });
        $totalNet = $taxSchedules->sum(function ($salary) {
            return $salary->details['net_pay'] ?? 0;
        });
        $totalPF = $taxSchedules->sum(function ($salary) {
            return $salary->details['deductions']['pf'] ?? 0;
        });

        $totalGross = $taxSchedules->sum(function ($salary) {
            return $salary->details['gross_pay'] ?? 0;
        });



        // Generate the PDF view and pass the data
        $pdf = Pdf::loadView('export-report.tax-schedule-report-pdf', compact('totalGross', 'taxSchedules', 'totalHealth', 'totalSalaryTax', 'totalGIS', 'totalNet', 'totalBasic', 'totalAllowances', 'totalPF'))->setPaper('a4', 'landscape');


        // Return the PDF download
        return $pdf->download('TaxSchedule-Deduction.pdf');
    }

    public function exportTaxScheduleExcel(Request $request)
    {
        return Excel::download(new TaxScheduleExport($request), 'tax-schedule-report.xlsx');
    }
    public function printTaxSchedule(Request $request)
    {

        $taxSchedules = FinalPaySlip::filter($request)->get();
        $totalAllowances = 0;

        foreach ($taxSchedules as $schedule) {
            $allowances = $schedule->details['allowances'] ?? [];
            $totalAllowances += array_sum(is_array($allowances) ? $allowances : []);
        }

        $totalHealth = $taxSchedules->sum(function ($health) {
            return $health->details['deductions']['H/Tax'] ?? 0;
        });
        $totalSalaryTax = $taxSchedules->sum(function ($salary) {
            return $salary->details['deductions']['Salary Tax'] ?? 0;
        });
        $totalBasic = $taxSchedules->sum(function ($salary) {
            return $salary->details['basic_pay'] ?? 0;
        });
        $totalGIS = $taxSchedules->sum(function ($salary) {
            return $salary->details['deductions']['GSLI'] ?? 0;
        });
        $totalPF = $taxSchedules->sum(function ($salary) {
            return $salary->details['deductions']['pf'] ?? 0;
        });
        $totalNet = $taxSchedules->sum(function ($salary) {
            return $salary->details['net_pay'] ?? 0;
        });

        $totalGross = $taxSchedules->sum(function ($salary) {
            return $salary->details['gross_pay'] ?? 0;
        });



        // Generate the PDF view and pass the data
        $pdf = Pdf::loadView('export-report.tax-schedule-report-pdf', compact('totalGross', 'taxSchedules', 'totalHealth', 'totalSalaryTax', 'totalGIS', 'totalNet', 'totalBasic', 'totalAllowances', 'totalPF'))->setPaper('a4', 'landscape');


        // Return the PDF as a stream to display it in the browser
        return $pdf->stream('TaxSchedule-Deduction.pdf');
    }
}
