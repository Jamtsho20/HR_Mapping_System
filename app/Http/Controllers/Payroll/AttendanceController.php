<?php

namespace App\Http\Controllers\Payroll;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Imports\AttendanceImport;
use App\Models\EmployeeAttendance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\EmployeeAttendanceDetail;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:payroll/attendance,view')->only('index');
        $this->middleware('permission:payroll/attendance,create')->only('store');
        $this->middleware('permission:payroll/attendance,edit')->only('update');
        $this->middleware('permission:payroll/attendance,delete')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $privileges = $request->instance();
        // $year = date("Y");
        // $month = date("m");
        $forMonth = Carbon::now()->format('m-Y');

        $attendances = EmployeeAttendance::all();
        $attendance = EmployeeAttendance::whereForMonth($forMonth)->first();
        if (!$attendance) {
            $attendance = EmployeeAttendance::create([
                "for_month" => $forMonth,
                "status" => 0,
            ]);
        }

        return view('payroll.attendance.index', compact('attendances', 'privileges'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $attendance = EmployeeAttendance::whereId($id)->first();
        $details = $attendance->details()->paginate(config('global.pagination'))->withQueryString();

        return view('payroll.attendance.show', compact('attendance', 'details'));
    }

    public function upload(Request $request, $id)
    {
        $request->validate([
            'attendance_sheet' => 'required|file|mimes:xlsx,csv',
        ]);

        Excel::import(new AttendanceImport($id), $request->file('attendance_sheet'));

        return back()->with('success', 'Attendance sheet uploaded successfully.');
    }

    public function updateAttendance(Request $request)
    {
        try {
            $record = EmployeeAttendanceDetail::find($request->id);

            if (!$record) {
                return response()->json(['success' => false, 'message' => 'Record not found'], 404);
            }

            DB::beginTransaction();

            $record->physical_days = $request->physical_days;
            $record->save();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Attendance updated successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating attendace: ' . $e->getMessage());

            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
