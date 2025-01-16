<?php

namespace App\Http\Controllers;

use App\Mail\LeaveEncashmentMail;
use App\Models\ApplicationHistory;
use App\Models\EmployeeLeave;
use App\Models\LeaveApplication;
use App\Models\LeaveEncashment;
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
use Illuminate\Support\Str;

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

        //alert for approver
        $alerts = ApplicationHistory::select('application_type', DB::raw('COUNT(*) as total'))
            ->where('approver_emp_id', auth()->user()->id)
            ->whereIn('status', [1, 2])
            ->groupBy('application_type')
            ->get();

        foreach ($alerts as $alert) {
            $text = $alert->application_type; // Get application_type
            $alert->lastPart = Str::afterLast($text, '\\'); // Add last part to the alert object
            $formattedText = Str::of($alert->lastPart)->replaceMatches('/([a-z])([A-Z])/', '$1 $2')->__toString();

            // Remove the last word
            $formattedTextWithoutLastWord = preg_replace('/\s\w+$/', '', $formattedText);

            $alert->lastPart = $formattedTextWithoutLastWord; // Add formatted string to the alert object
            $alert->count = $alert->total;
        }

        // Get all system notifications
        $notifications = SystemNotification::all()->map(function ($notification) {
            return [
                'type' => 'notification', // Mark as notification type
                'title' => $notification->title,
                'message' => $notification->message,
            ];
        });

        // Merge alerts and notifications into a single array
        $combinedItems = $alerts->map(function ($alert) {
            return [
                'type' => 'alert', // Mark as alert type
                'application_type' => $alert->lastPart,
                'count' => $alert->count,
            ];
        })->merge($notifications);

        // Check leave encashment eligibility and send notification if applicable
        $leaveEncashmentMessage = $this->sendEncashmentNotification($user->id, $currentYear);
        if ($leaveEncashmentMessage) {
            $notifications[] = [
                'id' => null,
                'title' => 'Leave Encashment',
                'message' => $leaveEncashmentMessage,
            ];
        }
        //collect all record related to login user to show in notification from application histories to show in notification tbl

        // Fetch leave status counts
        [$leaveData, $statusCounts] = $this->getLeaveData($currentYear);
        $showEarnedLeave = $employmentTypeId !== 3;

        // Fetch earned leave data if applicable
        [$earnedLeaveData, $earnedLeaveCounts] = $showEarnedLeave
            ? $this->getLeaveData($currentYear, 2)
            : [[], []];
        // dd($earnedLeaveData, $earnedLeaveCounts);


        // dd($alerts);
        return view('dashboard', compact(
            'user',
            'holidays',
            'notifications',
            'leaveData',
            'statusCounts',
            'earnedLeaveData',
            'earnedLeaveCounts',
            'showEarnedLeave',
            'alerts',
            'combinedItems'
        ));
    }

    /**
     * Check if the user is eligible for leave encashment and send email.
     */


     private function sendEncashmentNotification($employeeId, $currentYear)
     {
         // Fetch closing balance for the employee's leave type
         $closingBalance = EmployeeLeave::where('mas_employee_id', $employeeId)
             ->where('mas_leave_type_id', 2)
             ->value('closing_balance');
     
         // Check if an encashment application exists for the current year
         $hasEncashed = LeaveEncashmentApplication::where('mas_employee_id', $employeeId)
             ->whereYear('created_at', $currentYear)
             ->exists();
     
         // If closing balance is more than or equal to 37 and no encashment exists for the current year
         if ($closingBalance >= 37 && !$hasEncashed) {
             // Fetch the leave encashment record for the employee
             $notification = LeaveEncashment::where('mas_employee_id', $employeeId)->first();
     
             // If no notification exists or if email has not been sent
             if (!$notification || !$notification->email_sent) {
                 try {
                     // Fetch the user to send the email
                     $user = User::find($employeeId);
     
                     // If user exists and email is valid, send the email and update the database
                     if ($user && $user->email) {
                         // Update or create the notification record
                         LeaveEncashment::updateOrCreate(
                             ['mas_employee_id' => $employeeId],
                             [
                                 'email_sent' => true,
                                 'sent_at' => now(),
                             ]
                         );
     
                         // Send the leave encashment email
                         Mail::to($user->email)->send(new LeaveEncashmentMail($user));
     
                         return 'You are eligible for leave encashment. Please apply to encash your leave balance.';
                     }
                 } catch (\Exception $e) {
                     // Log the exception if email fails
                     Log::error('Failed to send leave encashment email: ' . $e->getMessage());
                     return 'You are eligible for leave encashment.';
                 }
             }
     
             return 'You are eligible for leave encashment.';
         }
     
         return '';  // Return empty if the conditions are not met
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
