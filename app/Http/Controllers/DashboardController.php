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
    public function index(Request $request)
    {
        $user = auth()->user(); 
        $currentYear = Carbon::now()->year;
        $employmentTypeId = $user->empJob->mas_employment_type_id ?? null;

        // Fetch holiday list with filtering
        $holidays = WorkHolidayList::filter($request)
            ->orderBy('start_date')
            ->paginate(10)
            ->withQueryString();

        // Get all system notifications
        $notifications = SystemNotification::all()->map(function ($notification) {
            return [
                'id' => $notification->id,
                'title' => $notification->title,
                'message' => $notification->message,
            ];
        });

        // Check leave encashment eligibility
        $leaveEncashmentNotification = $this->checkLeaveEncashmentEligibility($user->id, $currentYear);
        if ($leaveEncashmentNotification) {
            $notifications[] = [
                'id' => null, 
                'title' => 'Leave Encashment',
                'message' => $leaveEncashmentNotification,
            ];
        }

        // Fetch leave status counts
        [$leaveData, $statusCounts] = $this->getLeaveData($currentYear);
        $showEarnedLeave = $employmentTypeId !== 3; 

        // Fetch earned leave data if applicable
        [$earnedLeaveData, $earnedLeaveCounts] = $showEarnedLeave
            ? $this->getLeaveData($currentYear, 2)
            : [[], []];

        return view('dashboard', compact(
            'user',
            'holidays',
            'notifications',
            'leaveData',
            'statusCounts',
            'earnedLeaveData',
            'earnedLeaveCounts',
            'showEarnedLeave'
        ));
    }

    /**
     * Check if the user is eligible for leave encashment.
     */
    private function checkLeaveEncashmentEligibility($employeeId, $currentYear)
    {
        $closingBalance = EmployeeLeave::where('mas_employee_id', $employeeId)
            ->where('mas_leave_type_id', 2)
            ->value('closing_balance');

        $hasEncashed = LeaveEncashmentApplication::where('mas_employee_id', $employeeId)
            ->whereYear('created_at', $currentYear)
            ->exists();

        if ($closingBalance >= 37 && !$hasEncashed) {
            return 'You are eligible for leave encashment. Please apply to encash your leave balance.';
        }

        return null;
    }

    /**
     * Fetch leave data (e.g., approved, balance, and in-progress counts).
     */
    private function getLeaveData($currentYear, $leaveTypeId = null)
    {
        $statusCounts = LeaveApplication::select(DB::raw('status, count(*) as total'))
            ->createdBy()
            ->whereYear('created_at', $currentYear)
            ->when($leaveTypeId, fn ($query) => $query->where('mas_leave_type_id', $leaveTypeId))
            ->groupBy('status')
            ->pluck('total', 'status');

        $balance = EmployeeLeave::where('mas_employee_id', auth()->id())
            ->when($leaveTypeId, fn ($query) => $query->where('mas_leave_type_id', $leaveTypeId))
            ->value('closing_balance') ?? 0;

        return [
            ['Approved', 'Balance', 'In-Progress'],
            [
                $statusCounts[3] ?? 0,  // Approved
                $balance,             // Balance
                ($statusCounts[1] ?? 0) + ($statusCounts[2] ?? 0),  // In-Progress
            ]
        ];
    }

    public function sendEncashmentNotification()
    {
        $employees = EmployeeLeave::where('mas_leave_type_id', 2)
            ->where('closing_balance', '>=', 37)
            ->with('employee') // Assume EmployeeLeave has a relationship with the Employee model
            ->get();

        foreach ($employees as $leave) {
            $employee = $leave->employee;
            $leaveBalance = $leave->closing_balance;

            $hasEncashed = LeaveEncashmentApplication::where('mas_employee_id', $employee->id)->exists();
            if (!$hasEncashed) {
                Mail::to($employee->email)->send(new LeaveEncashmentMail($employee, $leaveBalance));
            }
        }
    }

    public function show()
    {
        $alertMessage = Cache::get('holiday_alert_message');
        return view('dashboard.index', compact('alertMessage'));
    }

    public function dashboard()
    {
        $leaveData = app('App\Http\Controllers\LeaveApplicationController')->getUserLeaveData();
        return view('dashboard', compact('leaveData'));
    }
}
