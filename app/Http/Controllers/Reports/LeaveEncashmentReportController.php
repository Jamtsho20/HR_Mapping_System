<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\LeaveEncashmentApplication;
use App\Models\MasDepartment;
use App\Models\MasSection;
use Illuminate\Http\Request;

class LeaveEncashmentReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:report/leave-encashment-report,view')->only('index');
        $this->middleware('permission:report/leave-encashment-report,create')->only('store');
        $this->middleware('permission:report/leave-encashment-report,edit')->only('update');
        $this->middleware('permission:report/leave-encashment-report,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $departments = MasDepartment::select('name', 'id')->get();
        $sections = MasSection::select('name', 'id')->get();
        $leaveEncashments = LeaveEncashmentApplication::where('status', '=', 3)
            ->join('employee_leaves', 'leave_encashment_applications.type_id', '=', 'employee_leaves.mas_leave_type_id')
            ->paginate(config('global.pagination'));


        return view('report.leave-encashment-report.index', compact('privileges', 'departments', 'sections', 'leaveEncashments'));
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
