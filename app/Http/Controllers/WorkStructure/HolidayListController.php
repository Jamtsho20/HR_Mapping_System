<?php

namespace App\Http\Controllers\WorkStructure;

use App\Http\Controllers\Controller;
use App\Models\MasRegion;
use App\Models\WorkHolidayList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HolidayListController extends Controller
{
    protected $rules = [
        'holiday_name' => 'required',
        'holiday_type' => 'required',
        'start_date' => 'required|date|before_or_equal:end_date',
        'end_date' => 'required|date|after_or_equal:start_date',
    ];

    public function __construct()
    {
        $this->middleware('permission:work-structure/holiday-lists,view')->only('index');
        $this->middleware('permission:work-structure/holiday-lists,create')->only('store');
        $this->middleware('permission:work-structure/holiday-lists,edit')->only('update');
        $this->middleware('permission:work-structure/holiday-lists,delete')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $holidays = WorkHolidayList::filter($request)->orderBy('holiday_name')->paginate(30)->withQueryString();
        $regions = MasRegion::select('id', 'region_name')->get();     
        $dates = DB::table("work_holiday_lists")->distinct()->selectRaw("YEAR(start_date) as year")->pluck('year')->toArray();
    
        return view('work-structure.holiday-list.index', compact('holidays', 'privileges', 'regions','dates'));
    }

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->rules);

        $holiday = new WorkHolidayList;
        $holiday->holiday_name = $request->holiday_name;
        $holiday->holiday_type = $request->holiday_type;
        $holiday->region_id = $request->mas_region_id;
        $holiday->start_date = $request->start_date;
        $holiday->end_date = $request->end_date;
        $holiday->save();

        return back()->with('msg_success', 'Holiday created successfully');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate($this->rules);

        $holiday = WorkHolidayList::findOrFail($id);
        $holiday->holiday_name = $request->holiday_name;
        $holiday->holiday_type = $request->holiday_type;
        $holiday->region_id = $request->mas_region_id;
        $holiday->start_date = $request->start_date;
        $holiday->end_date = $request->end_date;
        $holiday->save();

        return back()->with('msg_success', 'Holiday has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            WorkHolidayList::findOrFail($id)->delete();

            return back()->with('msg_success', 'Holiday list has been deleted');
        } catch (\Exception $e) {
            return back()->with('msg_error', 'Holiday list cannot be delete as it has been used by other module. For further information contact system admin.');
        }
    }
}
