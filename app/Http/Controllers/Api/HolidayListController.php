<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\WorkHolidayList;
use App\Models\MasRegion;
use App\Traits\JsonResponseTrait;
use App\Models\SystemNotification;

use Illuminate\Support\Facades\DB;


class HolidayListController extends Controller
{
    use JsonResponseTrait;
    public function index(Request $request){
        try{
            $holidays = WorkHolidayList::filter($request)
            ->orderBy('start_date')->where('status', 1)->get(['id', 'holiday_name', 'start_date', 'end_date']);
            $dates = DB::table("work_holiday_lists")->distinct()->selectRaw("YEAR(start_date) as year")->pluck('year')->toArray();

            return response()->json(['holidays' => $holidays, 'dates' => $dates], 200);
        }catch(\Exception $e){

            return $this->errorResponse($e->getMessage());
        }


    }

public function notification(Request $request){
    try{
    $notifications = SystemNotification::get();
    return response()->json(
           $notifications
        );
    }

    catch(\Exception $e){
        return $this->errorResponse($e->getMessage());
    }

}
}
