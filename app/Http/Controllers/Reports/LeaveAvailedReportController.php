<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\LeaveApplication;
use App\Models\MasDepartment;
use App\Models\MasLeaveType;
use App\Models\MasSection;
use App\Models\User;
use Illuminate\Http\Request;

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
        $employeeLists = employeeList();
        $leaveTypes = MasLeaveType::get('name', 'id');
        $departments = MasDepartment::get('name', 'id');
        $sections = MasSection::get('name', 'id');
     
        $leaveReports = LeaveApplication::filter($request, false)->paginate(30)->withQueryString();    


        return view('report.leave-availed-report.index', compact('leaveReports','leaveTypes', 'departments','sections'));
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
}
