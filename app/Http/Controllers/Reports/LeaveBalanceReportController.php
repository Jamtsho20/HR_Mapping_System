<?php

namespace App\Http\Controllers\Reports;

use App\Exports\LeaveBalanceExport;
use App\Http\Controllers\Controller;
use App\Models\EmployeeLeave;
use App\Models\MasDepartment;
use App\Models\MasLeaveType;
use App\Models\MasSection;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LeaveBalanceReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:report/leave-balance-report,view')->only('index');
        $this->middleware('permission:report/leave-balance-report,create')->only('store');
        $this->middleware('permission:report/leave-balance-report,edit')->only('update');
        $this->middleware('permission:report/leave-balance-report,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $employee = employeeList();
        $leaveBalances = EmployeeLeave::filter($request)->paginate(config('global.pagination'))->withQueryString();
        $leaveTypes = MasLeaveType::select('id', 'name')->get();
        $departments = MasDepartment::select('name', 'id')->get();
        $sections = MasSection::select('name', 'id')->get();

        return view('report.leave-balance-report.index', compact('privileges', 'leaveBalances', 'leaveTypes', 'departments', 'sections', 'employee'));
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
    public function exportLeaveBalance(Request $request)
    {

        // Load all bookings with their dzongkhag names
        $leaveBalances = EmployeeLeave::filter($request)->get();



        // Generate the PDF view and pass the data
        $pdf = Pdf::loadView('export-report.leave-balance-report-pdf', compact('leaveBalances'))->setPaper('a4', 'landscape');;

        // Return the PDF download
        return $pdf->download('Leave-balance-Report.pdf');
    }
    public function exportLeaveBalanceExcel(Request $request)
    {
        return Excel::download(new LeaveBalanceExport($request), 'leave-balance-report.xlsx');
    }

    public function printLeaveBalance(Request $request)
    {
        $leaveBalances = EmployeeLeave::filter($request)->get();

        // Generate the PDF view and pass the data
        $pdf = Pdf::loadView('export-report.leave-balance-report-pdf', compact('leaveBalances'))
            ->setPaper('a4', 'landscape');

        // Return the PDF as a stream to display it in the browser
        return $pdf->stream('Leave-balance-Report.pdf');
    }
}
