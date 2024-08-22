<?php

namespace App\Http\Controllers\EmployeeGroup;

use App\Http\Controllers\Controller;
use App\Models\MasEmployeeGroup;
use Illuminate\Http\Request;

class EmployeeGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:employee-group/employee-create,view')->only('index');
        $this->middleware('permission:employee-group/employee-create,create')->only('store');
        $this->middleware('permission:employee-group/employee-create,edit')->only('update');
        $this->middleware('permission:employee-group/employee-create,delete')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $employeeGroups = MasEmployeeGroup::filter($request)->orderBy('name')->paginate(30);

        return view('employee-group.employee-create.index', compact('privileges','employeeGroups'));
    }
    public function create()
    {
        return view('employee-group.employee-create.create');
    }

    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:200',
            'description' => 'nullable|string|max:500',
            'status' => 'required|boolean',
        ]);

        // Create a new New Employee Group instance and fill it with the validated data
        $employeeGroup = new MasEmployeeGroup();
        $employeeGroup->name = $request->name;
        $employeeGroup->description = $request->description;
        $employeeGroup->status = $request->status;
        $employeeGroup->created_by = auth()->user()->id;
        $employeeGroup->updated_by = auth()->user()->id;

        // Save the new Employee Group instance to the database
        $employeeGroup->save();

        // Redirect to the index page with a success message
        return redirect('employee-group/employee-create')->with('msg_success', 'New Employee Group created successfully');
    }
    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $employeeGroup = MasEmployeeGroup::findOrFail($id);
        
        return view('employee-group.employee-create.edit', compact('employeeGroup'));
    }

    public function update(Request $request, string $id)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:200',
            'description' => 'nullable|string|max:500',
            'status' => 'required|boolean',
        ]);

        // Find the existing sub-store by ID
        $employeeGroup = MasEmployeeGroup::findOrFail($id);


        $employeeGroup->name = $request->name;
        $employeeGroup->description = $request->description;
        $employeeGroup->status = $request->status;
        $employeeGroup->updated_by = auth()->user()->id; // Track who edited the record

        // Save the updated model instance to the database
        $employeeGroup->save();


        return redirect('employee-group/employee-create')->with('msg_success', 'Employee Group updated successfully');
    }
    public function destroy(string $id)
    {
        try {
            // Attempt to find and delete the sub-store
            $employeeGroup = MasEmployeeGroup::findOrFail($id);
            $employeeGroup->delete();

            // Redirect back with a success message
            return back()->with('msg_success', 'Employee Group has been deleted');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Handle the case where the record was not found
            return back()->with('msg_error', 'Employee Group not found.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle foreign key constraint errors or other database exceptions
            return back()->with('msg_error', 'Employee Group cannot be deleted as it has been used by another module. For further information, contact the system admin.');
        } catch (\Exception $e) {
            // Handle any other exceptions
            return back()->with('msg_error', 'An error occurred while attempting to delete the Employee Group.');
        }
    }
}
