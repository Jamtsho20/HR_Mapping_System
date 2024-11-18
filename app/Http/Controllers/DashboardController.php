<?php

namespace App\Http\Controllers;

use App\Models\LeaveApplication;
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
        $user = auth()->user();

        // Fetch the count of leave applications by status
        $leaveStatusCounts = LeaveApplication::select(DB::raw('status, count(*) as total'))
            ->groupBy('status')
            ->get();

        // Defining the status labels for the chart
        $statuses = ['Approved', 'Balance', 'In-Progress'];

        // Initialize the status counts to zero
        $statusCounts = [0, 0, 0];

        // Map the counts to the correct status
        foreach ($leaveStatusCounts as $leaveStatus) {
            $statusCounts[$leaveStatus->status] = $leaveStatus->total;
        }

        return view('dashboard', compact('user', 'holidays', 'statuses', 'statusCounts'));
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
