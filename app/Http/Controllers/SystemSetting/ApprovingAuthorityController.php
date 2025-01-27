<?php

namespace App\Http\Controllers\SystemSetting;

use App\Http\Controllers\Controller;
use App\Models\ApprovingAuthority;
use App\Models\Role;
use Illuminate\Http\Request;

class ApprovingAuthorityController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:system-setting/approving-authorities,view')->only('index');
        $this->middleware('permission:system-setting/approving-authorities,create')->only('store');
        $this->middleware('permission:system-setting/approving-authorities,edit')->only('update');
        $this->middleware('permission:system-setting/approving-authorities,delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $approvingAuthorities = ApprovingAuthority::with('role')->paginate(30);
        $privileges = $request->instance();
        return view('system-settings.approving-authorities.index', compact('privileges', 'approvingAuthorities'));
    }


    public function create()
    {
        $roles = Role::all();
        return view('system-settings.approving-authorities.create', compact('roles'));
    }

    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'role_id' => 'nullable|exists:roles,id',
            'description' => 'required|string|max:255',
            'has_employee_field' => 'boolean',
            'status.is_active' => 'boolean',
        ]);

        // Create a new Approving Authority instance
        $approvingAuthority = new ApprovingAuthority();
        $approvingAuthority->name = $validatedData['name'];
        $approvingAuthority->role_id = $validatedData['role_id'];
        $approvingAuthority->description = $validatedData['description'];
        $approvingAuthority->has_employee_field = $request->input('has_employee_field', 0); 
        $approvingAuthority->status = $request->input('status.is_active', 0);

        // Save the Approving Authority instance to the database
        $approvingAuthority->save();

        // Redirect with success message
        return redirect()->route('approving-authorities.index')->with('success', 'Approving Authority created successfully.');
    }
    
    public function edit($id)
    {
        $approvingAuthority = ApprovingAuthority::findOrFail($id);
        $roles = Role::all();

        return view('system-settings.approving-authorities.edit', compact('approvingAuthority', 'roles'));
    }

    public function update(Request $request, string $id)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'role_id' => 'nullable|exists:roles,id',
            'description' => 'required|string|max:255',
            'has_employee_field' => 'sometimes|boolean',
            'status.is_active' => 'sometimes|boolean',
        ]);

        // Find the Approving Authority by ID or throw a 404 error
        $approvingAuthority = ApprovingAuthority::findOrFail($id);

        // Update the Approving Authority details
        $approvingAuthority->name = $request->input('name');
        $approvingAuthority->role_id = $request->input('role_id');
        $approvingAuthority->description = $request->input('description');
        $approvingAuthority->has_employee_field = $request->input('has_employee_field', 0); 
        $approvingAuthority->status = $request->input('status.is_active', 0);
       

        // Save the updated Approving Authority
        $approvingAuthority->save();

        // Redirect with a success message
        return redirect()->route('approving-authorities.index')->with('msg_success', 'Approving Authority updated successfully.');
    }

    public function destroy(string $id)
    {
        try {
            ApprovingAuthority::findOrFail($id)->delete();
            return back()->with('msg_success', 'Approving Authority has been deleted');
        } catch (\Exception $e) {
            return back()->with('msg_error', 'Approving Authority cannot be deleted as it has been used by other modules. For further information, contact the system admin.');
        }
    }
}
