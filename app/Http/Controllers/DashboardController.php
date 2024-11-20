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
        $holidays = WorkHolidayList::filter($request)
            ->orderBy('start_date')
            ->paginate(10)
            ->withQueryString();
    
        $notifications = SystemNotification::all();
        $user = auth()->user();
        $currentYear = Carbon::now()->year;
    
        // Fetch the count of leave applications by status for all leave types
        $leaveStatusCounts = LeaveApplication::select(DB::raw('status, count(*) as total'))
            ->createdBy()
            ->whereYear('created_at', $currentYear)
            ->groupBy('status')
            ->pluck('total', 'status'); // Use pluck for a key-value mapping of status to total
    
        // Fetch the leave balance for the logged-in user
        $leaveBalance = EmployeeLeave::where('mas_employee_id', auth()->id())
            ->pluck('closing_balance')
            ->first() ?? 0; // Default to 0 if no balance is found
    
        // Leave data setup
        $leaveData = ['Approved', 'Balance', 'In-Progress'];
        $statusCounts = [
            0,                // Approved Leave (Status 3)
            $leaveBalance,    // Leave Balance
            0                 // In-Progress Leave (Statuses 1 and 2)
        ];
    
        // Populate the counts
        $statusCounts[0] = $leaveStatusCounts[3] ?? 0; // Approved Leave
        $statusCounts[2] = ($leaveStatusCounts[1] ?? 0) + ($leaveStatusCounts[2] ?? 0); // In-Progress Leave
    
        // Earned Leave specific calculations
        $earnedLeaveStatusCounts = LeaveApplication::select(DB::raw('status, count(*) as total'))
            ->createdBy()
            ->where('mas_leave_type_id', 2) // Filter for earned leave
            ->whereYear('created_at', $currentYear)
            ->groupBy('status')
            ->pluck('total', 'status');
    
        $earnedLeaveBalance = EmployeeLeave::where('mas_employee_id', auth()->id())
            ->where('mas_leave_type_id', 2) // Filter for earned leave
            ->pluck('closing_balance')
            ->first() ?? 0; // Default to 0 if no balance is found
    
        // Earned Leave data setup
        $earnedLeaveData = ['Approved Leave', 'Leave Balance', 'In-Progress Leave'];
        $earnedLeaveCounts = [
            0,                     // Approved Leave (Status 3)
            $earnedLeaveBalance,   // Leave Balance
            0                      // In-Progress Leave (Statuses 1 and 2)
        ];
    
        // Populate the earned leave counts
        $earnedLeaveCounts[0] = $earnedLeaveStatusCounts[3] ?? 0; // Approved Leave
        $earnedLeaveCounts[2] = ($earnedLeaveStatusCounts[1] ?? 0) + ($earnedLeaveStatusCounts[2] ?? 0); // In-Progress Leave
    
        return view('dashboard', compact(
            'user',
            'holidays',
            'leaveData',
            'statusCounts',
            'earnedLeaveData',
            'earnedLeaveCounts',
            'notifications'
        ));
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
