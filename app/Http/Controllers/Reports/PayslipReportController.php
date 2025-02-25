<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\FinalPaySlip;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PayslipReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:report/payslip-report,view')->only('index');
        $this->middleware('permission:report/payslip-report,create')->only('store');
        $this->middleware('permission:report/payslip-report,edit')->only('update');
        $this->middleware('permission:report/payslip-report,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $employee = employeeList();

        $payslips = FinalPaySlip::filter($request)
            ->paginate(config('global.pagination'))
            ->withQueryString();

        // Collection to hold data with calculated PF


        return view('report.payslip.index', compact('privileges', 'payslips', 'employee'));
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

    public function exportPayslip(Request $request)
    {
        $request->merge([
            'year' => $request->get('year', \Carbon\Carbon::now()->format('Y-m'))
        ]);

        // Load all bookings with their dzongkhag names
        $payslips = FinalPaySlip::filter($request)->get();



        $totalEmployeeAmount = $payslips->sum(function ($pf) {
            return $pf->details['deductions']['PF Contr'] ?? 0;
        });

        $totalEmployerAmount = $payslips->sum('employer_pf_amount');

        // Generate the PDF view and pass the data
        $pdf = Pdf::loadView('export-report.payslip-report-pdf', compact('payslips'));


        // Return the PDF download
        // return $pdf->download('PF-Deduction.pdf');
        return $pdf->stream('PF-Deduction.pdf');
    }
}
