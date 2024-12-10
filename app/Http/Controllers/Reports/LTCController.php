<?php

namespace App\Http\Controllers\Reports;

use App\Exports\LTCExport;
use App\Http\Controllers\Controller;
use App\Models\LeaveTravelConcession;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LTCController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:report/ltc-report,view')->only('index');
        $this->middleware('permission:report/ltc-report,create')->only('store');
        $this->middleware('permission:report/ltc-report,edit')->only('update');
        $this->middleware('permission:report/ltc-report,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $ltcs=LeaveTravelConcession::where('status','=',3)->filter($request)->paginate(config('global.pagination'))->withQueryString();

               
        return view('report.ltc.index', compact( 'privileges','ltcs'));
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
    public function exportLTC(Request $request)
    {

        // Load all bookings with their dzongkhag names
        $ltcs = LeaveTravelConcession::filter($request)->get();



        // Generate the PDF view and pass the data
        $pdf = Pdf::loadView('export-report.ltc-pdf', compact('ltcs'))->setPaper('a4', 'landscape');;

        // Return the PDF download
        return $pdf->download('LTC-Report.pdf');
    }

    public function exportLTCExcel(Request $request)
    {
        return Excel::download(new LTCExport($request), 'ltc-report.xlsx');
    }
    public function printLTC(Request $request)
    {
        $ltcs = LeaveTravelConcession::filter($request)->get();

        // Generate the PDF view and pass the data
        $pdf = Pdf::loadView('export-report.ltc-pdf', compact('ltcs'))->setPaper('a4', 'landscape');;


        // Return the PDF as a stream to display it in the browser
        return $pdf->stream('LTC.pdf');
    }

}
