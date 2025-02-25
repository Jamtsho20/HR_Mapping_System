<?php

namespace App\Http\Controllers\Reports;

use App\Exports\GISExport;
use App\Http\Controllers\Controller;
use App\Models\FinalPaySlip;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class GISReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct()
    {
        $this->middleware('permission:report/gis-report,view')->only('index');
        $this->middleware('permission:report/gis-report,create')->only('store');
        $this->middleware('permission:report/gis-report,edit')->only('update');
        $this->middleware('permission:report/gis-report,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $gisDeductions = FinalPaySlip::filter($request)->paginate(config('global.pagination'))->withQueryString();
        $employee = employeeList();

        return view('report.gis-report.index', compact('privileges', 'employee', 'gisDeductions'));
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
    public function exportGIS(Request $request)
    {

        // Load all bookings with their dzongkhag names
        $gisDeductions = FinalPaySlip::filter($request)->get();


        $totalGIS = $gisDeductions->sum(function ($pf) {
            return $pf->details['deductions']['GSLI'] ?? 0;
        });

        // Generate the PDF view and pass the data
        $pdf = Pdf::loadView('export-report.gis-report-pdf', compact('gisDeductions', 'totalGIS'))->setPaper('a4', 'landscape');

        // Return the PDF download
        return $pdf->download('GIS-Deduction.pdf');
    }

    public function exportGISExcel(Request $request)
    {
        return Excel::download(new GISExport($request), 'gis-report.xlsx');
    }
    public function printGIS(Request $request)
    {
        $gisDeductions = FinalPaySlip::filter($request)->get();

        $totalGIS = $gisDeductions->sum(function ($pf) {
            return $pf->details['deductions']['GSLI'] ?? 0;
        });

        // Generate the PDF view and pass the data
        $pdf = Pdf::loadView('export-report.gis-report-pdf', compact('gisDeductions', 'totalGIS'))->setPaper('a4', 'landscape');


        // Return the PDF as a stream to display it in the browser
        return $pdf->stream('GIS-Deduction.pdf');
    }
}
