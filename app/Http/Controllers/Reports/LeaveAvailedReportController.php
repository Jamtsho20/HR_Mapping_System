<?php

namespace App\Http\Controllers\Reports;

use App\Exports\LeaveAvailedExport;
use App\Http\Controllers\Controller;
use App\Models\LeaveApplication;
use App\Models\MasDepartment;
use App\Models\MasLeaveType;
use App\Models\MasSection;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LeaveAvailedReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:report/leave-availed-report,view')->only('index');
        $this->middleware('permission:report/leave-availed-report,create')->only('store');
        $this->middleware('permission:report/leave-availed-report,edit')->only('update');
        $this->middleware('permission:report/leave-availed-report,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $employee = employeeList();    
        $leaveTypes = MasLeaveType::select('id', 'name')->get();
        $departments = MasDepartment::select('name', 'id')->get();
        $sections = MasSection::select('name', 'id')->get();

        $leaveReports = LeaveApplication::filter($request, false)->paginate(config('global.pagination'))->withQueryString();


        return view('report.leave-availed-report.index', compact('leaveReports', 'leaveTypes', 'departments', 'sections', 'employee'));
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


    public function exportLeaveAvailed(Request $request)
    {

        // Load all bookings with their dzongkhag names
        $leaveReports = LeaveApplication::filter($request, false)->get();



        // Generate the PDF view and pass the data
        $pdf = Pdf::loadView('export-report.leave-availed-report-pdf', compact('leaveReports'))->setPaper('a4', 'landscape');;

        // Return the PDF download
        return $pdf->download('Leave-availed-Report.pdf');
    }
    public function exportLeaveAvailedExcel(Request $request)
    {
        return Excel::download(new LeaveAvailedExport($request), 'leave-availed-report.xlsx');
    }

    public function printLeave(Request $request)
    {
        $leaveReports = LeaveApplication::filter($request, false)->get();

        // Generate the PDF view and pass the data
        $pdf = Pdf::loadView('export-report.leave-availed-report-pdf', compact('leaveReports'))
            ->setPaper('a4', 'landscape');

        // Return the PDF as a stream to display it in the browser
        return $pdf->stream('Leave-availed-Report.pdf');
    }
}
