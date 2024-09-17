<?php

namespace App\Http\Controllers\EmployeeGroup;

use App\Http\Controllers\Controller;
use App\Models\MasEmployeeGroup;
use App\Models\MasEmployeeGroupMap;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        // $privileges = $request->instance();
        // $employees = User::all();
        // $employeeGroups = MasEmployeeGroup::filter($request)->orderBy('name')->paginate(30);

        // return view('employee-group.employee-create.index', compact('privileges', 'employeeGroups', 'employees'));
        $privileges = $request->instance();
        $employeeGroups = MasEmployeeGroup::with('employees') // Eager load employees
            ->filter($request)
            ->orderBy('name')
            ->paginate(30);

        return view('employee-group.employee-create.index', compact('privileges', 'employeeGroups'));
    }
    public function create()
    {
        $employees = User::all();

        return view('employee-group.employee-create.create', compact('employees'));
    }

    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:200',
            'description' => 'nullable|string|max:500',
            'status' => 'required|boolean',
            'employees' => 'required|array', // Validate that employees is an array
            'employees.*' => 'exists:mas_employees,id', // Validate that each employee exists in the users table
        ]);

        // Start a database transaction
        DB::beginTransaction();

        // try {
        // Create a new Employee Group instance and fill it with the validated data
        $employeeGroup = new MasEmployeeGroup();

        $employeeGroup->name = $request->name;
        $employeeGroup->description = $request->description;
        $employeeGroup->status = $request->status;


        // Save the new Employee Group instance to the database
        $employeeGroup->save();

        // Loop through the selected employees and insert each into the mas_employee_group_maps table
        foreach ($request->employees as $employeeId) {

            $mas_employee_group_maps = new MasEmployeeGroupMap();
            $mas_employee_group_maps->mas_employee_id = $employeeId;
            $mas_employee_group_maps->mas_employee_group_id = $employeeGroup->id;
            $mas_employee_group_maps->save();


            // DB::table('mas_employee_group_maps')->insert([
            //     'mas_employee_id' => $employeeId,
            //     'mas_employee_group_id' => $employeeGroup->id,
            // ]);
        }

        // Commit the transaction
        DB::commit();

        // Redirect to the index page with a success message
        return redirect('employee-group/employee-create')->with('msg_success', 'New Employee Group created successfully');
        // } catch (\Exception $e) {
        //     // Rollback the transaction in case of any error
        //     DB::rollBack();
        //     return redirect('employee-group/employee-create')->with('msg_error', 'An error occurred while creating the Employee Group. Please try again.');
        // }
    }


    public function show(string $id)
    {
        $employee = User::find($id); // Finds an employee by ID from mas_employees table

        if (!$employee) {
            return redirect()->back()->with('msg_error', 'Employee not found.');
        }

        return view('employee-group.show', compact('employee'));
    }

    public function edit(string $id)
    {
        $employeeGroup = MasEmployeeGroup::findOrFail($id);

        // Get the IDs of the employees currently associated with this group
        $selectedEmployees = MasEmployeeGroupMap::where('mas_employee_group_id', $id)
            ->pluck('mas_employee_id')
            ->toArray();

        $employees = User::all();

        return view('employee-group.employee-create.edit', compact('employeeGroup', 'employees', 'selectedEmployees'));
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
        // Track who edited the record

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
