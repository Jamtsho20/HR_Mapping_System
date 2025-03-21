<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\SAP\ApiController;
use App\Models\AdvanceApplication;
use App\Models\ApprovingAuthority;
use App\Models\DsaClaimApplication;
use App\Models\DsaClaimType;
use App\Models\EmployeeLeave;
use App\Models\ExpenseApplication;
use App\Models\GoodIssueApplication;
use App\Models\GoodReceiptApplication;
use App\Models\LeaveEncashmentType;
use App\Models\MasAdvanceTypes;
use App\Models\MasConditionField;
use App\Models\MasEmployeeJob;
use App\Models\MasExpensePolicy;
use App\Models\MasExpenseType;
use App\Models\MasGewog;
use App\Models\MasGoodIssueType;
use App\Models\MasGoodReceiptType;
use App\Models\MasGradeStep;
use App\Models\MasLeavePolicy;
use App\Models\MasLeaveType;
use App\Models\MasPayGroupDetail;
use App\Models\MasPaySlabDetails;
use App\Models\MasRegionLocation;
use App\Models\MasRequisitionType;
use App\Models\MasSection;
use App\Models\MasSifaType;
use App\Models\MasTransferClaim;
use App\Models\MasTravelType;
use App\Models\MasVehicle;
use App\Models\MasVillage;
use App\Models\RequisitionApplication;
use App\Models\SystemHierarchyLevel;
use App\Models\TransferClaimApplication;
use App\Models\TravelAuthorizationApplication;
use App\Models\User;
use App\Models\WorkHolidayList;
use App\Services\ApprovalService;
use App\Traits\JsonResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\s;
use App\Models\GoodCommissionApplication;
use App\Models\MasCommissionTypes;
use App\Models\GoodReceiptApplicationDetail;
use App\Models\LeaveApplication;
use DateTime;
use App\Models\DsaClaimMappings;
use App\Models\MasDzongkhag;
use App\Models\MasGrnItem;
use App\Models\MasGrnItemDetail;
use App\Models\RequisitionDetail;

class AjaxRequestController extends Controller
{
    /* write code related to ajax request */
    use JsonResponseTrait;

    protected $sap;

    public function __construct(ApiController $sap)
    {
        $this->sap = $sap;
    }

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

