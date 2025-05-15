<?php

namespace App\Http\Controllers\SystemSetting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SystemMenu;
use App\Models\SystemSubMenu;
use App\Models\RolePermission;

class ModuleController extends Controller
{
    private $rules = [
        'main_module_name' => 'required',
        'module_icon' => 'required',
        'module_display_order' => 'required|integer',
        'submodules.*.sub_module_name' => 'required',
        'submodules.*.route' => 'required',
        'submodules.*.display_order' => 'required|integer',
    ];

    private $messages = [
        'main_module_name.required' => 'Main Module Name field is required',
        'module_icon.required' => 'Module Icon field is required',
        'module_display_order.required' => 'Display Order field is required',
        'module_display_order.integer' => 'Display Order field must be a valid number',
        'submodules.*.sub_module_name.required' => 'Sub Module Name field is required',
        'submodules.*.route.required' => 'Route field is required',
        'submodules.*.display_order.required' => 'Display Order field is required',
        'submodules.*.display_order.integer' => 'Display Order field must be a valid number'
    ];

    public function __construct()
    {
        $this->middleware('permission:system-setting/modules,view')->only('index');
        $this->middleware('permission:system-setting/modules,create')->only('create', 'store');
        $this->middleware('permission:system-setting/modules,edit')->only('edit', 'update');
        $this->middleware('permission:system-setting/modules,delete')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $modules = SystemMenu::select('id', 'name', 'icon')->paginate(50);
        return view('system-settings.modules.index', compact('modules', 'privileges'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('system-settings.modules.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->rules, $this->messages);

        \DB::transaction(function () use ($request) {

            $module = new SystemMenu;

            $module->name = $request->main_module_name;
            $module->icon = $request->module_icon;
            $module->display_order = $request->module_display_order;
            $module->save();

            $subModules = [];

            foreach($request->submodules as $key => $value){
                $subModules[] = [
                    'name' => $value['sub_module_name'],
                    'route' => $value['route'],
                    'display_order' => $value['display_order']
                ];
            }

            $module->systemSubMenus()->createMany($subModules); 

        });

        return redirect('system-setting/modules')->with('msg_success', 'Module successfully created');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $module = SystemMenu::with('systemSubMenus')->findOrFail($id);
        return view('system-settings.modules.edit', compact('module'));
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
        $this->validate($request, $this->rules, $this->messages);

        \DB::transaction(function () use ($request, $id) {
            $module = SystemMenu::findOrFail($id);

            $module->name = $request->main_module_name;
            $module->icon = $request->module_icon;
            $module->display_order = $request->module_display_order;
            $module->save();

            //collect the ids of the SystemSubMenu table into an array from the database
            $subModuleIdsFromTheDatabase = SystemSubMenu::where('system_menu_id', $module->id)->pluck('id')->toArray();
            $subModuleIdsFromTheDatabaseCount = sizeof($subModuleIdsFromTheDatabase);

            //count the total number of values in the array
            $subModuleIdsFromRequestCount = sizeof($request->submodules);

            //store the incoming form values in an array but only the sub modules/menus id
            foreach ($request->submodules as $value) {
                $subModuleIdsFromRequest [] = (int) $value['sub_module_id'];
            }

            //if the count from the database is greater than the count from the form request then there is some sub modules to be deleted else its an addition
            if ($subModuleIdsFromTheDatabaseCount > $subModuleIdsFromRequestCount) {
                //if the count is not equal then get the unique ids by comparing the above two arrays [subModuleIdsFromTheDatabase, subModuleIdsFromRequest]
                $uniqueSubModuleIds = array_merge(array_diff($subModuleIdsFromTheDatabase, $subModuleIdsFromRequest), array_diff($subModuleIdsFromRequest, $subModuleIdsFromTheDatabase));

                //before deleting the sub module, first we need to remove the role allocated to the particular module from the role permission table
                RolePermission::whereIn('system_sub_menu_id', $uniqueSubModuleIds)->delete();

                //after that remove the deleted sub module from the system_sub_menus table
                SystemSubMenu::whereIn('id', $uniqueSubModuleIds)->delete();
            }

            //if the count is equal or the count from the form request is greater than that of the database count then just do the normal updateOrCreate
            foreach($request->submodules as $key => $value){
                SystemSubMenu::updateOrCreate(
                    ['id' => $value['sub_module_id'], 'system_menu_id' => $id],
                    [
                        'name' => $value['sub_module_name'],
                        'route' => $value['route'],
                        'display_order' => $value['display_order'],
                    ]
                );
            }
        });
        return back()->with('msg_success', 'Module updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return redirect('system-setting/modules')->with('msg_error', 'Delete is prohibited');
    }
}
