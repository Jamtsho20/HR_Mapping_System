<?php

namespace App\Http\Controllers\PayMaster;

use App\Http\Controllers\Controller;
use App\Models\MasPayHead;
use Illuminate\Http\Request;

class PayHeadsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:paymaster/pay-heads,view')->only('index');
        $this->middleware('permission:paymaster/pay-heads,create')->only('store');
        $this->middleware('permission:paymaster/pay-heads,edit')->only('update');
        $this->middleware('permission:paymaster/pay-heads,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $payHeads = MasPayHead::filter($request)->orderBy('name')->paginate(30);
        return view('paymaster.pay-heads.index', compact('payHeads', 'privileges'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('paymaster.pay-heads.create');

    }
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:150',
            'code' => 'required|string|max:50',
            'payhead_type' => 'required|integer|in:1,2', // Assuming 1 for Allowance, 2 for Deduction
            'accounthead_type' => 'required|integer|in:1,2', // Assuming 1 for Allowance, 2 for Deduction
            'calculation_method' => 'required|integer|in:1,2,3,4,5,6,7', // Depending on your methods
            'calculated_on' => 'required|integer|in:1,2,3,4,5,6,7', // Depending on your criteria
            'formula' => 'nullable|string',
        ]);

        // Create a new PayHead instance and assign data
        $payHead = new MasPayHead();
        $payHead->name = $request->name;
        $payHead->code = $request->code;
        $payHead->payhead_type = $request->payhead_type;
        $payHead->accounthead_type = $request->payhead_type;
        $payHead->calculation_method = $request->calculation_method;
        $payHead->calculated_on = $request->calculated_on;
        $payHead->formula = $request->formula;
        $payHead->created_by = auth()->user()->id;
        $payHead->save();

        return redirect('paymaster/pay-heads')->with('msg_success', 'Pay head created successfully');
    }
    public function show(string $id)
    {
        // Show specific PayHead details if needed
        // $payHead = PayHead::findOrFail($id);
        // return view('paymaster.pay-heads.show', compact('payHead'));
    }

    public function edit(string $id)
    {
        $payHead = MasPayHead::findOrFail($id);
        return view('paymaster.pay-heads.edit', compact('payHead'));
    }

    public function update(Request $request, string $id)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:150',
            'code' => 'required|string|max:50',
            'payhead_type' => 'required|integer|in:1,2',
            'accounthead_type' => 'required|integer|in:1,2',
            'calculation_method' => 'required|integer|in:1,2,3,4,5,6,7',
            'calculated_on' => 'required|integer|in:1,2,3,4,5,6,7',
            'formula' => 'nullable|string',
        ]);

        // Find the existing PayHead by ID
        $payHead = MasPayHead::findOrFail($id);

        // Update the PayHead properties with the request data
        $payHead->name = $request->name;
        $payHead->code = $request->code;
        $payHead->payhead_type = $request->payhead_type;
        $payHead->accounthead_type = $request->payhead_type;
        $payHead->calculation_method = $request->calculation_method;
        $payHead->calculated_on = $request->calculated_on;
        $payHead->formula = $request->formula;
        $payHead->edited_by = auth()->user()->id;

        // Save the updated model instance to the database
        $payHead->save();

        // Redirect to the pay heads listing page with a success message
        return redirect('paymaster/pay-heads')->with('msg_success', 'Pay head updated successfully');
    }

    public function destroy(string $id)
    {
        try {
            // Attempt to find and delete the pay head
            MasPayHead::findOrFail($id)->delete();

            // Redirect back with a success message
            return back()->with('msg_success', 'Pay head has been deleted');
        } catch (\Exception $e) {
            // Handle the exception, typically due to foreign key constraints
            return back()->with('msg_error', 'Pay head cannot be deleted as it has been used by another module. For further information, contact the system admin.');
        }
    }


}
