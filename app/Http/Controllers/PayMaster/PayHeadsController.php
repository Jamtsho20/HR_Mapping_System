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
            ->paginate(10);

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
        $validatedData = $request->validate([
            'payhead_type' => 'required',
            'account_head_id' => 'required',
            'name' => 'required|max:150',
            'code' => 'required|max:50',
            'calculation_method' => 'required',
            'calculated_on' => 'nullable',
            'pay_slab_id' => 'nullable',
            'pay_group_id' => 'nullable',
            'amount' => 'nullable|numeric',
            'formula' => 'nullable|string',
        ]);

        MasPayHead::create($validatedData);

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
        $accountHeads = MasAccAccountHead::all();
        $paySlabs = MasPaySlab::all();
        $payGroups = MasPayGroup::all();
        return view('paymaster.pay-heads.edit', compact('payHead','payGroups','accountHeads','paySlabs'));
    }

    public function update(Request $request, $id)
    {
        $payHead = MasPayHead::findOrFail($id);

        $validatedData = $request->validate([
            'payhead_type' => 'required',
            'account_head_id' => 'required',
            'name' => 'required|max:150',
            'code' => 'required|max:50',
            'calculation_method' => 'required',
            'calculated_on' => 'nullable',
            'pay_slab_id' => 'nullable',
            'pay_group_id' => 'nullable',
            'amount' => 'nullable|numeric',
            'formula' => 'nullable|string',
        ]);

        $payHead->update($validatedData);

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
