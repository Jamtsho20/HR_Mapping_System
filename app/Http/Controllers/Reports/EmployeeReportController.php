<?php

namespace App\Http\Controllers\Reports;

use App\Exports\EmployeeExport;
use App\Http\Controllers\Controller;
use App\Models\MasDepartment;
use App\Models\MasDesignation;
use App\Models\MasSection;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:report/employee-report,view')->only('index');
        $this->middleware('permission:report/employee-report,create')->only('store');
        $this->middleware('permission:report/employee-report,edit')->only('update');
        $this->middleware('permission:report/employee-report,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $employees = User::active()->filter($request)->paginate(config('global.pagination'))->withQueryString();
        $departments = MasDepartment::orderBy('name')->get(['id', 'name']);
        $designations = MasDesignation::orderBy('name')->get(['id', 'name']);
        $sections = MasSection::orderBy('name')->get(['id', 'name']);
        return view('report.employee-report.index', compact('privileges', 'employees', 'departments', 'sections', 'designations'));
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

    public function exportEmployee(Request $request)
    {

        // Load all bookings with their dzongkhag names
        $employees = User::filter($request, false)->get();

        // Generate the PDF view and pass the data
        $pdf = Pdf::loadView('export-report.employee-report-pdf', compact('employees'))->setPaper('a4', 'landscape');;

        // Return the PDF download
        return $pdf->download('Employee-Report.pdf');
    }
    public function exportEmployeeExcel(Request $request)
    {
        return Excel::download(new EmployeeExport($request), 'employee-report.xlsx');
    }

    public function printEmployee(Request $request)
    {
        $employees = User::filter($request, false)->get();

        // Generate the PDF view and pass the data
        $pdf = Pdf::loadView('export-report.employee-report-pdf', compact('employees'))
            ->setPaper('a4', 'landscape');

        // Return the PDF as a stream to display it in the browser
        return $pdf->stream('Employee-Report.pdf');
    }
}
