<?php

namespace App\Http\Controllers\Reports;

use App\Exports\SalaryPaySlipExport;
use App\Http\Controllers\Controller;
use App\Models\FinalPaySlip;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
class SalaryReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:report/salary-report,view')->only('index');
        $this->middleware('permission:report/salary-report,create')->only('store');
        $this->middleware('permission:report/salary-report,edit')->only('update');
        $this->middleware('permission:report/salary-report,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $employee = employeeList();
        $salaries = FinalPaySlip::filter($request)->paginate(30)->withQueryString();

        return view('report.salary-report.index', compact('privileges', 'salaries', 'employee'));
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

    public function exportSalary(Request $request){

        // Load all bookings with their dzongkhag names
        $salaries = FinalPaySlip::filter($request)->get();
     
  

        // Generate the PDF view and pass the data
        $pdf = Pdf::loadView('export-report.salary-report-pdf', compact('salaries'))->setPaper('a4', 'landscape');;

        // Return the PDF download
        return $pdf->download('Salary-Report.pdf');
    }

    public function exportSalaryExcel(Request $request)
    {
        return Excel::download(new SalaryPaySlipExport($request), 'salary-report.xlsx');
    }
    public function printSalary(Request $request)
    {
        $salaries = FinalPaySlip::filter($request)->get();

        // Generate the PDF view and pass the data
        $pdf = Pdf::loadView('export-report.salary-report-pdf', compact('salaries'))->setPaper('a4', 'landscape');;


        // Return the PDF as a stream to display it in the browser
        return $pdf->stream('Salary-Report.pdf');
    }



}
