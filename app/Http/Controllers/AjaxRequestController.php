<?php

namespace App\Http\Controllers;

use App\Models\AdvanceApplication;
use App\Models\ApprovingAuthority;
use App\Models\EmployeeLeave;
use App\Models\MasAdvanceTypes;
use App\Models\MasEmployeeJob;
use App\Models\MasGewog;
use App\Models\MasGradeStep;
use App\Models\MasLeavePolicy;
use App\Models\MasPayGroupDetail;
use App\Models\MasPaySlabDetails;
use App\Models\MasRegionLocation;
use App\Models\MasSection;
use App\Models\MasVillage;
use App\Models\User;
use App\Models\WorkHolidayList;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AjaxRequestController extends Controller
{ 
    /* write code related to ajax request */

    public function getGewog($id){
        $gewogs = MasGewog::where('mas_dzongkhag_id', $id)->get();
        return $gewogs;
    }

    public function getVillage($id){
        $villages = MasVillage::where('mas_gewog_id', $id)->get(['id', 'village']);
        return $villages;
    }

    public function getSection($id){
        $sections = MasSection::where('mas_department_id', $id)->get(['id', 'name']);
        return $sections;
    }

    public function getGradeStep($id){
        $gradeSteps = MasGradeStep::where('mas_grade_id', $id)->get(['id', 'name', 'starting_salary', 'point']);
        return $gradeSteps;
    }

    public function getPaySlabDetail($id){
        $paySlabDetail = MasPaySlabDetails::findOrFail($id);
        return $paySlabDetail;
    }
    
    public function getPayGroupDetail($id){
        $payGroupDetail = MasPayGroupDetail::findOrFail($id);
        return $payGroupDetail;
    }

    public function getRegionLocation($id){
        $regionLocation = MasRegionLocation::findOrFail($id);
        return $regionLocation;
    }
    
    public function getPayScale($id){
        $payScale = MasGradeStep::where('id', $id)->get(['starting_salary', 'increment', 'ending_salary']);
        return $payScale;
    }

    public function getLeaveBalance($id){
        $balance = EmployeeLeave::where('mas_leave_type_id', $id)->where('mas_employee_id', auth()->user()->id)->value('closing_balance');
        $leavePolicy = MasLeavePolicy::with('leavePolicyPlan')->where('mas_leave_type_id', $id)->whereStatus(1)->first();
        $attachmentRequired = $leavePolicy && $leavePolicy->leavePolicyPlan ? $leavePolicy->leavePolicyPlan->attachment_required : 0;

        return ['balance' => $balance ?? 0, 'leavePolicy' => $leavePolicy, 'attachment_required' => $attachmentRequired];
    }

    public function getNoOfDays(Request $request){
        $loggedInUserId = auth()->user()->id; 
        $loggedInUserOfficeId = MasEmployeeJob::where('mas_employee_id', $loggedInUserId)->value('mas_office_id');
        $loggedInUserRegion = DB::select(
                                        "select 
                                            t3.mas_region_id as region_id
                                        from mas_offices t1
                                        left join mas_dzongkhags t2 on t1.mas_dzongkhag_id = t2.id
                                        left join mas_region_locations t3 on t2.id = t3.mas_dzongkhag_id
                                        where t1.id = ?", [$loggedInUserOfficeId]);

        $fromDate = Carbon::parse($request->fromDate);
        $toDate = Carbon::parse($request->toDate);
        // dd($fromDate, $toDate);
        $fromDay = (int) $request->fromDay;
        $toDay = (int) $request->toDay;
        // dd(gettype($fromDay));
        $holidays = WorkHolidayList::whereJsonContains('region_id', (string)$loggedInUserRegion[0]->region_id)->get();
        $holidayDates = [];
        $totalDays = 0;
        // Create an array of all holiday dates
        foreach ($holidays as $holiday) {
            $holidayStart = Carbon::parse($holiday->start_date);
            $holidayEnd = Carbon::parse($holiday->end_date);
            // Add each day of the holiday period to the array
            for ($date = $holidayStart; $date->lte($holidayEnd); $date->addDay()) {
                $holidayDates[] = $date->format('Y-m-d');
            }
        }

        for ($date = $fromDate; $date->lte($toDate); $date->addDay()) {
            // Skip if the day is a holiday
            
            if (in_array($date->format('Y-m-d'), $holidayDates)) {
                continue;
            }
            // If it's Saturday, count as half day
            if ($date->isSaturday()) {
                $totalDays += 0.5;  
                continue;
            }
            // If it's Sunday, skip the day
            if ($date->isSunday()) {
                continue;
            }
            // Handle the first day (fromDate)
            if ($date->eq($fromDate)) {
                if ($fromDay == 1) {
                    $totalDays += 1; // Full day
                } elseif ($fromDay == 2) {
                    $totalDays += 0.5; // First half (morning)
                } elseif ($fromDay == 3) {
                    // If the leave starts from the second half, we should skip this day and start the next day as full.
                    $totalDays += 0.5; // Second half (afternoon)
                }
            } 
            // Handle the last day (toDate)
            elseif ($date->eq($toDate)) {
                if ($toDay == 1) {
                    $totalDays += 1; // Full day
                } elseif ($toDay == 2) {
                    // If the leave ends on the first half, we only count the first half
                    $totalDays += 0.5; // First half (morning)
                } elseif ($toDay == 3) {
                    $totalDays += 0.5; // Second half (afternoon)
                }
            } 
            // Handle normal weekdays in between fromDate and toDate
            else {
                $totalDays += 1;
            }
        }

        return $totalDays;
    }

    public function getEmployeeSelect($id) {
        $approvingAuthority = ApprovingAuthority::where('id', $id)->whereStatus(1)->first();
        $employeeSelect = [];
    
        if ($approvingAuthority && $approvingAuthority->has_employee_field) {
            $employeeSelect = User::whereHas('roles', function ($query) use ($approvingAuthority) {
                $query->where('roles.id', $approvingAuthority->role_id);
            })->get(['id', 'name', 'title', 'username']);
        }
    
        return response()->json([
            'has_employee_field' => $approvingAuthority->has_employee_field ?? false, 
            'employees' => $employeeSelect
        ]);
    }

    public function getAdvanceNumber($id) {
        $sifaInterestRate = 0;
        $advanceCode = MasAdvanceTypes::where('id', $id)->pluck('code')[0];

        $latestTransaction = AdvanceApplication::where('advance_type_id', $id)
                            ->latest('id') // Orders by id in descending order
                            ->first();
       
        // Extract the next sequence number: get last 4 digits if transaction exists, else default to 1
        $nextSequence = $latestTransaction ? (int)substr($latestTransaction->advance_no, -4) + 1 : 1;
        
        // Generate the new advance number with the incremented sequence
        $advanceNo = generateTransactionNumber($advanceCode, $nextSequence);

        // if advance type is SIFA LOAN then need to get its interest rate and sent it to frontend.
        if($id == SIFA_LOAN){
            $sifaInterestRate = SIFA_INTEREST_RATE;
        }
        
        return response()->json([
            'advance_no' => $advanceNo,
            'sifa_interest_rate' => $sifaInterestRate
        ]);
    }
}
