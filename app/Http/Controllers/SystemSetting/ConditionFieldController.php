<?php

namespace App\Http\Controllers\SystemSetting;

use App\Http\Controllers\Controller;
use App\Models\MasApprovalHead;
use App\Models\MasConditionField;
use Illuminate\Http\Request;

class ConditionFieldController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:system-setting/condition-fields,view')->only('index');
        $this->middleware('permission:system-setting/condition-fields,create')->only('store');
        $this->middleware('permission:system-setting/condition-fields,edit')->only('update');
        $this->middleware('permission:system-setting/condition-fields,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $fields = MasConditionField::paginate(config('global.pagination'));

        return view('system-settings.condition-fields.index', compact('privileges', 'fields'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $heads = MasApprovalHead::get();
        return view('system-settings.condition-fields.create', compact('heads'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'mas_approval_head_id' => 'required',
            'name' => 'required',
            'label' => 'required',
        ]);

        $field = new MasConditionField();
        $field->mas_approval_head_id = $request->mas_approval_head_id;
        $field->name = $request->name;
        $field->label = $request->label;
        $field->has_employee_field = $request->has_employee_field;
        $field->save();

        return redirect('system-setting/condition-fields')->with('msg_success', 'Condition Fields  created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $field = MasConditionField::find($id);
        $heads=MasApprovalHead::get();
        return view('system-settings.condition-fields.edit', compact('field','heads'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $field = MasConditionField::findOrFail($id);
        $field->mas_approval_head_id = $request->mas_approval_head_id;
        $field->name = $request->name;
        $field->label = $request->label;
        $field->has_employee_field = $request->has_employee_field;
        $field->save();

        return redirect('system-setting/condition-fields')->with('msg_success', 'Condition Field updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            MasConditionField::findOrFail($id)->delete();

            return back()->with('msg_success', 'condition Field has been deleted');
        } catch (\Exception $e) {
            return back()->with('msg_error', 'condition Field cannot be delete as it has been used by other module. For further information contact system admin.');
        }
    }
}
