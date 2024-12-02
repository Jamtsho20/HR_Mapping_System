<?php

namespace App\Http\Controllers;

use App\Mail\LeaveEncashmentMail;
use App\Models\EmployeeLeave;
use App\Models\LeaveApplication;
use App\Models\LeaveEncashmentApplication;
use App\Models\SystemNotification;
use App\Models\WorkHolidayList;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class DashboardController extends Controller
{
    // public function index(Request $request)
    // {
    //     $holidays = WorkHolidayList::filter($request)
    //         ->orderBy('start_date')
    //         ->paginate(10)
    //         ->withQueryString();

    //     $notifications = SystemNotification::all();
    //     $user = auth()->user(); // Retrieve the logged-in user
    //     $currentYear = Carbon::now()->year;

    //     // Retrieve the employment type ID from the MasEmployeeJob model
    //     $employmentTypeId = $user->empJob->mas_employment_type_id ?? null; // Assuming the relationship is named 'employeeJob'

    //     // Log the employment type ID for debugging
    //     Log::info('Employment Type ID: ' . $employmentTypeId);

    //     // Function to fetch and calculate leave status counts
    //     $getLeaveData = function ($leaveTypeId = null) use ($currentYear) {
    //         $statusCounts = LeaveApplication::select(DB::raw('status, count(*) as total'))
    //             ->createdBy()
    //             ->whereYear('created_at', $currentYear)
    //             ->when($leaveTypeId, function ($query) use ($leaveTypeId) {
    //                 $query->where('mas_leave_type_id', $leaveTypeId);
    //             })
    //             ->groupBy('status')
    //             ->pluck('total', 'status');

    //         $balance = EmployeeLeave::where('mas_employee_id', auth()->id())
    //             ->when($leaveTypeId, function ($query) use ($leaveTypeId) {
    //                 $query->where('mas_leave_type_id', $leaveTypeId);
    //             })
    //             ->pluck('closing_balance')
    //             ->first() ?? 0; // Default to 0 if no balance is found

    //         // Default labels and counts
    //         $leaveData = ['Approved', 'Balance', 'In-Progress'];
    //         $statusCountsArray = [0, $balance, 0];

    //         // Populate the counts
    //         $statusCountsArray[0] = $statusCounts[3] ?? 0; // Approved Leave
    //         $statusCountsArray[2] = ($statusCounts[1] ?? 0) + ($statusCounts[2] ?? 0); // In-Progress Leave

    //         return [$leaveData, $statusCountsArray];
    //     };

    //     // Fetch casual leave data
    //     list($leaveData, $statusCounts) = $getLeaveData();

    //     // Check the employment type ID
    //     $showEarnedLeave = true;
    //     if ($employmentTypeId == 3) {
    //         $showEarnedLeave = false;  // Hide earned leave chart for employment type 3
    //     }

    //     // Fetch earned leave data only if it should be shown
    //     $earnedLeaveData = $earnedLeaveCounts = [];
    //     if ($showEarnedLeave) {
    //         // Fetch earned leave data
    //         list($earnedLeaveData, $earnedLeaveCounts) = $getLeaveData(2); // 2 for earned leave type
    //     }

    //     return view('dashboard', compact('user', 'holidays', 'leaveData', 'statusCounts', 'earnedLeaveData', 'earnedLeaveCounts', 'notifications', 'showEarnedLeave'));
    // }
    public function index(Request $request)
    {
        $holidays = WorkHolidayList::filter($request)
            ->orderBy('start_date')
            ->paginate(10)
            ->withQueryString();

        $notifications = SystemNotification::all();
        $user = auth()->user(); // Retrieve the logged-in user
        $currentYear = Carbon::now()->year;

        // Retrieve the employment type ID from the MasEmployeeJob model
        $employmentTypeId = $user->empJob->mas_employment_type_id ?? null;

        // Log the employment type ID for debugging
        Log::info('Employment Type ID: ' . $employmentTypeId);

        // Function to fetch and calculate leave status counts
        $getLeaveData = function ($leaveTypeId = null) use ($currentYear) {
            $statusCounts = LeaveApplication::select(DB::raw('status, count(*) as total'))
                ->createdBy()
                ->whereYear('created_at', $currentYear)
                ->when($leaveTypeId, function ($query) use ($leaveTypeId) {
                    $query->where('mas_leave_type_id', $leaveTypeId);
                })
                ->groupBy('status')
                ->pluck('total', 'status');

            $balance = EmployeeLeave::where('mas_employee_id', auth()->id())
                ->when($leaveTypeId, function ($query) use ($leaveTypeId) {
                    $query->where('mas_leave_type_id', $leaveTypeId);
                })
                ->pluck('closing_balance')
                ->first() ?? 0; // Default to 0 if no balance is found

            // Default labels and counts
            $leaveData = ['Approved', 'Balance', 'In-Progress'];
            $statusCountsArray = [0, $balance, 0];

            // Populate the counts
            $statusCountsArray[0] = $statusCounts[3] ?? 0; // Approved Leave
            $statusCountsArray[2] = ($statusCounts[1] ?? 0) + ($statusCounts[2] ?? 0); // In-Progress Leave

            return [$leaveData, $statusCountsArray];
        };

        // Fetch casual leave data
        list($leaveData, $statusCounts) = $getLeaveData();

        // Check the employment type ID
        $showEarnedLeave = true;
        if ($employmentTypeId == 3) {
            $showEarnedLeave = false;  // Hide earned leave chart for employment type 3
        }

        // Fetch earned leave data only if it should be shown
        $earnedLeaveData = $earnedLeaveCounts = [];
        if ($showEarnedLeave) {
            // Fetch earned leave data
            list($earnedLeaveData, $earnedLeaveCounts) = $getLeaveData(2); // 2 for earned leave type
        }

        // Check for leave encashment notification
        $leaveEncashmentNotification = null;

        $closingBalance = EmployeeLeave::where('mas_employee_id', $user->id)
            ->where('mas_leave_type_id', 2)
            ->value('closing_balance');

        $hasEncashed = DB::table('leave_encashment_applications')
            ->where('mas_employee_id', $user->id)
            ->whereYear('created_at', $currentYear) // Adjust condition as per requirement
            ->exists();

        if ($closingBalance >= 37 && !$hasEncashed) {
            $leaveEncashmentNotification = 'You are eligible for leave encashment. Please apply to encash your leave balance.';
        }

        return view('dashboard', compact(
            'user',
            'holidays',
            'leaveData',
            'statusCounts',
            'earnedLeaveData',
            'earnedLeaveCounts',
            'notifications',
            'showEarnedLeave',
            'leaveEncashmentNotification'
        ));
    }





    public function show()
    {
        // Retrieve the alert message from the cache
        $alertMessage = Cache::get('holiday_alert_message');
        return view('dashboard.index', compact('alertMessage'));
    }
    
    public function sendEncashmentNotification()
    {
        $employees = EmployeeLeave::where('mas_leave_type_id', 2)
            ->where('closing_balance', '>=', 37) // Check for leave balance
            ->get();

        foreach ($employees as $leave) {
            $employee = $leave->employee; // Assuming a relationship with the Employee model
            $leaveBalance = $leave->closing_balance;

            // Check if the employee has already availed leave encashment
            $hasEncashed = LeaveEncashmentApplication::where('mas_employee_id', $employee->id)->exists();

            if (!$hasEncashed) {
                // Send the email
                Mail::to($employee->email)->send(new LeaveEncashmentMail($employee, $leaveBalance));
            }
        }
    }

    public function dashboard()
    {
        $leaveData = app('App\Http\Controllers\LeaveApplicationController')->getUserLeaveData();
        return view('dashboard', compact('leaveData'));
    }
}
