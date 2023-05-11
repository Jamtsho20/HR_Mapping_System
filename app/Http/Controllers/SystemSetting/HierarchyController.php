<?php

namespace App\Http\Controllers\SystemSetting;

use App\Http\Controllers\Controller;
use App\Models\SystemHierarchy;
use Illuminate\Http\Request;


class HierarchyController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:system-setting/hierarchies,view')->only('index');
        $this->middleware('permission:system-setting/hierarchies,create')->only('store');
        $this->middleware('permission:system-setting/hierarchies,edit')->only('update');
        $this->middleware('permission:system-setting/hierarchies,delete')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $privileges = $request->instance();
        $hierarchies = SystemHierarchy::filter($request)->orderBy('hierarchy_name')->paginate(30)->withQueryString();

        return view('system-settings.hierarchy.index', compact('privileges', 'hierarchies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('system-settings.hierarchy.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    { {
            $request->validate([
                'hierarchy_name' => 'required',
                'level' => 'required',
                'value' => 'required',
                'start_date' => 'required|date|before:end_date',
                'end_date' => 'required|date|after:start_date',
                'status' => 'required',
            ]);

            $hierarchy = new SystemHierarchy();
            $hierarchy->hierarchy_name= $request->hierarchy_name;
            $hierarchy->level = $request->level;
            $hierarchy->value = $request->value;
            $hierarchy->start_date = $request->start_date;
            $hierarchy->end_date = $request->end_date;
            $hierarchy->status = $request->status;
            $hierarchy->save();

            return redirect('system-setting/hierarchies')->with('msg_success', 'Hierarchy created successfully');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SystemHierarchy $systemHierarchy)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $hierarchy = SystemHierarchy::findOrFail($id);
        
        return view('system-settings.hierarchy.edit', compact('hierarchy'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        $request->validate([
                'hierarchy_name' => 'required',
                'level' => 'required',
                'value' => 'required',
                'start_date' => 'required|date|before:end_date',
                'end_date' => 'required|date|after:start_date',
                'status' => 'required',
        ]);

        $hierarchy = SystemHierarchy::findOrFail($id);
      
        $hierarchy->hierarchy_name = $request->hierarchy_name;
        $hierarchy->level = $request->level;
        $hierarchy->value = $request->value;
        $hierarchy->start_date = $request->start_date;
        $hierarchy->end_date = $request->end_date;
        $hierarchy->status = $request->status;
        $hierarchy->save();

        return redirect('system-setting/hierarchies')->with('msg_success', 'Hierarchy Updated successfully');


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            SystemHierarchy::findOrFail($id)->delete();

            return back()->with('msg_success', 'Hierarchy has been deleted');
        } catch (\Exception $e) {
            return back()->with('msg_error', 'Hierarchy cannot be delete as it has been used by other module. For further information contact system admin.');
        }
    }
}
