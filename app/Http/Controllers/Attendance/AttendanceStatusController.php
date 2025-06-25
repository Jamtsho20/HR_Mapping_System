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
        $statuses = AttendanceStatus::paginate(50);

        return view('attendance.attendance-status.index', compact('privileges', 'statuses'));
    }
    public function create()
    {
        return view('attendance.attendance-status.create');
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:attendance_statuses,code',
            'description' => 'required|string|max:255',
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        AttendanceStatus::create($validated);

        return redirect()->route('attendance-status.index')->with('success', 'Attendance status created successfully.');
    }
    public function edit($id)
    {
        $attendanceStatus = AttendanceStatus::findOrFail($id);

        return view('attendance.attendance-status.edit', compact('attendanceStatus'));
    }
    public function update(Request $request, $id)
    {
        $attendanceStatus = AttendanceStatus::findOrFail($id);

        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:attendance_statuses,code,' . $attendanceStatus->id,
            'description' => 'required|string|max:255',
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        $attendanceStatus->update($validated);

        return redirect()->route('attendance-status.index')->with('success', 'Attendance status updated successfully.');
    }

    public function destroy($id)
    {
        try {
            AttendanceStatus::findOrFail($id)->delete();

            return back()->with('msg_success', 'Attendance Status has been deleted');
        } catch (\Exception $e) {
            return back()->with('msg_error', 'Attendance Status cannot be deleted as it has been used by other module. For further information contact system admin.');
        }
    }
}
