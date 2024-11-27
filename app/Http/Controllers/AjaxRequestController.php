<?php

namespace App\Http\Controllers;

use App\Models\AdvanceApplication;
use App\Models\ApprovingAuthority;
use App\Models\DsaClaimApplication;
use App\Models\EmployeeLeave;
use App\Models\ExpenseApplication;
use App\Models\LeaveApplication;
use App\Models\LeaveEncashmentType;
use App\Models\MasAdvanceTypes;
use App\Models\MasApprovalHeadTypes;
use App\Models\MasConditionField;
use App\Models\MasEmployeeJob;
use App\Models\MasExpensePolicy;
use App\Models\MasExpenseType;
use App\Models\MasGewog;
use App\Models\MasGradeStep;
use App\Models\MasLeavePolicy;
use App\Models\MasLeaveType;
use App\Models\MasPayGroupDetail;
use App\Models\MasPaySlabDetails;
use App\Models\MasRegionLocation;
use App\Models\MasSection;
use App\Models\MasTransferClaim;
use App\Models\MasSifaType;
use App\Models\MasTravelType;
use App\Models\MasVillage;
use App\Models\SystemHierarchyLevel;
use App\Models\TransferClaimApplication;
use App\Models\TravelAuthorizationApplication;
use App\Models\User;
use App\Services\ApprovalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AjaxRequestController extends Controller
{
    /* write code related to ajax request */

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

    public function getLeaveBalance($id)
    {
        $balance = EmployeeLeave::where('mas_leave_type_id', $id)->where('mas_employee_id', auth()->user()->id)->value('closing_balance');
        $leavePolicy = MasLeavePolicy::with('leavePolicyPlan')->where('mas_leave_type_id', $id)->whereStatus(1)->first();
        $attachmentRequired = $leavePolicy && $leavePolicy->leavePolicyPlan ? $leavePolicy->leavePolicyPlan->attachment_required : 0;

        return ['balance' => $balance ?? 0, 'leavePolicy' => $leavePolicy, 'attachment_required' => $attachmentRequired];
    }

    // public function getNoOfDays(Request $request){
    //     // $loggedInUserId = auth()->user()->id;
    //     // $loggedInUserOfficeId = MasEmployeeJob::where('mas_employee_id', $loggedInUserId)->value('mas_office_id');
    //     // $loggedInUserRegion = DB::select(
    //     //                                 "select
    //     //                                     t3.mas_region_id as region_id
    //     //                                 from mas_offices t1`
    //     //                                 left join mas_dzongkhags t2 on t1.mas_dzongkhag_id = t2.id
    //     //                                 left join mas_region_locations t3 on t2.id = t3.mas_dzongkhag_id
    //     //                                 where t1.id = ?", [$loggedInUserOfficeId]);

    //     $loggedInUserRegion = loggedInUserRegion(); //defined in helpers.php for common use as an when required to be use in appliocation

    //     $fromDate = Carbon::parse($request->fromDate);
    //     $toDate = Carbon::parse($request->toDate);
    //     // dd($fromDate, $toDate);
    //     $fromDay = (int) $request->fromDay;
    //     $toDay = (int) $request->toDay;
    //     // dd(gettype($fromDay));
    //     $holidays = WorkHolidayList::whereJsonContains('region_id', (string) $loggedInUserRegion[0]->region_id)->get();
    //     $holidayDates = [];
    //     $totalDays = 0;
    //     // Create an array of all holiday dates
    //     foreach ($holidays as $holiday) {
    //         $holidayStart = Carbon::parse($holiday->start_date);
    //         $holidayEnd = Carbon::parse($holiday->end_date);
    //         // Add each day of the holiday period to the array
    //         for ($date = $holidayStart; $date->lte($holidayEnd); $date->addDay()) {
    //             $holidayDates[] = $date->format('Y-m-d');
    //         }
    //     }

    //     for ($date = $fromDate; $date->lte($toDate); $date->addDay()) {
    //         // Skip if the day is a holiday

    //         if (in_array($date->format('Y-m-d'), $holidayDates)) {
    //             continue;
    //         }
    //         // If it's Saturday, count as half day
    //         if ($date->isSaturday()) {
    //             $totalDays += 0.5;
    //             continue;
    //         }
    //         // If it's Sunday, skip the day
    //         if ($date->isSunday()) {
    //             continue;
    //         }
    //         // Handle the first day (fromDate)
    //         if ($date->eq($fromDate)) {
    //             if ($fromDay == 1) {
    //                 $totalDays += 1; // Full day
    //             } elseif ($fromDay == 2) {
    //                 $totalDays += 0.5; // First half (morning)
    //             } elseif ($fromDay == 3) {
    //                 // If the leave starts from the second half, we should skip this day and start the next day as full.
    //                 $totalDays += 0.5; // Second half (afternoon)
    //             }
    //         }
    //         // Handle the last day (toDate)
    //         elseif ($date->eq($toDate)) {
    //             if ($toDay == 1) {
    //                 $totalDays += 1; // Full day
    //             } elseif ($toDay == 2) {
    //                 // If the leave ends on the first half, we only count the first half
    //                 $totalDays += 0.5; // First half (morning)
    //             } elseif ($toDay == 3) {
    //                 $totalDays += 0.5; // Second half (afternoon)
    //             }
    //         }
    //         // Handle normal weekdays in between fromDate and toDate
    //         else {
    //             $totalDays += 1;
    //         }
    //     }

    //     return $totalDays;
    // }
    public function getNoOfDays(Request $request)
    {
        $fromDate = new \DateTime($request->input('from_date'));
        $toDate = new \DateTime($request->input('to_date'));
        $fromDay = (int) $request->input('from_day');
        $toDay = (int) $request->input('to_day');

        // Calculate the difference in days
        $dayDifference = $toDate->diff($fromDate)->days;

        // Adjust based on day selections (full day, half day, etc.)
        $fromDayAdjustment = ($fromDay === 2 || $fromDay === 3) ? 0.5 : 1;
        $toDayAdjustment = ($toDay === 2 || $toDay === 3) ? 0.5 : 1;

        // Calculate total days
        $totalDays = ($dayDifference === 0)
        ? $fromDayAdjustment + $toDayAdjustment - 1
        : $dayDifference + $fromDayAdjustment - 1 + $toDayAdjustment;

        // Count weekends (Saturdays, Sundays) and adjust
        $sundays = 0;
        $saturdays = 0;

        $currentDate = clone $fromDate;
        while ($currentDate <= $toDate) {
            if ($currentDate->format('w') == 0) { // Sunday
                $sundays++;
            }
            if ($currentDate->format('w') == 6) { // Saturday
                $saturdays++;
            }
            $currentDate->modify('+1 day');
        }

        // Adjust the total days by excluding weekends
        $totalDays -= $sundays;
        $totalDays -= ($saturdays * 0.5);

        // Return the calculated leave days
        return response()->json(['total_days' => max($totalDays, 0)]);
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

    public function getTravelNumber($id)
    {
        $travelAuthPrefix = MasTravelType::where('id', $id)->value('code');
        $latestTransaction = TravelAuthorizationApplication::latest('id')->first();

        $nextSequence = $latestTransaction ? (int)substr($latestTransaction->travel_authorization_no, -4) + 1 : 1;
        $authorizationNo = generateTransactionNumber($travelAuthPrefix, $nextSequence);

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
            6 => MasTransferClaim::class,
            7 => MasSifaType::class,
            8 => MasTravelType::class,
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

    public function bulkApprovalRejection(Request $request)
    {
        $modelMap = [
            1 => LeaveApplication::class,
            2 => ExpenseApplication::class,
            3 => AdvanceApplication::class,
            4 => TransferClaimApplication::class,
        ];

        $model = $modelMap[$request->item_type_id] ?? null;

        $action = $request->action;
        $itemIds = $request->item_ids;
        $status = ($action === 'approve') ? 2 : -1;
        $rejectRemarks = $request->input('reject_remarks', '');
        $actionBy = auth()->id();
        $responseMessage = $action === 'approve' ? 'approved.' : 'rejected.';

        DB::beginTransaction();
        try {
            $approvalService = new ApprovalService();

            foreach ($itemIds as $id) {
                $application = $model::findOrFail($id);

                $applicationHistory = $application->histories
                    ->where('application_type', $model)
                    ->where('application_id', $id)
                    ->first();

                // Update leave application status
                $application->update([
                    'status' => $status,
                    'updated_by' => $actionBy,
                ]);

                // Forward application if approved
                $updateData = [
                    'status' => $status,
                    'remarks' => $rejectRemarks,
                    'action_performed_by' => $actionBy,
                ];

                if ($action === 'approve' && $applicationHistory) {
                    $applicationForwardedTo = $approvalService->applicationForwardedTo($id, $model);

                    if ($applicationForwardedTo && isset($applicationForwardedTo['next_level'])) {
                        $updateData = array_merge($updateData, [
                            'level_id' => $applicationForwardedTo['next_level']->id,
                            'approver_role_id' => $applicationForwardedTo['approver_details']['approver_role_id'],
                            'approver_emp_id' => $applicationForwardedTo['approver_details']['user_with_approving_role']->id,
                            'level_sequence' => $applicationForwardedTo['next_level']->sequence,
                        ]);
                        // Attempt to send email to next approver need to work on it
                        // try {
                        //     Mail::to($nextApprover->email)->send(new NextApproverNotificationMail($application, $nextApprover));
                        // } catch (\Exception $e) {
                        //     \Log::error('Failed to send email to next approver: ' . $e->getMessage());
                        // }
                    } elseif ($applicationForwardedTo && isset($applicationForwardedTo['application_status']) && $applicationForwardedTo['application_status'] === 'max_level_reached') {
                        // Finalize approval if it's at the maximum level
                        $application->update([
                            'status' => 3, // 3 could represent 'final approved'
                            'updated_by' => $actionBy,
                        ]);
                        $updateData['status'] = 3; // Mark the history entry as final approved
                    } elseif ($applicationForwardedTo && $applicationForwardedTo['application_status'] === 3) {
                        $application->update([
                            'status' => $applicationForwardedTo['application_status'], // 3 could represent 'final approved'
                            'updated_by' => $actionBy,
                        ]);
                        $updateData['status'] = $applicationForwardedTo['application_status'];
                    }
                }
                // Update application history
                if ($applicationHistory) {
                    $applicationHistory->update($updateData);
                }

                // Attempt to send email to applicant about the approval/rejection status need to work on it
                // try {
                //     Mail::to($user->email)->send(new LeaveApplicationStatusMail($application, $action, $rejectRemarks));
                // } catch (\Exception $e) {
                //     \Log::error('Failed to send email to applicant: ' . $e->getMessage());
                // }
            }

            DB::commit();

            $model = preg_replace(
                ['/App\\\\Models\\\\/', '/([a-z])Application/'],
                ['', '$1 Application'],
                $model
            );

            return response()->json(['msg_success' => 'Selected ' . Str::plural(strtolower($model)) . ' have been successfully ' . $responseMessage], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Bulk approval/rejection error: ' . $e->getMessage());

            return response()->json(['msg_error' => 'An error occurred during the operation.'], 500);
        }
    }

    public function getExpenseNumber($id)
    {
        $expenseCode = MasExpenseType::where('id', $id)->pluck('code')[0];

        $latestTransaction = ExpenseApplication::where('mas_expense_type_id', $id)
            ->latest('id') // Orders by id in descending order
            ->first();

        // Extract the next sequence number: get last 4 digits if transaction exists, else default to 1
        $nextSequence = $latestTransaction ? (int) substr($latestTransaction->expense_no, -4) + 1 : 1;

        // Generate the new advance number with the incremented sequence
        $expenseNo = generateTransactionNumber($expenseCode, $nextSequence);

        return response()->json([
            'expense_no' => $expenseNo
        ]);
    }

    public function getDsaClaimNumber()
    {
        $claimCode = MasExpenseType::where('id', 3)->pluck('code')[0];

        $latestTransaction = DsaClaimApplication::latest('id')->first();

        // Extract the next sequence number: get last 4 digits if transaction exists, else default to 1
        $nextSequence = $latestTransaction ? (int) substr($latestTransaction->claim_no, -4) + 1 : 1;

        // Generate the new advance number with the incremented sequence
        $claimNo = generateTransactionNumber($claimCode, $nextSequence);

        return $claimNo;
    }

    public function getTransferClaimNumber()
    {
        $claimCode = MasExpenseType::where('id', 4)->pluck('code')[0];

        $latestTransaction = TransferClaimApplication::latest('id')->first();

        // Extract the next sequence number: get last 4 digits if transaction exists, else default to 1
        $nextSequence = $latestTransaction ? (int) substr($latestTransaction->transfer_claim_no, -4) + 1 : 1;

        // Generate the new advance number with the incremented sequence
        $claimNo = generateTransactionNumber($claimCode, $nextSequence);

        return $claimNo;
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

    public function getDsaAdvancebyTravelAuth($id) {
        $advances = AdvanceApplication::where('travel_authorization_id', $id)->get();

        return response()->json($advances);

    }
}
