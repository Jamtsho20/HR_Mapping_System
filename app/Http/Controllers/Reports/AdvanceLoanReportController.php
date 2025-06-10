<?php

namespace App\Http\Controllers\Reports;

use App\Exports\AdvanceLoanExport;
use App\Http\Controllers\Controller;
use App\Models\AdvanceApplication;
use App\Models\MasAdvanceTypes;
use App\Models\MasDepartment;
use App\Models\MasRegion;
use App\Models\MasSection;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AdvanceLoanReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:report/advance-loan-report,view')->only('index');
        $this->middleware('permission:report/advance-loan-report,create')->only('store');
        $this->middleware('permission:report/advance-loan-report,edit')->only('update');
        $this->middleware('permission:report/advance-loan-report,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $employeeLists = employeeList();
        $advanceType = MasAdvanceTypes::whereIn('id', [2, 4])->get();
        $departments = MasDepartment::select('name', 'id')->get();
        $regions = MasRegion::select('name', 'id')->get();
        $sections = MasSection::select('name', 'id')->get();
        $advanceReports = AdvanceApplication::filter($request, false)->whereStatus(3)->paginate(config('global.pagination'))->withQueryString();


        return view('report.advance-loan-report.index', compact('privileges', 'employeeLists', 'departments', 'regions', 'sections', 'advanceReports', 'advanceType'));
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
        $advance = AdvanceApplication::with('advanceType')->findOrFail($id);
        $empDetails = empDetails($advance->created_by);

        return view('report.advance-loan-report.show', compact('advance', 'empDetails'));
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

    public function exportAdvanceLoan(Request $request)
    {


        $advanceReports = AdvanceApplication::whereStatus(3)->filter($request, false)->get();



        // Generate the PDF view and pass the data
        $pdf = Pdf::loadView('export-report.advance-loan-report-pdf', compact('advanceReports'))->setPaper('a4', 'landscape');;

        // Return the PDF download
        return $pdf->download('AdvanceLoan-Report.pdf');
    }

    public function exportAdvanceLoanExcel(Request $request)
    {
        return Excel::download(new AdvanceLoanExport($request), 'advance-loan-report.xlsx');
    }
    public function printAdvanceLoan(Request $request)
    {
        $advanceReports = AdvanceApplication::whereStatus(3)->filter($request, false)->get();

        // Generate the PDF view and pass the data
        $pdf = Pdf::loadView('export-report.advance-loan-report-pdf', compact('advanceReports'))->setPaper('a4', 'landscape');;


        // Return the PDF as a stream to display it in the browser
        return $pdf->stream('AdvanceLoan-Report.pdf');
    }
}
