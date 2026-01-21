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
use App\Models\RequisitionDetail;
use App\Models\MasAssets;
use App\Models\MRF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\MasDepartment;
use App\Models\FunctionModel;


class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $companyId = $user->empJob->mas_company_id ?? null;
        $currentYear = Carbon::now()->year;
        $employmentTypeId = $user->empJob->mas_employment_type_id ?? null;

        // Determine roles
        $isAdmin = $user->roles()->where('name', 'Administrator')->exists();
        $isHRorHOD = $user->roles()->whereIn('name', ['Human Resource', 'Head Of Department'])->exists();
        // Fetch holiday list with filtering
        $holidays = WorkHolidayList::filter($request)
            ->orderBy('start_date')
            ->paginate(30)
            ->withQueryString();

        //alert for approver
        $applicationsConfig = config('global.applications');

        // Create a reverse map: 'App\Models\AdvanceApplication' => 3
        $classToIdMap = collect($applicationsConfig)
            ->mapWithKeys(fn($item, $key) => [$item['name'] => $key])
            ->toArray();

        $alerts = ApplicationHistory::select('application_type', DB::raw('COUNT(*) as total'))
            ->where('approver_emp_id', auth()->user()->id)
            ->whereIn('status', [1, 2])
            ->groupBy('application_type')
            ->get();

        $alerts->transform(function ($alert) use ($applicationsConfig, $classToIdMap) {
            $className = $alert->application_type;
            $appTypeId = $classToIdMap[$className] ?? null;

            $lastPart = Str::afterLast($className, '\\');
            $formattedText = Str::of($lastPart)
                ->replaceMatches('/([a-z])([A-Z])/', '$1 $2')
                ->__toString();
            $formattedTextWithoutLastWord = preg_replace('/\s\w+$/', '', $formattedText);

            if ($appTypeId && isset($applicationsConfig[$appTypeId])) {
                $config = $applicationsConfig[$appTypeId];
                $alert->application_type_id = $appTypeId;
                $alert->model_class = $className;
                $alert->lastPart = $formattedTextWithoutLastWord;
                $alert->email_subject = $config['email_subject'];
                $alert->post_to_sap = $config['post_to_sap'];
                $alert->count = $alert->total;
            } else {
                $alert->application_type_id = null;
                $alert->lastPart = $formattedTextWithoutLastWord;
                $alert->lastPart = 'Unknown';
            }

            return $alert;
        });

        // Get all system notifications
        $notifications = SystemNotification::where('mas_employee_id', auth()->id())
            ->latest()
            ->get();

        // Merge alerts and notifications into a single array
        $combinedItems = collect($alerts)->map(function ($alert) {
            return [
                'type' => 'alert',
                'application_type' => $alert->lastPart,
                'count' => $alert->count,
            ];
        })->merge(collect($notifications));

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
        [$leaveData, $statusCounts] = $this->getLeaveData($currentYear, 1);
        $showEarnedLeave = $employmentTypeId !== 3;

        // Fetch earned leave data if applicable
        [$earnedLeaveData, $earnedLeaveCounts] = $showEarnedLeave
            ? $this->getLeaveData($currentYear, 2)
            : [[], []];

        // ========== NEW DASHBOARD DATA ==========

        // Initialize variables with default values
        $totalEmployees = $activeEmployees = $pendingMRF = $inProcessMRF = 0;
        $totalDepartments = $activeDepartments = $totalFunctions = $activeFunctions = 0;
        $functionsStrength = collect([]);
        $departmentsWithSections = collect([]);
        $departmentChartLabels = $departmentChartData = $functionChartLabels = $functionChartData = [];

        // Total Employees - Use User model (which maps to mas_employees table)
        // --------------------- Employees ---------------------
        try {
            $employeeQuery = User::employee();

            if (!$isAdmin && $companyId) {
                $employeeQuery->whereHas('empJob', fn($q) => $q->where('mas_company_id', $companyId));
            }

            $totalEmployees = $employeeQuery->count();
            $activeEmployees = $employeeQuery->where('is_active', 'active')->count();
        } catch (\Exception $e) {
            \Log::error('Error fetching employee count: ' . $e->getMessage());
        }
        // --------------------- MRFs ---------------------
        try {
            if (class_exists(MRF::class)) {
                $mrfQuery = MRF::query();

                if (!$isAdmin && $companyId) {
                    // MRFs linked to employee's company through function
                    $mrfQuery->whereHas('function', fn($q) => $q->where('mas_company_id', $companyId));
                }

                $pendingMRF = (clone $mrfQuery)->where('status', 'pending')->count();
                $inProcessMRF = (clone $mrfQuery)->where('status', 'in_process')->count();
            }
        } catch (\Exception $e) {
            \Log::error('Error fetching MRF data: ' . $e->getMessage());
        }
        // --------------------- Departments ---------------------
        try {
            if (class_exists(MasDepartment::class)) {
                $departmentQuery = MasDepartment::withCount('sections');

                if (!$isAdmin && $companyId) {
                    $departmentQuery->whereHas('employees.empJob', fn($q) => $q->where('mas_company_id', $companyId));
                }

                $departmentsWithSections = $departmentQuery->orderBy('name')->get(['id', 'name', 'status']);
                $totalDepartments = $departmentsWithSections->count();
                $activeDepartments = $departmentsWithSections->where('status', 'active')->count();

                // Count employees per department
                $departmentsWithSections->each(function ($department) use ($companyId, $isAdmin) {
                    $query = User::employee()->whereHas('empJob', fn($q) => $q->where('mas_department_id', $department->id));

                    if (!$isAdmin && $companyId) {
                        $query->whereHas('empJob', fn($q) => $q->where('mas_company_id', $companyId));
                    }

                    $department->employees_count = $query->where('is_active', 'active')->count();
                });

                // Department chart data
                foreach ($departmentsWithSections as $department) {
                    $departmentChartLabels[] = $department->name;
                    $departmentChartData[] = $department->employees_count;
                }
            }
        } catch (\Exception $e) {
            \Log::error('Error fetching department data: ' . $e->getMessage());
        }
        // --------------------- Functions ---------------------
        try {
            $functionQuery = FunctionModel::withCount('designations')->orderBy('name');

            if (!$isAdmin && $companyId) {
                $functionQuery->where('mas_company_id', $companyId);
            }

            $functionsStrength = $functionQuery->get(['id', 'name', 'approved_strength', 'current_strength', 'status']);
            $totalFunctions = $functionsStrength->count();
            $activeFunctions = $functionsStrength->where('status', 'active')->count();

            // Function utilization chart
            $functionChartLabels = $functionsStrength->pluck('name')->toArray();
            $functionChartData = $functionsStrength->map(
                fn($f) => $f->approved_strength > 0
                    ? round(($f->current_strength / $f->approved_strength) * 100)
                    : 0
            )->toArray();
        } catch (\Exception $e) {
            \Log::error('Error fetching function data: ' . $e->getMessage());
        }
        // ========== END NEW DASHBOARD DATA ==========

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
            'combinedItems',
            // New dashboard data
            'totalEmployees',
            'activeEmployees',
            'pendingMRF',
            'inProcessMRF',
            'totalDepartments',
            'activeDepartments',
            'totalFunctions',
            'activeFunctions',
            'functionsStrength',
            'departmentsWithSections',
            'departmentChartLabels',
            'departmentChartData',
            'functionChartLabels',
            'functionChartData'
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
        // Calculate the total leave days for each status
        $statusCounts = LeaveApplication::select(DB::raw('status, SUM(no_of_days) as total_days'))
            ->createdBy() // Scope for the logged-in user
            ->whereYear('created_at', $currentYear) // Filter by current year
            ->when($leaveTypeId, fn($query) => $query->where('type_id', $leaveTypeId)) // Filter by leave type if provided
            ->groupBy('status') // Group by status (approved, in-progress, etc.)
            ->pluck('total_days', 'status');

        // Get the employee's closing leave balance
        // Calculate the total leave days for each status
        $statusCounts = LeaveApplication::select(DB::raw('status, SUM(no_of_days) as total_days'))
            ->createdBy() // Scope for the logged-in user
            ->whereYear('created_at', $currentYear) // Filter by current year
            ->when($leaveTypeId, fn($query) => $query->where('type_id', $leaveTypeId)) // Filter by leave type if provided
            ->groupBy('status') // Group by status (approved, in-progress, etc.)
            ->pluck('total_days', 'status');

        // Get the employee's closing leave balance
        $balance = EmployeeLeave::where('mas_employee_id', auth()->id())
            ->when($leaveTypeId, fn($query) => $query->where('mas_leave_type_id', $leaveTypeId))
            ->value('closing_balance') ?? 0;

        // Calculate leave days for each status
        $approvedLeave = $statusCounts[3] ?? 0; // Approved status (assuming 3 is the status code for approved)
        $inProgressLeave = ($statusCounts[1] ?? 0) + ($statusCounts[2] ?? 0); // In-Progress status (Pending = 1, Rejected = 2)

        // Calculate remaining balance after deducting approved and in-progress leave days
        // $remainingBalance = $balance - $approvedLeave - $inProgressLeave;

        return [
            [
                'Approved (' . $approvedLeave . ')',
                'Balance (' . $balance . ')',
                'In-Progress (' . $inProgressLeave . ')',
            ],
            [
                $approvedLeave,      // Approved leave days
                $balance,    // Remaining balance after deducting approved and in-progress
                $inProgressLeave,     // In-progress leave days (pending/rejected)
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
