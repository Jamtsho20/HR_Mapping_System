<?php

namespace App\Http\Controllers;

use App\Mail\LeaveEncashmentMail;
use App\Models\EmployeeLeave;
use App\Models\LeaveApplication;
use App\Models\LeaveEncashmentApplication;
use App\Models\SystemNotification;
use App\Models\User;
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
            ->paginate(30)
            ->withQueryString();

        // Get all system notifications
        $notifications = SystemNotification::all()->map(function ($notification) {
            return [
                'id' => $notification->id,
                'title' => $notification->title,
                'message' => $notification->message,
            ];
        });

        // Check leave encashment eligibility and send notification if applicable
        $leaveEncashmentMessage = $this->sendEncashmentNotification($user->id, $currentYear);
        if ($leaveEncashmentMessage) {
            $notifications[] = [
                'id' => null,
                'title' => 'Leave Encashment',
                'message' => $leaveEncashmentMessage,
            ];
        }

        // Fetch leave status counts
        [$leaveData, $statusCounts] = $this->getLeaveData($currentYear);
        $showEarnedLeave = $employmentTypeId !== 3;

        // Fetch earned leave data if applicable
        [$earnedLeaveData, $earnedLeaveCounts] = $showEarnedLeave
            ? $this->getLeaveData($currentYear, 2)
            : [[], []];
            // dd($earnedLeaveData, $earnedLeaveCounts);

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
     * Check if the user is eligible for leave encashment and send email.
     */
    private function sendEncashmentNotification($employeeId, $currentYear)
    {
        $closingBalance = EmployeeLeave::where('mas_employee_id', $employeeId)
            ->where('mas_leave_type_id', 2)
            ->value('closing_balance');

        $hasEncashed = LeaveEncashmentApplication::where('mas_employee_id', $employeeId)
            ->whereYear('created_at', $currentYear)
            ->exists();

        if ($closingBalance >= 37 && !$hasEncashed) {
            $user = User::find($employeeId);

            if ($user && $user->email && !$user->encashment_email_sent) {
                try {
                    // Send email notification
                    Mail::to($user->email)->send(new LeaveEncashmentMail($user));

                    // Update the flag to indicate the email has been sent
                    $user->encashment_email_sent = true;
                    $user->save();

                    return 'You are eligible for leave encashment. Please apply to encash your leave balance.';
                } catch (\Exception $e) {
                    // Log the exception
                    Log::error('Failed to send leave encashment email: ' . $e->getMessage());
                    return 'You are eligible for leave encashment';
                }
            }

            return 'You are eligible for leave encashment';
        }


        return '';
    }

    /**
     * Fetch leave data (e.g., approved, balance, and in-progress counts).
     */
    private function getLeaveData($currentYear, $leaveTypeId = null)
    {
        $statusCounts = LeaveApplication::select(DB::raw('status, count(*) as total'))
            ->createdBy()
            ->whereYear('created_at', $currentYear)
            ->when($leaveTypeId, fn($query) => $query->where('type_id', $leaveTypeId))
            ->groupBy('status')
            ->pluck('total', 'status');
        $balance = EmployeeLeave::where('mas_employee_id', auth()->id())
            ->when($leaveTypeId, fn($query) => $query->where('mas_leave_type_id', $leaveTypeId))
            ->value('closing_balance') ?? 0;

        return [
            [
                'Approved (' . ($statusCounts[3] ?? 0) . ')',
                'Balance (' . $balance . ')',
                'In-Progress (' . (($statusCounts[1] ?? 0) + ($statusCounts[2] ?? 0)) . ')',
            ],
            [
                $statusCounts[3] ?? 0,  // Approved
                $balance,              // Balance
                ($statusCounts[1] ?? 0) + ($statusCounts[2] ?? 0),  // In-Progress
            ]
        ];
    }

    public function dashboard()
    {
        $currentYear = now()->year;
        $employeeId = auth()->id();
        $leaveData = app('App\Http\Controllers\LeaveApplicationController')->getUserLeaveData();
        $eligibilityMessage = $this->sendEncashmentNotification($employeeId, $currentYear);

        return view('dashboard', compact('leaveData', 'eligibilityMessage'));
    }
}
