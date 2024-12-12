<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayComparisionReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:report/pay-comparision-report,view')->only('index');
        $this->middleware('permission:report/pay-comparision-report,create')->only('store');
        $this->middleware('permission:report/pay-comparision-report,edit')->only('update');
        $this->middleware('permission:report/pay-comparision-report,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $employee = employeeList();

        // Get current and previous month details
        $currentMonth = now()->format('Y-m-01'); // First day of the current month
        $previousMonth = now()->subMonth()->format('Y-m-01'); // First day of the previous month

        // Query data for the two months

        $payslips = DB::table('final_pay_slips')
            ->join('mas_employees', 'final_pay_slips.mas_employee_id', '=', 'mas_employees.id') // Specify the join condition
            ->when($request->has('employee_id'), function ($query) use ($request) {
                return $query->where('final_pay_slips.mas_employee_id', $request->employee_id);
            })
            ->whereIn('final_pay_slips.for_month', [$currentMonth, $previousMonth]) // Filter for current and previous month
            ->select(
                'final_pay_slips.*',
                'mas_employees.name as employee_name', // Include employee name for easier access
                'mas_employees.username as employee_code'
            )
            ->paginate(config('global.pagination')) // Paginate the results
            ->withQueryString();

        //get the name of month and year
        $current = $payslips->where('for_month', now()->format('Y-m-01'))->first();
        $previous = $payslips->where('for_month', now()->subMonth()->format('Y-m-01'))->first();
        $currentMonthName = Carbon::parse($current->for_month)->format('F Y');
        $previousMonthName = Carbon::parse($previous->for_month)->format('F Y');


        $payslipData = $payslips->groupBy('mas_employee_id')->map(function ($records) {
            $current = $records->where('for_month', now()->format('Y-m-01'))->first();
            $previous = $records->where('for_month', now()->subMonth()->format('Y-m-01'))->first();


            return [
                'employee_id' => $current->employee_code ?? $previous->employee_code,
                'employee_name' => $current->employee_name ?? $previous->employee_name,
                'current_basic' => json_decode($current->details ?? '{}', true)['basic_pay'] ?? 0,
                'previous_basic' => json_decode($previous->details ?? '{}', true)['basic_pay'] ?? 0,
                'current_gross' => json_decode($current->details ?? '{}', true)['gross_pay'] ?? 0,
                'previous_gross' => json_decode($previous->details ?? '{}', true)['gross_pay'] ?? 0,
                'current_allowances' => array_sum(json_decode($current->details ?? '{}', true)['allowances'] ?? []),
                'previous_allowances' => array_sum(json_decode($previous->details ?? '{}', true)['allowances'] ?? []),
                'basic_diff' => ($current->basic_pay ?? 0) - ($previous->basic_pay ?? 0),
                'allowances_diff' => ($current->current_allowances ?? 0) - ($previous->previous_allowances ?? 0),
                'gross_diff' => ($current->gross_pay ?? 0) - ($previous->gross_pay ?? 0),

            ];
        });




        return view('report.pay-comparision-report.index', compact('privileges', 'payslips', 'payslipData', 'employee', 'currentMonthName', 'previousMonthName'));
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
    public function exportPayComparision(Request $request)
    {

        // Get current and previous month details
        $currentMonth = now()->format('Y-m-01'); // First day of the current month
        $previousMonth = now()->subMonth()->format('Y-m-01'); // First day of the previous month

        // Query data for the two months

        $payslips = DB::table('final_pay_slips')
            ->join('mas_employees', 'final_pay_slips.mas_employee_id', '=', 'mas_employees.id') // Specify the join condition
            ->when($request->has('employee_id'), function ($query) use ($request) {
                return $query->where('final_pay_slips.mas_employee_id', $request->employee_id);
            })
            ->whereIn('final_pay_slips.for_month', [$currentMonth, $previousMonth]) // Filter for current and previous month
            ->select(
                'final_pay_slips.*',
                'mas_employees.name as employee_name', // Include employee name for easier access
                'mas_employees.username as employee_code'
            )
            ->get();

        //get the name of month and year
        $current = $payslips->where('for_month', now()->format('Y-m-01'))->first();
        $previous = $payslips->where('for_month', now()->subMonth()->format('Y-m-01'))->first();
        $currentMonthName = Carbon::parse($current->for_month)->format('F Y');
        $previousMonthName = Carbon::parse($previous->for_month)->format('F Y');

        $payslipData = $payslips->groupBy('mas_employee_id')->map(function ($records) {
            $current = $records->where('for_month', now()->format('Y-m-01'))->first();
            $previous = $records->where('for_month', now()->subMonth()->format('Y-m-01'))->first();


            return [
                'employee_id' => $current->employee_code ?? $previous->employee_code,
                'employee_name' => $current->employee_name ?? $previous->employee_name,
                'current_basic' => json_decode($current->details ?? '{}', true)['basic_pay'] ?? 0,
                'previous_basic' => json_decode($previous->details ?? '{}', true)['basic_pay'] ?? 0,
                'current_gross' => json_decode($current->details ?? '{}', true)['gross_pay'] ?? 0,
                'previous_gross' => json_decode($previous->details ?? '{}', true)['gross_pay'] ?? 0,
                'current_allowances' => array_sum(json_decode($current->details ?? '{}', true)['allowances'] ?? []),
                'previous_allowances' => array_sum(json_decode($previous->details ?? '{}', true)['allowances'] ?? []),
                'basic_diff' => ($current->basic_pay ?? 0) - ($previous->basic_pay ?? 0),
                'allowances_diff' => ($current->current_allowances ?? 0) - ($previous->previous_allowances ?? 0),
                'gross_diff' => ($current->gross_pay ?? 0) - ($previous->gross_pay ?? 0),

            ];
        });




        // Generate the PDF view and pass the data
        $pdf = Pdf::loadView('export-report.pay-comparision-report-pdf', compact('payslipData', 'currentMonthName', 'previousMonthName'))->setPaper('a4', 'landscape');;

        // Return the PDF download
        return $pdf->download('Pay-Comparision-Report.pdf');
    }
}
