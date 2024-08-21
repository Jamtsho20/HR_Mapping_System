<?php

namespace App\Http\Controllers\PayMaster;

use App\Http\Controllers\Controller;
use App\Models\MasPaySlab;
use App\Models\MasPaySlabDetails;
use App\Models\PaySlip;
use Illuminate\Http\Request;

class PaySlabsDetailsController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:paymaster/pay-slab-details,view')->only('index');
        // $this->middleware('permission:paymaster/pay-slab-details,create')->only('store');
        // $this->middleware('permission:paymaster/pay-slab-details,edit')->only('update');
        // $this->middleware('permission:paymaster/pay-slab-details,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance(); 
        $paySlabDetails = MasPaySlabDetails::filter($request)->orderBy('created_at', 'desc')->paginate(30);
        return view('paymaster.pay-slabs-details.index', compact('paySlabDetails', 'privileges'));
    }

    public function create(Request $request)
    {
        $paySlipId = $request->payslabId;
        $paySlab = MasPaySlab::whereId($paySlipId)->first(); // Fetch available pay slabs
        // $paySlabs = MasPaySlab::all(); // Fetch available pay slabs

        return view('paymaster.pay-slabs-details.create', compact('paySlab'));
    }

    
    public function store(Request $request)
{
    // Validate the request data
    $request->validate([
        'mas_pay_slab_id' => 'required|exists:mas_pay_slabs,id',
        'pay_from' => 'required|numeric',
        'pay_to' => 'required|numeric',
        'amount' => 'required|numeric',
    ]);

    // Create a new PaySlabDetails instance and save it to the database
    $paySlabDetail = new MasPaySlabDetails();
    $paySlabDetail->mas_pay_slab_id = $request->mas_pay_slab_id;
    $paySlabDetail->pay_from = $request->pay_from;
    $paySlabDetail->pay_to = $request->pay_to;
    $paySlabDetail->amount = $request->amount;
    $paySlabDetail->save();

    return redirect('paymaster/pay-slabs/'. $request->mas_pay_slab_id . '/edit')->with('msg_success', 'Pay slab detail created successfully');
    // return redirect()->back()->with('msg_success', 'Pay slab detail created successfully');
}

    public function show(string $id)
    {
        // Find the PaySlabDetail by ID and display it
        $paySlabDetail = MasPaySlabDetails::findOrFail($id);
        return view('paymaster.pay-slabs-details.show', compact('paySlabDetail'));
    }

    public function edit(string $id)
    {
        $paySlabDetail = MasPaySlabDetails::findOrFail($id);
        //new
        $paySlab = MasPaySlab::findOrFail($id);
        // Handle null dates
        $paySlabDetail->created_at = $paySlabDetail->created_at ? $paySlabDetail->created_at->format('Y-m-d') : '';
        $paySlabDetail->updated_at = $paySlabDetail->updated_at ? $paySlabDetail->updated_at->format('Y-m-d') : '';

        return view('paymaster.pay-slabs-details.edit', compact('paySlabDetail','paySlab'));
    }

    public function update(Request $request, string $id)
    {
        // Validate the incoming request data for Pay Slab Details
        $request->validate([
            'pay_from' => 'required|numeric',
            'pay_to' => 'required|numeric',
            'amount' => 'required|numeric',
        ]);

        // Find the existing Pay Slab Detail by ID and update its properties
        $paySlabDetail = MasPaySlabDetails::findOrFail($id);
        $paySlabDetail->pay_from = $request->pay_from;
        $paySlabDetail->pay_to = $request->pay_to;
        $paySlabDetail->amount = $request->amount;
        $paySlabDetail->save();

        return redirect()->back()->with('msg_success', 'Pay slab detail updated successfully');
    }

    public function destroy(string $id)
    {
        try {
            // Attempt to find and delete the PaySlabDetail
            MasPaySlabDetails::findOrFail($id)->delete();
            return back()->with('msg_success', 'Pay slab detail has been deleted');
        } catch (\Exception $e) {
            // Handle the exception, typically due to foreign key constraints
            return back()->with('msg_error', 'Pay slab detail cannot be deleted as it has been used by another module. For further information, contact the system admin.');
        }
    }
}
