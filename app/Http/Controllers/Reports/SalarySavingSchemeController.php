<?php

namespace App\Http\Controllers\Reports;

use App\Exports\SSSExport;
use App\Http\Controllers\Controller;
use App\Models\EmployeeSalarySaving;
use App\Models\FinalPaySlip;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SalarySavingSchemeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:report/salary-saving-scheme,view')->only('index');
        $this->middleware('permission:report/salary-saving-scheme,create')->only('store');
        $this->middleware('permission:report/salary-saving-scheme,edit')->only('update');
        $this->middleware('permission:report/salary-saving-scheme,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $employee = employeeList();

        $sss = EmployeeSalarySaving::filter($request)
            ->paginate(config('global.pagination'))
            ->withQueryString();



        // Collection to hold data with calculated PF


        return view('report.salary-saving-scheme.index', compact('privileges', 'sss', 'employee'));
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
    public function exportSSS(Request $request)
    {

        $sss = EmployeeSalarySaving::filter($request)
            ->paginate(config('global.pagination'))
            ->withQueryString();


        // Generate the PDF view and pass the data
        $pdf = Pdf::loadView('export-report.sss-report-pdf', compact('sss'))->setPaper('a4', 'landscape');

        // Return the PDF download
        return $pdf->download('SSS.pdf');
    }

    public function exportSSSExcel(Request $request)
    {


        return Excel::download(new SSSExport($request), 'sss-report.xlsx');
    }

    public function printSSS(Request $request)
    {
        // Load all bookings with their dzongkhag names
        $sss = EmployeeSalarySaving::filter($request)
            ->paginate(config('global.pagination'))
            ->withQueryString();
        // Generate the PDF view and pass the data
        $pdf = Pdf::loadView('export-report.sss-report-pdf', compact('sss'))->setPaper('a4', 'landscape');

        // Return the PDF as a stream to display it in the browser
        return $pdf->stream('sss.pdf');
    }
}
