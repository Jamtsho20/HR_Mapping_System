<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Models\MasDepartment;
use App\Models\MasRegion;
use App\Models\MasSection;
use App\Models\User;
use Illuminate\Http\Request;

class AttendanceSummaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('permission:attendance/attendance-summary,view')->only('index');
        $this->middleware('permission:attendance/attendance-summary,create')->only('store');
        $this->middleware('permission:attendance/attendance-summary,edit')->only('update');
        $this->middleware('permission:attendance/attendance-summary,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $yearMonth = $request->year_month;
        $departments = MasDepartment::select('id', 'name')->get();
        $sections = MasSection::select('id', 'name')->get();
        $employees = User::get();
        $maxDays = daysInMonth($yearMonth);
        $days = [];
        for ($i = 1; $i <= $maxDays; $i++) {
            $days[] = str_pad($i, 2, '0', STR_PAD_LEFT);
        }
               
        return view('attendance.attendance-summary.index', compact( 'privileges','departments','sections', 'employees', 'days'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
}
