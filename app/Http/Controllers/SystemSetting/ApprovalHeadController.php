<?php

namespace App\Http\Controllers\SystemSetting;

use App\Http\Controllers\Controller;
use App\Models\MasApprovalHead;
use App\Models\MasVehicle;
use Illuminate\Http\Request;

class ApprovalHeadController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:system-setting/approval-head,view')->only('index');
        $this->middleware('permission:system-setting/approval-head,create')->only('store');
        $this->middleware('permission:system-setting/approval-head,edit')->only('update');
        $this->middleware('permission:system-setting/approval-head,delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $privileges = $request->instance();
        return view('system-settings.approval-head.index', compact('privileges'));
    }

    public function create()
    {
        return view('system-settings.approval-head.create');
    }

    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        // Create a new MasVehicle instance and fill it with the validated data
        $approval = new MasApprovalHead();
        $approval->name = $validatedData['name'];
        $approval->name = $validatedData['description'];

        // Save the MasVehicle instance to the database
        $approval->save();

        // Redirect with success message
        return redirect()->route('approval-head.index')->with('success', 'Approval Head created successfully.');
    }

    public function edit($id)
    {
        $approval = MasApprovalHead::findOrFail($id);

        return view('system-setting.approval-head.edit', compact('approval'));
    }

    public function update(Request $request, string $id)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        $approval = MasApprovalHead::findOrFail($id);

        $approval->name = $validatedData['name'];
        $approval->description = $validatedData['description'];

        // Save the updated vehicle to the database
        $approval->save();

        // Redirect with success message
        return redirect()->route('approval-head.index')->with('success', 'Approval Head updated successfully.');
    }


    public function destroy(string $id)
    {
        try {
            MasApprovalHead::findOrFail($id)->delete();
            return back()->with('msg_success', 'Approval Head has been deleted');
        } catch (\Exception $e) {
            return back()->with('msg_error', 'Approval Head cannot be deleted as it has been used by other modules. For further information, contact the system admin.');
        }
    }
}
