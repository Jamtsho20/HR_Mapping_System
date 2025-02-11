<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\FinalPaySlip;
use App\Models\PaySlipDetail;
use App\Models\PaySlipDetailView;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Finally_;

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

        // // Get current and previous month details
        $currentMonth = now()->format('Y-m-01'); // First day of the current month
        $previousMonth = now()->subMonth()->format('Y-m-01'); // First day of the previous month



        $current = PaySlipDetailView::filter($request)->get()->map(function ($item) {
            // Initialize total_allowance to 0 for each item
            $item->total_allowance = 0;

            // Iterate over each attribute (column) of the item
            foreach ($item->getAttributes() as $key => $value) {
                // Check if the column name ends with '_Allowance' and is numeric
                if (str_ends_with($key, '_Allowance') && is_numeric($value)) {
                    $item->total_allowance += $value; // Add the allowance to total_allowance
                }
            }

            return $item; // Return the modified item
        });


        $previous = FinalPaySlip::filter($request)->where('for_month', $previousMonth)->get()->map(function ($item) {
            // Check if 'details' is a JSON string or already an array
            $details = is_string($item->details) ? json_decode($item->details, true) : $item->details;

            // Initialize the total allowance for this record
            $totalAllowances = 0;

            // Check if 'allowances' exists in the decoded details (or the array directly)
            if (isset($details['allowances'])) {
                // Sum all the allowance values in the 'allowances' array
                foreach ($details['allowances'] as $allowance => $amount) {
                    // Only add numeric values
                    if (is_numeric($amount)) {
                        $totalAllowances += $amount;
                    }
                }
            }

            // Add the total allowance to the item
            $item->total_allowances = $totalAllowances;

            return $item;
        });








        // return view('report.pay-comparision-report.index', compact('privileges', 'payslips', 'payslipData', 'employee', 'currentMonthName', 'previousMonthName'));
        return view('report.pay-comparision-report.index', compact('privileges', 'current', 'previous', 'employee', 'currentMonth', 'previousMonth'));
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



        $current = PaySlipDetailView::filter($request)->get()->map(function ($item) {
            // Initialize total_allowance to 0 for each item
            $item->total_allowance = 0;

            // Iterate over each attribute (column) of the item
            foreach ($item->getAttributes() as $key => $value) {
                // Check if the column name ends with '_Allowance' and is numeric
                if (str_ends_with($key, '_Allowance') && is_numeric($value)) {
                    $item->total_allowance += $value; // Add the allowance to total_allowance
                }
            }

            return $item; // Return the modified item
        });


        $previous = FinalPaySlip::filter($request)->where('for_month', $previousMonth)->get()->map(function ($item) {
            // Check if 'details' is a JSON string or already an array
            $details = is_string($item->details) ? json_decode($item->details, true) : $item->details;

            // Initialize the total allowance for this record
            $totalAllowances = 0;

            // Check if 'allowances' exists in the decoded details (or the array directly)
            if (isset($details['allowances'])) {
                // Sum all the allowance values in the 'allowances' array
                foreach ($details['allowances'] as $allowance => $amount) {
                    // Only add numeric values
                    if (is_numeric($amount)) {
                        $totalAllowances += $amount;
                    }
                }
            }

            // Add the total allowance to the item
            $item->total_allowances = $totalAllowances;

            return $item;
        });









        // Generate the PDF view and pass the data
        $pdf = Pdf::loadView('export-report.pay-comparision-report-pdf', compact('current', 'previous', 'currentMonth', 'previousMonth'))->setPaper('a4', 'landscape');

        // Return the PDF download
        return $pdf->download('Pay-Comparision-Report.pdf');
    }
    public function printPayComparision(Request $request)
    {
        // Get current and previous month details
        $currentMonth = now()->format('Y-m-01'); // First day of the current month
        $previousMonth = now()->subMonth()->format('Y-m-01'); // First day of the previous month



        $current = PaySlipDetailView::filter($request)->get()->map(function ($item) {
            // Initialize total_allowance to 0 for each item
            $item->total_allowance = 0;

            // Iterate over each attribute (column) of the item
            foreach ($item->getAttributes() as $key => $value) {
                // Check if the column name ends with '_Allowance' and is numeric
                if (str_ends_with($key, '_Allowance') && is_numeric($value)) {
                    $item->total_allowance += $value; // Add the allowance to total_allowance
                }
            }

            return $item; // Return the modified item
        });


        $previous = FinalPaySlip::filter($request)->where('for_month', $previousMonth)->get()->map(function ($item) {
            // Check if 'details' is a JSON string or already an array
            $details = is_string($item->details) ? json_decode($item->details, true) : $item->details;

            // Initialize the total allowance for this record
            $totalAllowances = 0;

            // Check if 'allowances' exists in the decoded details (or the array directly)
            if (isset($details['allowances'])) {
                // Sum all the allowance values in the 'allowances' array
                foreach ($details['allowances'] as $allowance => $amount) {
                    // Only add numeric values
                    if (is_numeric($amount)) {
                        $totalAllowances += $amount;
                    }
                }
            }

            // Add the total allowance to the item
            $item->total_allowances = $totalAllowances;

            return $item;
        });









        // Generate the PDF view and pass the data
        $pdf = Pdf::loadView('export-report.pay-comparision-report-pdf', compact('current', 'previous', 'currentMonth', 'previousMonth'))->setPaper('a4', 'landscape');


        // Return the PDF as a stream to display it in the browser
        return $pdf->stream('Pay-Comparision-Report.pdf');
    }
}
