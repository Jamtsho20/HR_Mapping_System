<?php

namespace App\Http\Controllers\Reports;

use App\Exports\PFExport;
use App\Http\Controllers\Controller;
use App\Models\FinalPaySlip;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PFReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:report/pf-report,view')->only('index');
        $this->middleware('permission:report/pf-report,create')->only('store');
        $this->middleware('permission:report/pf-report,edit')->only('update');
        $this->middleware('permission:report/pf-report,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();

        $pfDeductions = FinalPaySlip::filter($request)
            ->with(['employee.empJob.empType'], ['employee.empJob']) // Load necessary relationships
            ->paginate(config('global.pagination'))
            ->withQueryString();

        // Collection to hold data with calculated PF
        $pfDeductionsWithPF = $pfDeductions->map(function ($pf) {
            // Decode the details JSON
            $details = $pf->details;

            // Ensure relationships are not null
            $empTypeId = $pf->employee?->empJob?->empType?->id;

            // Initialize PF amount
            $pfAmount = 0;

            if ($empTypeId && isset($details['basic_pay'])) {
                // Calculate PF based on empType->id and basic_pay
                $basicPay = $details['basic_pay'];
                $pfAmount = $this->calculatePF($basicPay, $empTypeId);
            } else {
                // Log missing data cases
                logger()->warning("Unable to calculate PF for FinalPaySlip ID: {$pf->id}");
            }


            // Return data structure with calculated PF and original slip details
            return [
                'id' => $pf->id,
                'employee_name' => $pf->employee->name ?? 'N/A',
                'pf_number' => $pf->employee->empJob->pf_number ?? 'N/A',
                'CID' => $pf->employee->cid_no ?? 'N/A',
                'basic_pay' => $details['basic_pay'] ?? 0,
                'employer_pf_amount' => $pfAmount,
                'net_pay' => $details['net_pay'] ?? 0,
                'details' => $details, // Include the entire details structure if needed
                'total' => $pfAmount + ($details['deductions']['PF Contr'] ?: 0) // total of PFs
            ];
        });



        $employee = employeeList();

        // Pass the calculated data to the view
        return view('report.pf-report.index', compact('privileges', 'employee', 'pfDeductionsWithPF', 'pfDeductions'));
    }


    public function calculatePF($basicPay,  $empTypeId)
    {
        // Define PF rate or logic based on empType->id
        $pfRates = [
            2 => 0.10, // regular
            3 => 0, // probation
            4 => 0.15, // executive long term contract
            5 => 0.10, // Long-term Contract(Technical Staff Group 2 Level)
            6 => 0.05, // general support staff long term contract
            7 => 0.05, //Short Term Contract
            8 => 0,
            9 => 0.10, //longterm T2
        ];

        //calculate
        $rate = $pfRates[$empTypeId];

        return $basicPay * $rate;
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
    public function exportPF(Request $request)
    {

        $pfDeductions = FinalPaySlip::filter($request)
            ->with(['employee.empJob.empType'])
            ->get();

        $pfDeductionsWithPF = $pfDeductions->map(
            function ($pf) {
                $details = is_array($pf->details) ? $pf->details : json_decode(json_encode($pf->details), true);
                $empTypeId = $pf->employee?->empJob?->empType?->id;

                $pfAmount = 0;
                if ($empTypeId && isset($details['basic_pay'])) {
                    $basicPay = $details['basic_pay'];
                    $pfAmount = $this->calculatePF($basicPay, $empTypeId);
                    $details['deductions']['pf'] = $pfAmount;
                }

                return [
                    'id' => $pf->id,
                    'employee_name' => $pf->employee->name ?? 'N/A',
                    'pf_number' => $pf->employee->empJob->pf_number ?? 'N/A',
                    'CID' => $pf->employee->cid_no ?? 'N/A',
                    'basic_pay' => $details['basic_pay'] ?? 0,
                    'employer_pf_amount' => $pfAmount,
                    'net_pay' => $details['net_pay'] ?? 0,
                    'details' => $details, // Include the entire details structure if needed
                    'total' => $pfAmount + ($details['deductions']['PF Contr'] ?: 0) // total of PFs
                ];
            }
        );



        $filteredPF = $pfDeductionsWithPF->filter(function ($pf) {
            return ($pf['details']['deductions']['PF Contr'] ?? 0) > 0;
        });

        $totalEmployeeAmount = $filteredPF->sum(function ($pf) {
            return $pf['details']['deductions']['PF Contr'] ?? 0;
        });

        $totalEmployerAmount = $filteredPF->sum('employer_pf_amount');


        // Generate the PDF view and pass the data
        $pdf = Pdf::loadView('export-report.pf-report-pdf', compact('pfDeductions', 'pfDeductionsWithPF', 'totalEmployeeAmount', 'totalEmployerAmount'))->setPaper('a4', 'landscape');


        // Return the PDF download
        return $pdf->download('PF-Deduction.pdf');
    }

    public function exportPFExcel(Request $request)
    {
        $pfDeductions = FinalPaySlip::filter($request)
            ->with(['employee.empJob.empType'])
            ->get();

        $pfDeductionsWithPF = $pfDeductions->map(function ($pf) {
            $details = $pf->details;
            $empTypeId = $pf->employee?->empJob?->empType?->id;

            $pfAmount = 0;
            if ($empTypeId && isset($details['basic_pay'])) {
                $basicPay = $details['basic_pay'];
                $pfAmount = $this->calculatePF($basicPay, $empTypeId);
                $details['deductions']['pf'] = $pfAmount;
            }

            return [
                'id' => $pf->id,
                'employee_name' => $pf->employee->name ?? 'N/A',
                'pf_number' => $pf->employee->empJob->pf_number ?? 'N/A',
                'CID' => $pf->employee->cid_no ?? 'N/A',
                'basic_pay' => $details['basic_pay'] ?? 0,
                'employer_pf_amount' => $pfAmount,
                'net_pay' => $details['net_pay'] ?? 0,
                'details' => $details, // Include the entire details structure if needed
                'total' => $pfAmount + ($details['deductions']['PF Contr'] ?: 0) // total of PFs
            ];
        });

        return Excel::download(new PFExport($pfDeductionsWithPF), 'pf-report.xlsx');
    }

    public function printPF(Request $request)
    {
        $pfDeductions = FinalPaySlip::filter($request)
            ->with(['employee.empJob.empType'])
            ->get();

        $pfDeductionsWithPF = $pfDeductions->map(
            function ($pf) {
                $details = is_array($pf->details) ? $pf->details : json_decode(json_encode($pf->details), true);
                $empTypeId = $pf->employee?->empJob?->empType?->id;

                $pfAmount = 0;
                if ($empTypeId && isset($details['basic_pay'])) {
                    $basicPay = $details['basic_pay'];
                    $pfAmount = $this->calculatePF($basicPay, $empTypeId);
                    $details['deductions']['pf'] = $pfAmount;
                }

                return [
                    'id' => $pf->id,
                    'employee_name' => $pf->employee->name ?? 'N/A',
                    'pf_number' => $pf->employee->empJob->pf_number ?? 'N/A',
                    'CID' => $pf->employee->cid_no ?? 'N/A',
                    'basic_pay' => $details['basic_pay'] ?? 0,
                    'employer_pf_amount' => $pfAmount,
                    'net_pay' => $details['net_pay'] ?? 0,
                    'details' => $details, // Include the entire details structure if needed
                    'total' => $pfAmount + ($details['deductions']['PF Contr'] ?: 0) // total of PFs
                ];
            }
        );



        $filteredPF = $pfDeductionsWithPF->filter(function ($pf) {
            return ($pf['details']['deductions']['PF Contr'] ?? 0) > 0;
        });

        $totalEmployeeAmount = $filteredPF->sum(function ($pf) {
            return $pf['details']['deductions']['PF Contr'] ?? 0;
        });

        $totalEmployerAmount = $filteredPF->sum('employer_pf_amount');

        // dd($totalEmployerAmount);


        // Generate the PDF view and pass the data
        $pdf = Pdf::loadView('export-report.pf-report-pdf', compact('pfDeductions', 'pfDeductionsWithPF', 'totalEmployeeAmount', 'totalEmployerAmount'))->setPaper('a4', 'landscape');

        // Return the PDF as a stream to display it in the browser
        return $pdf->stream('PF-Deduction.pdf');
    }
}
