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
        $pfDeductions = FinalPaySlip::filter($request)->paginate(config('global.pagination'))->withQueryString();
       
        $employee = employeeList();

        return view('report.pf-report.index', compact('privileges', 'employee', 'pfDeductions'));
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

        // Load all bookings with their dzongkhag names
        $pfDeductions = FinalPaySlip::filter($request)->get();



        // Generate the PDF view and pass the data
        $pdf = Pdf::loadView('export-report.pf-report-pdf', compact('pfDeductions'))->setPaper('a4', 'landscape');;

        // Return the PDF download
        return $pdf->download('PF-Deduction.pdf');
    }

    public function exportPFExcel(Request $request)
    {
        return Excel::download(new PFExport($request), 'pf-report.xlsx');
    }
    public function printPF(Request $request)
    {
        $pfDeductions = FinalPaySlip::filter($request)->get();

        // Generate the PDF view and pass the data
        $pdf = Pdf::loadView('export-report.pf-report-pdf', compact('pfDeductions'))->setPaper('a4', 'landscape');;


        // Return the PDF as a stream to display it in the browser
        return $pdf->stream('PF-Deduction.pdf');
    }
}
