<?php

namespace App\Http\Controllers\Reports;

use App\Exports\SifaExport;
use App\Http\Controllers\Controller;
use App\Models\FinalPaySlip;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SIFAContributionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:report/sifa-contribution,view')->only('index');
        $this->middleware('permission:report/sifa-contribution,create')->only('store');
        $this->middleware('permission:report/sifa-contribution,edit')->only('update');
        $this->middleware('permission:report/sifa-contribution,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $sifaContributions = FinalPaySlip::filter($request)->paginate(30)->withQueryString();
        $employee = employeeList();

        return view('report.sifa-contribution.index', compact('privileges','employee', 'sifaContributions'));
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
    public function exportSifa(Request $request)
    {

        // Load all bookings with their dzongkhag names
        $sifaContributions = FinalPaySlip::filter($request)->get();



        // Generate the PDF view and pass the data
        $pdf = Pdf::loadView('export-report.sifa-contribution-report-pdf', compact('sifaContributions'))->setPaper('a4', 'landscape');;

        // Return the PDF download
        return $pdf->download('Sifa-Contribution.pdf');
    }

    public function exportSifaExcel(Request $request)
    {
        return Excel::download(new SifaExport($request), 'sifa-contribution-report.xlsx');
    }
    public function printSifa(Request $request)
    {
        $sifaContributions = FinalPaySlip::filter($request)->get();

        // Generate the PDF view and pass the data
        $pdf = Pdf::loadView('export-report.sifa-contribution-report-pdf', compact('sifaContributions'))->setPaper('a4', 'landscape');;


        // Return the PDF as a stream to display it in the browser
        return $pdf->stream('Sifa-Contribution.pdf');
    }
}
