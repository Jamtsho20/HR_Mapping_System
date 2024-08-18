<?php

namespace App\Http\Controllers\PayMaster;

use App\Http\Controllers\Controller;
use App\Models\MasPayHead;
use App\Models\MasPayGroup;
use App\Models\MasAccAccountHead;
use App\Models\MasPaySlab;
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
        $payHeads = MasPayHead::with('accountHead') // Ensure that you have defined the relationship in your model
            ->filter($request)
            ->orderBy('name')
            ->paginate(30);
    
        return view('paymaster.pay-heads.index', compact('payHeads', 'privileges'));
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $accountHeads = MasAccAccountHead::all();
        $paySlabs = MasPaySlab::all();
        $payGroups = MasPayGroup::all(); 

        // Pass both data sets to the view
        return view('paymaster.pay-heads.create', compact('accountHeads', 'paySlabs', 'payGroups'));

    }
    public function store(Request $request)
    {
        dd($request->all());
        $validatedData = $request->validate([
            'payhead_type' => 'required|integer',
            'name' => 'required|string|max:255',
            'accounthead_type' => 'required|integer',
            'code' => 'required|string|max:255',
            'calculation_method' => 'required|integer',
            'calculated_on' => 'required_if:calculation_method,3,4,5',
            'amount' => 'required_if:calculation_method,1,2,5|numeric',
            'payslab_id' => 'required_if:calculation_method,3|integer',
            'paygroup_id' => 'required_if:calculation_method,4|integer',
            'formula' => 'required_if:calculation_method,6|string',
        ]);
    
        // If validation passes, handle the data
        $payHead = new MasPayHead();
        $payHead->payhead_type = $request->payhead_type;
        $payHead->name = $request->name;
        $payHead->accounthead_type = $request->accounthead_type;
        $payHead->code = $request->code;
        $payHead->calculation_method = $request->calculation_method;
        $payHead->calculated_on = $request->calculated_on;
        $payHead->amount = $request->amount;
        $payHead->payslab_id = $request->payslab_id;
        $payHead->paygroup_id = $request->paygroup_id;
        $payHead->formula = $request->formula;
        $payHead->save();
    
        return redirect()->route('pay-heads.index')->with('success', 'Pay Head created successfully.');
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
