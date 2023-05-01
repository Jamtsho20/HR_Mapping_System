<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Models\MasDepartment;
use App\Models\MasDzongkhag;
use App\Models\MasRegion;
use Illuminate\Http\Request;

class AttendanceEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('permission:attendance/attendance-entry,view')->only('index');
        $this->middleware('permission:attendance/attendance-entry,create')->only('store');
        $this->middleware('permission:attendance/attendance-entry,edit')->only('update');
        $this->middleware('permission:attendance/attendance-entry,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
  
        $departments = MasDepartment::select('id', 'name')->get();
        $regions = MasRegion::select('id', 'region_name')->get();
               
        return view('attendance.attendance-entry.index', compact( 'privileges','departments','regions'));
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
