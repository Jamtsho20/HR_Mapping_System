<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\MasOfficeTiming;
use Illuminate\Http\Request;

class MasOfficeTimingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:master/office-timings,view')->only('index');
        $this->middleware('permission:master/office-timings,create')->only('store');
        $this->middleware('permission:master/office-timings,edit')->only('update');
        $this->middleware('permission:master/office-timings,delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $privileges = $request->instance();
        $officeTimings = MasOfficeTiming::filter($request)->orderBy('created_at','desc')->paginate(config('global.pagination'));

        return view('masters.office-timings.index', compact('privileges', 'officeTimings'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        return view('masters.office-timings.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'season' => 'required|in:1,2,3,4',
            'start_month' => 'required|in:JAN,FEB,MAR,APR,MAY,JUN,JUL,AUG,SEP,OCT,NOV,DEC',
            'end_month' => 'required|in:JAN,FEB,MAR,APR,MAY,JUN,JUL,AUG,SEP,OCT,NOV,DEC',
            'start_time' => 'required|date_format:H:i',
            'lunch_time_from' => 'required|date_format:H:i',
            'lunch_time_to' => 'required|date_format:H:i|after:lunch_time_from',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $timing = new \App\Models\MasOfficeTiming();
        $timing->season = $request->season;
        $timing->start_month = $request->start_month;
        $timing->end_month = $request->end_month;
        $timing->start_time = $request->start_time;
        $timing->lunch_time_from = $request->lunch_time_from;
        $timing->lunch_time_to = $request->lunch_time_to;
        $timing->end_time = $request->end_time;
        $timing->save();

        return redirect('master/office-timings')->with('msg_success', 'Office Timing created successfully');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(string $id)
    {
        $timing = \App\Models\MasOfficeTiming::findOrFail($id);
        return view('masters.office-timings.edit', compact('timing'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'season' => 'required|in:1,2,3,4',
            'start_month' => 'required|in:JAN,FEB,MAR,APR,MAY,JUN,JUL,AUG,SEP,OCT,NOV,DEC',
            'end_month' => 'required|in:JAN,FEB,MAR,APR,MAY,JUN,JUL,AUG,SEP,OCT,NOV,DEC',
            'start_time' => 'required',
            'lunch_time_from' => 'required',
            'lunch_time_to' => 'required',
            'end_time' => 'required',
        ]);


        $timing = MasOfficeTiming::findOrFail($id);
        $timing->season = $request->season;
        $timing->start_month = $request->start_month;
        $timing->end_month = $request->end_month;
        $timing->start_time = $request->start_time;
        $timing->lunch_time_from = $request->lunch_time_from;
        $timing->lunch_time_to = $request->lunch_time_to;
        $timing->end_time = $request->end_time;

        $timing->save();
        return redirect('master/office-timings')->with('msg_success', 'Office Timing updated successfully');
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
            MasOfficeTiming::findOrFail($id)->delete();

            return back()->with('msg_success', 'Office Timing has been deleted');
        } catch (\Exception $e) {
            return back()->with('msg_error', 'Office Timing cannot be delete as it has been used by other module. For further information contact system admin.');
        }
    }
}
