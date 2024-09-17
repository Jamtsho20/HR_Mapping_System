<?php

namespace App\Http\Controllers;

use App\Http\Controllers\WorkStructure\HolidayListController;
use App\Models\EmployeeLeave;
use App\Models\MasEmployeeJob;
use App\Models\MasGewog;
use App\Models\MasGradeStep;
use App\Models\MasPayGroupDetail;
use App\Models\MasPaySlabDetails;
use App\Models\MasRegionLocation;
use App\Models\MasSection;
use App\Models\MasVillage;
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
        $gradeSteps = MasGradeStep::where('mas_grade_id', $id)->get(['id', 'name']);
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
        return $balance ?? 0;
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
        $fromDay = $request->fromDay;
        $toDay = $request->toDay;
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
            }
            // If it's Sunday, skip the day
            if ($date->isSunday()) {
                continue;
            }
            // Check if it's the first day (fromDate)
            if ($date->eq($fromDate)) {
                if ($fromDay == 1) { // Full day
                    $totalDays += 1;
                } elseif ($fromDay == 2 || $fromDay == 3) { // First half or second half
                    $totalDays += 0.5;
                }
            } 
            // Check if it's the last day (toDate)
            elseif ($date->eq($toDate)) {
                if ($toDay == 1) { // Full day
                    $totalDays += 1;
                } elseif ($toDay == 2 || $toDay == 3) { // First half or second half
                    $totalDays += 0.5;
                }
            } 
            // Handle normal weekdays in between fromDate and toDate
            else {
                $totalDays += 1;
            }
        }
        // dd($totalDays);
        return $totalDays;
    }
}
