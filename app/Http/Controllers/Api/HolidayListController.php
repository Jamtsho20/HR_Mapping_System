<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\WorkHolidayList;
use App\Models\MasRegion;
use App\Traits\JsonResponseTrait;

use Illuminate\Support\Facades\DB;


class HolidayListController extends Controller
{
    use JsonResponseTrait;
    public function index(Request $request){
        try{
            $holidays = WorkHolidayList::filter($request)
            ->orderBy('start_date')->get(['id', 'holiday_name', 'start_date', 'end_date']);
            $dates = DB::table("work_holiday_lists")->distinct()->selectRaw("YEAR(start_date) as year")->pluck('year')->toArray();

            return response()->json(['holidays' => $holidays, 'dates' => $dates], 200);
        }catch(\Exception $e){

            return $this->errorResponse($e->getMessage());
        }


    }

}
