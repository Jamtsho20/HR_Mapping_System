<?php

namespace App\Http\Controllers;

use App\Models\EmployeeLeave;
use App\Models\LeaveApplication;
use App\Models\SystemNotification;
use App\Models\User;
use App\Models\WorkHolidayList;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $holidays = WorkHolidayList::filter($request)->orderBy('start_date')->paginate(10)->withQueryString();
        $notifications = SystemNotification::all();
        $user = auth()->user();
        $currentYear = Carbon::now()->year;
        
        // Fetch the count of leave applications by status
        $leaveStatusCounts = LeaveApplication::select(DB::raw('status, count(*) as total'))
            ->createdBy()
            ->whereYear('created_at', $currentYear)
            ->groupBy('status')
            ->get();

        // Fetch the leave balance for the logged-in user
        $leaveBalance = EmployeeLeave::where('mas_employee_id', auth()->id())
            ->pluck('closing_balance')
            ->first();  // Get the first (and presumably only) balance

        // Fetch the leave applications with statuses 1 (In Progress) and 2 (Approved)
        $inProgressLeave = LeaveApplication::whereIn('status', [1, 2])
            ->createdBy()
            ->pluck('status');

        // Fetch the approved leave applications (status 3)
        $approvedLeave = LeaveApplication::where('status', 3)
            ->createdBy()
            ->pluck('status');

        // Defining the status labels for the chart
        $leaveData = ['Approved Leave', 'Leave Balance', 'In-Progress Leave'];

        // Initialize the status counts to zero
        $statusCounts = [0, 0, 0]; // Correctly initialize all counts to 0

        // Map the counts to the correct status
        foreach ($leaveStatusCounts as $leaveStatus) {
            $statusCounts[$leaveStatus->status] = $leaveStatus->total;
        }

        // Assign the leave balance value to the correct position in the statusCounts array
        $statusCounts[1] = $leaveBalance ?? 0; // If leaveBalance is null, set to 0

        //Earned Leave
        $earnedLeaveStatusCounts = LeaveApplication::select(DB::raw('status, count(*) as total'))
            // Assuming 'leave_type' distinguishes leave types
            ->createdBy()
            ->groupBy('status')
            ->get();

        // Initialize the earned leave status counts array to store statuses
        $earnedLeaveCounts = [0, 0, 0]; // Set to 0 by default for all status counts
        foreach ($earnedLeaveStatusCounts as $leaveStatus) {
            $earnedLeaveCounts[$leaveStatus->status] = $leaveStatus->total;
        }



        return view('dashboard', compact('user', 'holidays', 'leaveData', 'statusCounts', 'earnedLeaveCounts','notifications'));
    }

    // public function show($id)
    // {
    //     // Retrieve the employee by their ID
    //     $employee = User::findOrFail($id); // Use findOrFail to handle cases where the ID is not found

    //     // Pass the employee data to the view
    //     return view('dashboard', compact('employee'));
    // }

    // public function showDashboard()
    // {

    //     $today = Carbon::today();
    //     $tomorrow = $today->addDay();

    //     $holiday = WorkHolidayList::whereDate('holiday_date', $tomorrow)->first();

    //     if ($holiday) {
    //         // If there is a holiday tomorrow, send an alert message to the view
    //         $alertMessage = "Tomorrow is a holiday: " . $holiday->holiday_name;
    //         return view('dashboard.index', compact('alertMessage'));
    //     }

    //     return view('dashboard.index');
    // }
    public function show()
    {
        // Retrieve the alert message from the cache
        $alertMessage = Cache::get('holiday_alert_message');
        return view('dashboard.index', compact('alertMessage'));
    }

    public function dashboard()
    {
        $leaveData = app('App\Http\Controllers\LeaveApplicationController')->getUserLeaveData();
        return view('dashboard', compact('leaveData'));
    }
}
