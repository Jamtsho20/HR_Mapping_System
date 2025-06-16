<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\AdvanceApplication;
use App\Models\MasDepartment;
use App\Models\MasSection;
use Illuminate\Http\Request;

class AdvanceSifaLoanReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:report/advance-sifa-loan-report,view')->only('index');
        $this->middleware('permission:report/advance-sifa-loan-report,create')->only('store');
        $this->middleware('permission:report/advance-sifa-loan-report,edit')->only('update');
        $this->middleware('permission:report/advance-sifa-loan-report,delete')->only('destroy');
    }
       public function index(Request $request)
    {
        $privileges = $request->instance();
        $employeeLists = employeeList();
        $departments = MasDepartment::select('name', 'id')->get();
        $sections = MasSection::select('name', 'id')->get();
        $advancesifaReports = AdvanceApplication::filter($request, false)->whereStatus(4)->paginate(config('global.pagination'))->withQueryString();


        return view('report.advance-sifa-loan-report.index', compact('privileges', 'employeeLists', 'departments', 'sections', 'advancesifaReports'));
    }
    public function show(string $id)
    {
        $advance = AdvanceApplication::with('advanceType')->findOrFail($id);
        $empDetails = empDetails($advance->created_by);
        $repayments = \App\Models\SifaLoanRepayment::where('advance_application_id', $advance->id)->get();

        return view('report.advance-sifa-loan-report.show', compact('advance', 'empDetails','repayments'));
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


        $advancesifaReports = AdvanceApplication::whereStatus(4)->filter($request, false)->get();



        // Generate the PDF view and pass the data
        $pdf = Pdf::loadView('export-report.advance-loan-report-pdf', compact('advancesifaReports'))->setPaper('a4', 'landscape');;

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
