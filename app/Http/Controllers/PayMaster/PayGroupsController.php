<?php

namespace App\Http\Controllers\PayMaster;

use App\Http\Controllers\Controller;
use App\Models\PayGroup;
use Illuminate\Http\Request;

class PayGroupsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:paymaster/pay-groups,view')->only('index');
        $this->middleware('permission:paymaster/pay-groups,create')->only('store');
        $this->middleware('permission:paymaster/pay-groups,edit')->only('update');
        $this->middleware('permission:paymaster/pay-groups,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $payGroups = PayGroup::filter($request)->orderBy('name')->paginate(30);
        return view('paymaster.pay-groups.index', compact('payGroups','privileges'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('paymaster.pay-groups.create');

    }

    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:150',
            'applicable_on' => 'required|integer|in:1,2',
        ]);

        $payGroup = new PayGroup();
        $payGroup->name = $request->name;
        $payGroup->applicable_on = $request->applicable_on;
        $payGroup->created_by = auth()->user()->id; 
        $payGroup->save();
        return redirect('paymaster/pay-groups')->with('msg_success', 'Pay group created successfully');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $payGroup = PayGroup::findOrFail($id);
        return view('paymaster.pay-groups.edit', compact('payGroup'));
    }

    public function update(Request $request, string $id)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:150',
            'applicable_on' => 'required|integer|in:1,2', // Assuming 1 for Employee Group, 2 for Grade
        ]);

        // Find the existing pay group by ID
        $payGroup = PayGroup::findOrFail($id);

        // Update the pay group properties with the request data
        $payGroup->name = $request->name;
        $payGroup->applicable_on = $request->applicable_on;
        $payGroup->edited_by = auth()->user()->id; // Assuming you're tracking who edited the record

        // Save the updated model instance to the database
        $payGroup->save();

        // Redirect to the pay groups listing page with a success message
        return redirect('paymaster/pay-groups')->with('msg_success', 'Pay group updated successfully');
    }

    public function destroy(string $id)
    {
        try {
            // Attempt to find and delete the pay group
            PayGroup::findOrFail($id)->delete();

            // Redirect back with a success message
            return back()->with('msg_success', 'Pay group has been deleted');
        } catch (\Exception $e) {
            // Handle the exception, typically due to foreign key constraints
            return back()->with('msg_error', 'Pay group cannot be deleted as it has been used by another module. For further information, contact the system admin.');
        }
    }





}
