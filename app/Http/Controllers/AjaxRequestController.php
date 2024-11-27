<?php

namespace App\Http\Controllers;

use App\Models\AdvanceApplication;
use App\Models\ApprovingAuthority;
use App\Models\EmployeeLeave;
use App\Models\MasAdvanceTypes;
use App\Models\MasConditionField;
use App\Models\MasTravelType;
use App\Models\MasEmployeeJob;
use App\Models\MasExpensePolicy;
use App\Models\MasExpenseType;
use App\Models\LeaveEncashmentType;
use App\Models\MasGewog;
use App\Models\MasGradeStep;
use App\Models\MasLeavePolicy;
use App\Models\MasLeaveType;
use App\Models\MasPayGroupDetail;
use App\Models\MasPaySlabDetails;
use App\Models\MasRegionLocation;
use App\Models\MasSection;
use App\Models\MasVillage;
use App\Models\SystemHierarchyLevel;
use App\Models\TravelAuthorizationApplication;
use App\Models\User;
use App\Models\WorkHolidayList;
use App\Traits\JsonResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AjaxRequestController extends Controller
{
    /* write code related to ajax request */
    use JsonResponseTrait;

    public function getGewog($id)
    {
        $gewogs = MasGewog::where('mas_dzongkhag_id', $id)->get();
        return $gewogs;
    }

    public function getVillage($id)
    {
        $villages = MasVillage::where('mas_gewog_id', $id)->get(['id', 'village']);
        return $villages;
    }

    public function getSection($id)
    {
        $sections = MasSection::where('mas_department_id', $id)->get(['id', 'name']);
        return $sections;
    }

    public function getGradeStep($id)
    {
        $gradeSteps = MasGradeStep::where('mas_grade_id', $id)->get(['id', 'name', 'starting_salary', 'point']);
        return $gradeSteps;
    }

    public function getPaySlabDetail($id)
    {
        $paySlabDetail = MasPaySlabDetails::findOrFail($id);
        return $paySlabDetail;
    }

    public function getPayGroupDetail($id)
    {
        $payGroupDetail = MasPayGroupDetail::findOrFail($id);
        return $payGroupDetail;
    }

    public function getRegionLocation($id)
    {
        $regionLocation = MasRegionLocation::findOrFail($id);
        return $regionLocation;
    }

    public function getPayScale($id)
    {
        $payScale = MasGradeStep::where('id', $id)->get(['starting_salary', 'increment', 'ending_salary']);
        return $payScale;
    }

    public function getLeaveBalance($id)  //was done for json message purpose
    {
        try {
            $empGender = auth()->user()->gender;
            $leavePolicy = MasLeavePolicy::with('leavePolicyPlan')->where('mas_leave_type_id', $id)->whereStatus(1)->first();
            if (!$leavePolicy || !$leavePolicy->status || $leavePolicy->is_information_only) {
                return $this->errorResponse('You cannot apply leave as leave policy for this leave type has not been enforced or is for information purpose only, please contact system admin.');
            }

            $balance = EmployeeLeave::where('mas_leave_type_id', $id)->where('mas_employee_id', auth()->user()->id)->value('closing_balance');
            if ($balance == 0) {
                return $this->errorResponse('You are not eligible for this leave type since there is no balance.');
            }
            $leaveLimits = $leavePolicy && $leavePolicy->leavePolicyPlan ? json_decode($leavePolicy->leavePolicyPlan->leave_limits, true) : [];
            $isHalfDay = in_array(4, $leaveLimits);
            $attachmentRequired = $leavePolicy && $leavePolicy->leavePolicyPlan ? $leavePolicy->leavePolicyPlan->attachment_required : 0;
            $leavePolicyGender = $leavePolicy->leavePolicyPlan->gender ?? null;
            if ($leavePolicyGender === 3 || $leavePolicyGender == $empGender) {
                return $this->successResponse([
                    'balance' => $balance,
                    'leavePolicy' => $leavePolicy,
                    'attachment_required' => $attachmentRequired,
                    'is_half_day' => $isHalfDay
                ]);
            }

            return $this->errorResponse('You are not eligible for this leave type based on your gender.');
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred while fetching the leave balance data. Please try again.');
        }
    }

    public function getNoOfDays(Request $request)
    {
        $leaveTypeId = $request->input('leave_type');
        $fromDate = new \DateTime($request->input('from_date'));
        $toDate = new \DateTime($request->input('to_date'));
        $fromDay = (int) $request->input('from_day');
        $toDay = (int) $request->input('to_day');
        $loggedInUserRegion = loggedInUserRegion();
        $totalDays = 0;

        try {
            $leavePolicy = MasLeavePolicy::with('leavePolicyPlan')
                ->where('mas_leave_type_id', $leaveTypeId)
                ->whereStatus(1)
                ->first();
            $leaveLimits = $leavePolicy && $leavePolicy->leavePolicyPlan
                ? json_decode($leavePolicy->leavePolicyPlan->leave_limits, true)
                : [];

            // Calculate initial days with adjustments
            $dayDifference = $toDate->diff($fromDate)->days;
            $fromDayAdjustment = ($fromDay === 2 || $fromDay === 3) ? 0.5 : 1;
            $toDayAdjustment = ($toDay === 2 || $toDay === 3) ? 0.5 : 1;
            $totalDays = ($dayDifference === 0)
                ? $fromDayAdjustment + $toDayAdjustment - 1
                : $dayDifference + $fromDayAdjustment - 1 + $toDayAdjustment;

            if (!empty($leaveLimits)) {
                $holidayDates = $this->getHolidayDates($loggedInUserRegion);
                $excludeHolidays = in_array(1, $leaveLimits);
                $excludeWeekends = in_array(3, $leaveLimits);

                $totalDays -= $this->calculateExcludedDays(
                    $fromDate,
                    $toDate,
                    $holidayDates,
                    $excludeHolidays,
                    $excludeWeekends
                );
            }
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred while calculating total no. of leave days. Please try again.');
        }

        return $this->successResponse(['total_days' => max($totalDays, 0)]);
    }

    private function getHolidayDates($loggedInUserRegion)
    {
        $holidays = WorkHolidayList::whereJsonContains('region_id', (string) $loggedInUserRegion[0]->region_id)->get();
        $holidayDates = [];
        foreach ($holidays as $holiday) {
            $holidayStart = Carbon::parse($holiday->start_date);
            $holidayEnd = Carbon::parse($holiday->end_date);
            for ($date = $holidayStart; $date->lte($holidayEnd); $date->addDay()) {
                $holidayDates[] = $date->format('Y-m-d');
            }
        }
        return $holidayDates;
    }

    private function calculateExcludedDays($fromDate, $toDate, $holidayDates, $excludeHolidays, $excludeWeekends)
    {
        $excludedDays = 0;
        $currentDate = clone $fromDate;

        while ($currentDate <= $toDate) {
            $formattedDate = $currentDate->format('Y-m-d');
            $isSunday = ($currentDate->format('w') == 0);
            $isSaturday = ($currentDate->format('w') == 6);

            if ($excludeHolidays && in_array($formattedDate, $holidayDates)) {
                $excludedDays++;
            } elseif ($excludeWeekends) {
                if ($isSunday) {
                    $excludedDays++;
                }
                if ($isSaturday) {
                    $excludedDays += 0.5;
                }
            }

            $currentDate->modify('+1 day');
        }

        return $excludedDays;
    }


    public function getEmployeeSelect($id)
    {
        $approvingAuthority = ApprovingAuthority::where('id', $id)->whereStatus(1)->first();
        $employeeSelect = [];

        if ($approvingAuthority && $approvingAuthority->has_employee_field) {
            $employeeSelect = User::whereHas('roles', function ($query) use ($approvingAuthority) {
                $query->where('roles.id', $approvingAuthority->role_id);
            })->get(['id', 'name', 'title', 'username']);
        }

        return response()->json([
            'has_employee_field' => $approvingAuthority->has_employee_field ?? false,
            'employees' => $employeeSelect,
        ]);
    }

    public function getAdvanceNumber($id)
    {
        $sifaInterestRate = 0;
        $advanceCode = MasAdvanceTypes::where('id', $id)->pluck('code')[0];

        $latestTransaction = AdvanceApplication::where('advance_type_id', $id)
            ->latest('id') // Orders by id in descending order
            ->first();

        // Extract the next sequence number: get last 4 digits if transaction exists, else default to 1
        $nextSequence = $latestTransaction ? (int) substr($latestTransaction->advance_no, -4) + 1 : 1;

        // Generate the new advance number with the incremented sequence
        $advanceNo = generateTransactionNumber($advanceCode, $nextSequence);

        // if advance type is SIFA LOAN then need to get its interest rate and sent it to frontend.
        if ($id == SIFA_LOAN) {
            $sifaInterestRate = SIFA_INTEREST_RATE;
        }

        return response()->json([
            'advance_no' => $advanceNo,
            'sifa_interest_rate' => $sifaInterestRate,
        ]);
    }

    public function getExpenseAmount($id)
    {
        $loggedInUserRegion = loggedInUserRegion();
        $empJobDetail = MasEmployeeJob::where('mas_employee_id', loggedInUser())->first();
        $expensePolicy = MasExpensePolicy::with(['rateDefinition' => function ($query) use ($id, $empJobDetail, $loggedInUserRegion) {
            $query->where('travel_type', DOMESTIC_TRAVEL_TYPE)
                ->with(['expenseRateLimits' => function ($q) use ($empJobDetail, $loggedInUserRegion) {
                    $q->whereMasGradeStepId($empJobDetail->mas_grade_step_id)
                        ->whereMasRegionId($loggedInUserRegion[0]->region_id)
                        ->whereStatus(1);
                }]);
        }])
            ->whereMasExpenseTypeId($id)
            ->whereStatus(1)
            ->first();

        $attachmentRequired = $expensePolicy && $expensePolicy->rateDefinition ? $expensePolicy->rateDefinition->attachment_required : 0;
        $limitAmount = $expensePolicy && $expensePolicy->rateDefinition->expenseRateLimits->isNotEmpty() ? $expensePolicy->rateDefinition->expenseRateLimits[0]->limit_amount : 0;

        return response()->json(['attachment_required' => $attachmentRequired, 'limit_amount' => $limitAmount, 'region_name' => $loggedInUserRegion[0]->region_name]);
    }

    public function getApprovalHeadTypes($id)
    {
        $modelMap = [
            1 => MasLeaveType::class,
            2 => MasExpenseType::class,
            3 => MasAdvanceTypes::class,
            4 => LeaveEncashmentType::class,
            5 => MasAdvanceTypes::class,
            6 => MasTravelType::class,
        ];

        if (isset($modelMap[$id])) {
            return $modelMap[$id]::select('id', 'name')->get();
        }

        return null;
    }

    public function getApprovalRuleConditionFields($id)
    {
        $fields = MasConditionField::whereMasApprovalHeadId($id)->select('id', 'name', 'label', 'has_employee_field')->get();

        return $fields;
    }

    public function getApprovalRuleConditionField($id)
    {
        $field = MasConditionField::whereId($id)->select('id', 'has_employee_field')->first();

        return $field;
    }

    public function getEmployees()
    {
        $employees = User::select('id', 'name', 'employee_id')->get();

        return $employees;
    }

    public function getSystemHierarchyLevels($id)
    {
        $levels = SystemHierarchyLevel::whereSystemHierarchyId($id)->select('id', 'level')->get();

        return $levels;
    }

    public function getAdvanceDetail($id)
    {
        $advanceDetail = AdvanceApplication::where('id', $id)->get();
        return response()->json(['advance_detail' => $advanceDetail, 'da' => DAILY_ALLOWANCE]);
    }

    public function getTravelAuthorizationDetails($id)
    {
        $travelAuthorizationDetails = TravelAuthorizationApplication::with('details')->find($id);
        if (!$travelAuthorizationDetails) {
            return response()->json(['message' => 'Travel authorization not found'], 404);
        }
        if ($travelAuthorizationDetails->details) {
            $travelAuthorizationDetails->details->each(function ($detail) {
                // Use the accessor to get the travel name
                $detail->mode_of_travel = $detail->travel_name; // This calls the accessor
            });
        }
        // dd($travelAuthorizationDetails);
        return response()->json(['travel_authorization_details' => $travelAuthorizationDetails]);
    }

    public function  getEmployeeById($id)
    {
        $user = User::whereId($id)->firstOrFail();

        return response()->json($user);
    }
}
