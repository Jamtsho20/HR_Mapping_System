<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Models\AttendanceStatus;
use Illuminate\Http\Request;

class AttendanceStatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:attendance/attendance-status,view')->only('index');
        $this->middleware('permission:attendance/attendance-status,create')->only('store');
        $this->middleware('permission:attendance/attendance-status,edit')->only('update');
        $this->middleware('permission:attendance/attendance-status,delete')->only('destroy');
    }
     public function index(Request $request)
    {
        $privileges = $request->instance();
        $statuses = AttendanceStatus::all();

        return view('attendance.attendance-status.index', compact('privileges','statuses'));
    }


}