    public function getLeaveBalance($id) //was done for json message purpose
    {
        try {
            $empGender = auth()->user()->gender;
            $leavePolicy = MasLeavePolicy::with('leavePolicyPlan')->where('type_id', $id)->whereStatus(1)->first();
            $allowedEmploymentType = array_values(json_decode($leavePolicy->leavePolicyPlan->can_avail_in, true));
            $empJobDetail = MasEmployeeJob::where('mas_employee_id', loggedInUser())->first();
            $leaveType = $leavePolicy && $leavePolicy->leaveType ? $leavePolicy->leaveType->name : '';

            if (!in_array((string)$empJobDetail->mas_employment_type_id, $allowedEmploymentType)) {
                return $this->errorResponse('You are not eligible to apply ' . $leaveType . ', based on your employment type.');
            }
            if (!$leavePolicy || !$leavePolicy->status || $leavePolicy->is_information_only) {
                return $this->errorResponse('You cannot apply leave as leave policy for this leave type has not been enforced or is for information purpose only, please contact system admin.');
            }

            $leavePolicyGender = $leavePolicy->leavePolicyPlan->gender ?? null;

            if ($leavePolicyGender === 3 || $leavePolicyGender === $empGender) {
                $balance = EmployeeLeave::where('mas_leave_type_id', $id)
                    ->where('mas_employee_id', auth()->user()->id)
                    ->value('closing_balance');

                // Check if the leave balance is 0
                if ($balance == 0) {
                    return $this->errorResponse('You are not eligible for this leave type since there is no leave balance.');
                }

                // If the gender and balance are valid, check leave limits and attachment requirements
                $leaveLimits = $leavePolicy && $leavePolicy->leavePolicyPlan ? json_decode($leavePolicy->leavePolicyPlan->leave_limits, true) : [];
                $isHalfDay = in_array(4, $leaveLimits); // Check if half day leave is allowed
                $attachmentRequired = $leavePolicy && $leavePolicy->leavePolicyPlan ? $leavePolicy->leavePolicyPlan->attachment_required : 0;

                return $this->successResponse([
                    'balance' => $balance,
                    'leavePolicy' => $leavePolicy,
                    'attachment_required' => $attachmentRequired,
                    'is_half_day' => $isHalfDay,
                ]);
            }

            return $this->errorResponse('You are not eligible for this leave type based on your gender.');
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred while fetching the leave balance, please try again.');
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

        // Get holidays for the logged-in user's region
        $holidayDates = $this->getHolidayDates($loggedInUserRegion);

        // Find the last working day before the new leave (skip holidays & weekends)
        $prevLeaveEndDate = $this->getLastValidLeaveDate($fromDate, $holidayDates);
        
        // Fetch previous leave ending exactly one day before the new leave
        $prevLeave = LeaveApplication::where('type_id', '<>', CASUAL_LEAVE)
            ->where('to_date', '=', $prevLeaveEndDate->format('Y-m-d'))
            ->where('created_by', auth()->id())
            ->latest('to_date')
            ->first();

        if ($leaveTypeId == CASUAL_LEAVE && $prevLeave) {
            if ($this->isConsecutiveLeaveViolation($prevLeave->to_date, $holidayDates, $fromDate)) {
                return $this->errorResponse('Casual Leave is not allowed, please try applying earned leave.');
            }
        }

        return $this->calculateLeaveDays($leaveTypeId, $fromDate, $toDate, $fromDay, $toDay, $holidayDates);
    }

        /**
     * Finds the last working day before the new leave start date by skipping holidays and weekends.
     */
    private function getLastValidLeaveDate($fromDate, $holidayDates)
    {
        $prevDate = clone $fromDate;
        $prevDate->modify('-1 day');

        // Keep moving back if the date is a holiday or weekend
        while (in_array($prevDate->format('Y-m-d'), $holidayDates) || in_array($prevDate->format('l'), ['Saturday', 'Sunday'])) {
            $prevDate->modify('-1 day');
        }

        return $prevDate;
    }

    private function isConsecutiveLeaveViolation($prevLeaveEndDate, $holidayDates, $fromDate)
    {
        $prevLeaveEnd = new \DateTime($prevLeaveEndDate);
        $nextDay = (clone $prevLeaveEnd)->modify('+1 day');
        // Flag to track if there's a working day in between
        $hasWorkingDayBetween = false;

        // Check for weekends and holidays between previous leave and new leave
        while ($nextDay < $fromDate) {
            if (in_array($nextDay->format('Y-m-d'), $holidayDates) || in_array($nextDay->format('l'), ['Saturday', 'Sunday'])) {
                $nextDay->modify('+1 day'); // Skip holidays and weekends
                continue;
            }
            
            // If we find a working day in between, it's **not** a violation
            $hasWorkingDayBetween = true;
            break;
        }

        // If **only holidays/weekends** exist between previous leave and the new leave, it's a violation
        return !$hasWorkingDayBetween;
    }

    private function calculateLeaveDays($leaveTypeId, $fromDate, $toDate, $fromDay, $toDay, $holidayDates)
    {
        try {
            $leavePolicy = MasLeavePolicy::with('leavePolicyPlan')
                ->where('type_id', $leaveTypeId)
                ->whereStatus(1)
                ->first();

            $leaveLimits = $leavePolicy->leavePolicyPlan
                ? json_decode($leavePolicy->leavePolicyPlan->leave_limits, true)
                : [];

            // Calculate initial days
            $dayDifference = $toDate->diff($fromDate)->days;
            $fromDayAdjustment = ($leaveTypeId == 2) ? 1 : (($fromDay === 2 || $fromDay === 3) ? 0.5 : 1);
            $toDayAdjustment = ($leaveTypeId == 2) ? 1 : (($toDay === 2 || $toDay === 3) ? 0.5 : 1);

            $totalDays = ($dayDifference === 0)
                ? $fromDayAdjustment + $toDayAdjustment - 1
                : $dayDifference + $fromDayAdjustment - 1 + $toDayAdjustment;

            // Adjust total days based on leave policy restrictions
            if (!empty($leaveLimits)) {
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

            $balance = EmployeeLeave::where('mas_leave_type_id', $leaveTypeId)
                ->where('mas_employee_id', auth()->user()->id)
                ->value('closing_balance');

            // Check if the leave balance is 0
            if ($balance != 0 && $totalDays > $balance) {
                return $this->errorResponse('You are not eligible for this leave type since total no. of days exceed leave balance.');
            }

            return $this->successResponse(['total_days' => max($totalDays, 0)]);
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred while calculating total no. of leave days. Please try again.');
        }
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

        $latestTransaction = AdvanceApplication::where('type_id', $id)
            ->latest('id') // Orders by id in descending order
            ->first();

        if ($latestTransaction) {
            // Extract the sequence part (last part after the last slash)
            preg_match('/(\d+)$/', $latestTransaction->advance_no, $matches);
            $lastSequence = $matches ? (int) $matches[0] : 0;
            // dd($lastSequence);
            $currentSequence = $lastSequence;
            // dd($nextSequence);
        } else {
            $currentSequence = 1;
        }

        // Generate the new advance number with the incremented sequence
        $advanceNo = generateTransactionNumber($advanceCode, $currentSequence);

        // if advance type is SIFA LOAN then need to get its interest rate and sent it to frontend.
        if ($id == SIFA_LOAN) {
            $sifaInterestRate = SIFA_INTEREST_RATE;
        }

        return response()->json([
            'advance_no' => $advanceNo,
            'sifa_interest_rate' => $sifaInterestRate,
        ]);
    }

    public function getTravelNumber($id)
    {
        $code = MasTravelType::where('id', $id)->value('code');
        $latestTransaction = TravelAuthorizationApplication::latest('id')->first();

        // Check if the latest transaction exists
        if ($latestTransaction) {
            // Extract the sequence part (last part after the last slash)
            preg_match('/(\d+)$/', $latestTransaction->travel_authorization_no, $matches);
            $lastSequence = $matches ? (int) $matches[0] : 0;
            // dd($lastSequence);
            $currentSequence = $lastSequence;
            // dd($nextSequence);
        } else {
            $currentSequence = 1;
        }

        // Generate the travel authorization number
        $authorizationNo = generateTransactionNumber($code, $currentSequence);


        return response()->json([
            'travel_no' => $authorizationNo,
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
                        // ->whereMasRegionId($loggedInUserRegion[0]->region_id)
                        ->whereStatus(1);
                }]);
        }])
            ->whereTypeId($id)
            ->whereStatus(1)
            ->first();

        $attachmentRequired = $expensePolicy && $expensePolicy->rateDefinition ? $expensePolicy->rateDefinition->attachment_required : 0;
        $limitAmount = $expensePolicy && $expensePolicy->rateDefinition->expenseRateLimits->isNotEmpty() ? $expensePolicy->rateDefinition->expenseRateLimits[0]->limit_amount : 0;

        // return response()->json(['attachment_required' => $attachmentRequired, 'limit_amount' => $limitAmount, 'region_name' => $loggedInUserRegion[0]->region_name]);
        return response()->json(['attachment_required' => $attachmentRequired, 'limit_amount' => $limitAmount]);
    }

    public function getApprovalHeadTypes($id)
    {
        $modelMap = [
            1 => MasLeaveType::class,
            2 => MasExpenseType::class,
            3 => MasAdvanceTypes::class,
            4 => LeaveEncashmentType::class,
            5 => MasRequisitionType::class,
            6 => MasTransferClaim::class,
            7 => MasTravelType::class,
            8 => MasSifaType::class,
            9 => DsaClaimType::class,
            10 => MasCommissionTypes::class,
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

    public function getExpenseNumber($id)
    {
        $expenseCode = MasExpenseType::where('id', $id)->pluck('code')[0];

        $latestTransaction = ExpenseApplication::where('type_id', $id)
            ->latest('id') // Orders by id in descending order
            ->first();

        if ($latestTransaction) {
            // Extract the sequence part (last part after the last slash)
            preg_match('/(\d+)$/', $latestTransaction->expense_no, $matches);
            $lastSequence = $matches ? (int) $matches[0] : 0;
            // dd($lastSequence);
            $currentSequence = $lastSequence;
            // dd($nextSequence);
        } else {
            $currentSequence = 1;
        }

        // Extract the next sequence number: get last 4 digits if transaction exists, else default to 1
        // $nextSequence = $latestTransaction ? (int) substr($latestTransaction->expense_no, -4) + 1 : 1;

        // // Generate the new advance number with the incremented sequence
        $expenseNo = generateTransactionNumber($expenseCode, $currentSequence);

        return response()->json([
            'expense_no' => $expenseNo,
        ]);
    }

    public function getDsaClaimNumber()
    {
        $claimCode = MasExpenseType::where('id', 3)->pluck('code')[0];

        $latestTransaction = DsaClaimApplication::latest('id')->first();

        if ($latestTransaction) {
            // Extract the sequence part (last part after the last slash)
            preg_match('/(\d+)$/', $latestTransaction->dsa_claim_no, $matches);
            $lastSequence = $matches ? (int) $matches[0] : 0;
            // dd($lastSequence);
            $currentSequence = $lastSequence;
            // dd($nextSequence);
        } else {
            $currentSequence = 1;
        }

        // Generate the new advance number with the incremented sequence
        $claimNo = generateTransactionNumber($claimCode, $currentSequence);

        return $claimNo;
    }

    public function getTransferClaimNumber($id)
    {
        $claimCode = MasTransferClaim::where('id', $id)->pluck('code')[0];

        $latestTransaction = TransferClaimApplication::latest('id')->first();


        if ($latestTransaction) {
            // Extract the sequence part (last part after the last slash)
            preg_match('/(\d+)$/', $latestTransaction->transfer_claim_no, $matches);
            $lastSequence = $matches ? (int) $matches[0] : 0;
            // dd($lastSequence);
            $currentSequence = $lastSequence;
            // dd($nextSequence);
        } else {
            $currentSequence = 1;
        }
        // Generate the new advance number with the incremented sequence
        $claimNo = generateTransactionNumber($claimCode, $currentSequence);

        return $claimNo;
    }

    public function getTravelAuthorizationDetails($id)
    {
        $travelAuthorizationDetails = TravelAuthorizationApplication::with('details')->find($id);
        $total_days = $travelAuthorizationDetails->total_days;
        if (!$travelAuthorizationDetails) {
            return response()->json(['message' => 'Travel authorization not found'], 404);
        }
        if ($travelAuthorizationDetails->details) {
            $travelAuthorizationDetails->details->each(function ($detail) {

                $detail->mode_of_travel = $detail->travel_name;

                if ($detail->number_of_days) {
                    $detail->no_of_days = $detail->number_of_days;
                } else {
                    if ($detail->from_date && $detail->to_date) {
                        $fromDate = new \DateTime($detail->from_date);
                        $toDate = new \DateTime($detail->to_date);
                        $interval = $fromDate->diff($toDate);
                        $detail->no_of_days = $interval->days + 1;
                    }
                }
            });
        }
        return response()->json(['travel_authorization_details' => $travelAuthorizationDetails, 'total_days' => $total_days]);
    }

    public function getTravelAuthorizationDetailsMultiple(Request $request)
    {
        // Ensure you get an array of IDs from the request
        $ids = $request->input('ids');

        // Validate the incoming request
        if (empty($ids) || !is_array($ids)) {
            return response()->json(['message' => 'Invalid or missing travel authorization IDs'], 400);
        }

        // Fetch travel authorizations based on the provided IDs
        $travelAuthorizationDetails = TravelAuthorizationApplication::with('details')
            ->whereIn('id', $ids)
            ->get(); // Get multiple travel authorizations
        $advanceDetail = AdvanceApplication::whereIn('travel_authorization_id', $ids)->get();
        // Check if any travel authorizations were found
        if ($travelAuthorizationDetails->isEmpty()) {
            return response()->json(['message' => 'No travel authorizations found for the given IDs'], 404);
        }

        $attachments = DsaClaimMappings::whereIn('travel_authorization_id', $ids)->get(['travel_authorization_id', 'attachment']);

        // Loop through each travel authorization and process its details
        $travelAuthorizationDetails->each(function ($travelAuthorization) {
            // Process the details for each travel authorization
            $travelAuthorization->details->each(function ($detail) {
                $detail->mode_of_travel = $detail->travel_name;

                // Calculate the number of days if not provided
                if ($detail->number_of_days) {
                    $detail->no_of_days = $detail->number_of_days;
                } else {
                    if ($detail->from_date && $detail->to_date) {
                        $fromDate = new \DateTime($detail->from_date);
                        $toDate = new \DateTime($detail->to_date);
                        $interval = $fromDate->diff($toDate);
                        $detail->no_of_days = $interval->days + 1;
                    }
                }
            });
        });

        // Prepare the response data
        $response = [
            'travel_authorizations' => $travelAuthorizationDetails->map(function ($travelAuthorization) use ($advanceDetail) {
                return [
                    'travelAuthorization' => $travelAuthorization,
                    'id' => $travelAuthorization->id,
                    'total_days' => $travelAuthorization->total_days,
                    'details' => $travelAuthorization->details,
                    'advance_details' => $advanceDetail->where('travel_authorization_id', $travelAuthorization->id)->first()
                ];
            }),
            'attachments' => $attachments,
            'advance_ids' => $advanceDetail->pluck('id')
        ];

        // return response()->json($response);

        // Return the response with the travel authorization details and total days
        return response()->json(['travel_authorization_details' => $response]);
    }


    public function getEmployeeById($id)
    {
        $user = User::whereId($id)->firstOrFail();

        return response()->json($user);
    }

    public function getDsaAdvancebyTravelAuth($id)
    {
        $advances = AdvanceApplication::where('travel_authorization_id', $id)->whereStatus(3)->get();

        return response()->json($advances);
    }

    public function getRequisitionNumber($id)
    {
        try {
            $requisitionType = MasRequisitionType::findOrFail($id);
            $latestTransaction = RequisitionApplication::latest('id')->first();
            // Extract the next sequence number: get last 4 digits if transaction exists, else default to 1
            $nextSequence = $latestTransaction ? (int) substr($latestTransaction->requisition_no, -4) + 1 : 1;
            $requisitionNo = generateTransactionNumber($requisitionType->code, $nextSequence);
            return $this->successResponse(['requisition_no' => $requisitionNo]);
        } catch (\Exception $e) {
            return $this->errorResponse('Something went wront while trying to generate requisition no, please try again.');
        }
    }

    public function getRequisitionDetails($id)
    {
        try {
            $requisitionDetails = RequisitionApplication::with('details')->findOrFail($id);
            return $this->successResponse(['requisition_details' => $requisitionDetails]);
        } catch (\Exception $e) {
            return $this->errorResponse('Something went wrong while trying to fetch details, please try again.');
        }
    }

    public function getDsaAdvanceDetails($id)
    {
        $advance = AdvanceApplication::whereId($id)->first();

        return response()->json($advance);
    }

    public function getVehicleDetailTypeById($id)
    { // Vehicle Detail and Type
        $vehicleDetailType = MasVehicle::with('vehicleType')->whereId($id)->first();

        return response()->json($vehicleDetailType);
    }

    public function getAssetNoByGrnId($grnId)
    {
        $uomValue = "";
        $itemDescription = ""; //default item description if asset description null
        $dzongkhags = MasDzongkhag::get(['id', 'dzongkhag']);
        try{
            $uom = MasGrnItemDetail::where('grn_id', $grnId)
                ->with(['item' => function ($query) {
                    $query->select('id', 'uom', 'item_description'); // Only fetch the 'uom' field
                }])
                ->first();
            $uomValue = $uom->item->uom;
            $itemDescription = $uom->item->item_description;

            $assetNos = RequisitionDetail::where('grn_item_id', $grnId)
                ->whereHas('serials', function ($query) {
                    $query->where('is_commissioned', 0);
                })
                ->with(['serials' => function ($query) use($itemDescription) {
                    $query->where('is_commissioned', 0)->selectRaw("id, requisition_detail_id, asset_serial_no, IFNULL(asset_description, '$itemDescription') AS asset_description"); //incase if description is null in received serials the sent default item description
                }])
                ->selectRaw("id, requisition_id, grn_item_id, CONCAT(GREATEST(0, received_quantity - commissioned_quantity - transferred_quantity - returned_quantity), ' $uomValue') AS qty_at_hand")
                ->get();
            //   dd($assetNos);
            if ($assetNos->isEmpty()) {
                return $this->errorResponse('No asset numbers found for the provided GRN number.');
            }
            // dd($assetNos);
            return $this->successResponse(['assetNos' => $assetNos, 'dzongkhags' => $dzongkhags]);
        }catch(\Exception $e){
            \Log::info("asset commission: " . $e->getMessage());
            return $this->internalServerErrorResponse('Something went wrong while fetching asset numbers. Please try again.');
        }   
    }
}
