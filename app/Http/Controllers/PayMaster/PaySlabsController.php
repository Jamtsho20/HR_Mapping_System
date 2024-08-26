<?php

namespace App\Http\Controllers\PayMaster;

use App\Http\Controllers\Controller;
use App\Models\MasPaySlab;
use App\Models\MasPaySlabDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaySlabsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:paymaster/pay-slabs,view')->only('index');
        $this->middleware('permission:paymaster/pay-slabs,create')->only('store');
        $this->middleware('permission:paymaster/pay-slabs,edit')->only('update');
        $this->middleware('permission:paymaster/pay-slabs,delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $privileges = $request->instance();
        $paySlabs = MasPaySlab::filter($request)->orderBy('name')->paginate(30);
        return view('paymaster.pay-slabs.index', compact('paySlabs', 'privileges'));
    }

    public function create()
    {
        return view('paymaster.pay-slabs.create');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:150',
            'effective_date' => 'required|date',
            'formula' => 'nullable|string',
        ]);

        // Create a new PaySlab instance and save it to the database
        DB::beginTransaction();
        try {
            $paySlab = new MasPaySlab();
            $paySlab->name = $request->name;
            $paySlab->effective_date = $request->effective_date;
            $paySlab->formula = $request->formula;
            $paySlab->created_by = auth()->user()->id;
            $paySlab->save();
            if (isset($request->details)) {
                $this->savePaySlabDetail($request->details, $paySlab->id);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('msg_error', 'The pay slab could not be created, please try again.');
        }

        return redirect('paymaster/pay-slabs')->with('msg_success', 'Pay slab created successfully');
    }

    public function show(string $id)
    {
        // Find the PaySlab by ID and display it
        $paySlab = MasPaySlab::findOrFail($id);
        return view('paymaster.pay-slabs.show', compact('paySlab'));
    }

    public function edit(string $id)
    {
        $paySlab = MasPaySlab::findOrFail($id);
        $paySlabDetails = $paySlab->paySlabDetails()->paginate(10);
        return view('paymaster.pay-slabs.edit', compact('paySlab', 'paySlabDetails'));
    }

    public function update(Request $request, string $id)
    {
        // Check if the request is for updating the Pay Slab
        if ($request->has('name')) {
            // Validate the incoming request data for Pay Slab
            $request->validate([
                'name' => 'required|string|max:150',
                'effective_date' => 'required|date',
                'formula' => 'nullable|string',
                'mas_pay_slab_details.*.pay_from' => 'required',
                'mas_pay_slab_details.*.pay_to' => 'required',
                'mas_pay_slab_details.*.amount' => 'required'
            ]);

            // Find the existing PaySlab by ID and update its properties
            $paySlab = MasPaySlab::findOrFail($id);
            $paySlab->name = $request->name;
            $paySlab->effective_date = $request->effective_date;
            $paySlab->formula = $request->formula;
            $paySlab->edited_by = auth()->user()->id;
            $paySlab->save();

            return redirect('paymaster/pay-slabs')->with('msg_success', 'Pay slab updated successfully');
        }

        // Check if the request is for updating Pay Slab Details
        if ($request->has('pay_from')) {
            // Validate the incoming request data for Pay Slab Details
            $request->validate([
                'pay_from' => 'required|numeric',
                'pay_to' => 'required|numeric',
                'amount' => 'required|numeric',
                'created_at' => 'required|date',
                'updated_at' => 'required|date',
            ]);

            // Find the existing Pay Slab Detail by ID and update its properties
            $paySlabDetail = MasPaySlabDetails::findOrFail($id);
            $paySlabDetail->pay_from = $request->pay_from;
            $paySlabDetail->pay_to = $request->pay_to;
            $paySlabDetail->amount = $request->amount;
            $paySlabDetail->created_at = $request->created_at;
            $paySlabDetail->updated_at = $request->updated_at;
            $paySlabDetail->save();

            return redirect()->back()->with('msg_success', 'Pay slab detail updated successfully.');
        }

        // Add additional conditions for other forms if necessary
    }


    public function destroy(string $id)
    {
        try {
            // Attempt to find and delete the PaySlab
            MasPaySlab::findOrFail($id)->delete();
            return back()->with('msg_success', 'Pay slab has been deleted');
        } catch (\Exception $e) {
            // Handle the exception, typically due to foreign key constraints
            return back()->with('msg_error', 'Pay slab cannot be deleted as it has been used by another module. For further information, contact the system admin.');
        }
    }

    private function savePaySlabDetail($details, $paySlabId)
    {
        $paySlab = new MasPaySlab();
        $paySlab->id = $paySlabId;
        // dd($paySlab->id);
        $paySlabDetails = [];
        foreach ($details as $key => $value) {
            $paySlabDetails[] = [
                'mas_pay_slab_id' => $paySlab->id,
                'pay_from' => $value['pay_from'],
                'pay_to' => $value['pay_to'],
                'amount' => $value['amount'],
            ];
        }
        // dd($paySlabDetails);
        $paySlab->paySlabDetails()->createMany($paySlabDetails);
    }
}
