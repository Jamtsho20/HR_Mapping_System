<?php

namespace App\Http\Controllers\SystemSetting;

use App\Http\Controllers\Controller;
use App\Models\MasAttendanceFeature;
use Illuminate\Http\Request;

class MasAttendanceFeaturesController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:system-setting/mas-attendance-features,view')->only('index');
        $this->middleware('permission:system-setting/mas-attendance-features,create')->only('store');
        $this->middleware('permission:system-setting/mas-attendance-features,edit')->only('update');
        $this->middleware('permission:system-setting/mas-attendance-features,delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $privileges = $request->instance();
        $attendance = MasAttendanceFeature::filter($request)->orderBy('created_at', 'desc')->paginate(config('global.pagination'));

        return view('system-settings.mas-attendance-features.index', compact('privileges', 'attendance'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        return view('system-settings.mas-attendance-features.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'is_mandatory.is_active' => 'boolean',
            'status.is_active' => 'boolean',
        ]);

        $attendance = new \App\Models\MasAttendanceFeature();
        $attendance->name = $request->name;
        $attendance->description = $request->description;
        $attendance->is_mandatory = $request->input('is_mandatory.is_active', 0);
        $attendance->status = $request->input('status.is_active', 0);
        $attendance->save();

        return redirect()->route('mas-attendance-features.index')->with('msg_success', 'Attendance Feature created successfully');
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
        $feature = \App\Models\MasAttendanceFeature::findOrFail($id);
        return view('system-settings.mas-attendance-features.edit',compact('feature'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|max:255',
            'is_mandatory.is_active' => 'boolean',
            'status.is_active' => 'boolean',
        ]);

        $feature = \App\Models\MasAttendanceFeature::findOrFail($id);
        $feature->name = $request->name;
        $feature->description = $request->description;
        $feature->is_mandatory = $request->input('is_mandatory.is_active', 0);
        $feature->status = $request->input('status.is_active', 0);
        $feature->save();

        return redirect('system-setting/mas-attendance-features')->with('msg_success', 'Attendance Feature updated successfully');
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
            MasAttendanceFeature::findOrFail($id)->delete();

            return back()->with('msg_success', 'Attendance Feature has been deleted');
        } catch (\Exception $e) {
            return back()->with('msg_error', 'Attendance Feature cannot be deleted as it has been used by other module. For further information contact system admin.');
        }
    }
}
