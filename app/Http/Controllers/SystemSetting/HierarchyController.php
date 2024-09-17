<?php

namespace App\Http\Controllers\SystemSetting;

use App\Http\Controllers\Controller;
use App\Models\ApprovingAuthority;
use App\Models\SystemHierarchy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HierarchyController extends Controller
{
    private $rules = [
        'hierarchy_name' => 'required',
        'hierarchies.*.level' => 'required'
    ];

    private $messages = [
        'hierarchies.*.level.required' => 'Level field is required',
    ];
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
        $hierarchies = SystemHierarchy::filter($request)->with('hierarchyLevels')->paginate(10)->withQueryString();
        // dd($hierarchies);
        return view('system-settings.hierarchy.index', compact('privileges', 'hierarchies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $approvingAuthorities = ApprovingAuthority::whereStatus(1)->get(['id', 'name', 'has_employee_field']);
        return view('system-settings.hierarchy.create', compact('approvingAuthorities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    { 

        $this->validate($request, $this->rules, $this->messages);

        DB::transaction(function () use ($request) {

            $hierarchy = new SystemHierarchy();
            $hierarchy->hierarchy_name = $request->hierarchy_name;
            $hierarchy->save();

            $level = [];
            foreach ($request->hierarchies as $key => $value) {
                $level[] = [
                    'level' => $value['level'],
                    'approving_authority_id' => $value['approving_authority_id'],
                    'start_date' => $value['start_date'],
                    'end_date' => $value['end_date'],
                    'status' => $value['status']
                ];
            }

            $hierarchy->hierarchyLevels()->createMany($level);
        });

        return back()->with('msg_success', 'Hierarchy have been created successfully.');
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
        $hierarchy = SystemHierarchy::with('hierarchyLevels')->findOrFail($id);
        $approvingAuthorities = ApprovingAuthority::where('status', 1)->get(['id', 'name', 'has_employee_field']);

        return view('system-settings.hierarchy.edit', compact('hierarchy', 'approvingAuthorities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        // dd($request->all());
        $this->validate($request, $this->rules, $this->messages);

        DB::transaction(function () use ($request, $id) {
            $hierarchy = SystemHierarchy::findOrFail($id);
            $hierarchy->hierarchy_name = $request->hierarchy_name;
            $hierarchy->save();

            $hierarchy->hierarchyLevels()->delete();

            foreach ($request->hierarchies as $key => $value) {
                $hierarchy->hierarchyLevels()->create([
                    'level' => $value['level'],
                    'approving_authority_id' => $value['approving_authority_id'],
                    'start_date' => $value['start_date'],
                    'end_date' => $value['end_date'],
                    'status' => $value['status']
                ]);
            }
        });

        return redirect('system-setting/hierarchies')->with('msg_success', 'Hierarchy have been updated successfully.');


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
