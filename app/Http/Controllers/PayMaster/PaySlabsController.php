<?php

namespace App\Http\Controllers\PayMaster;

use App\Http\Controllers\Controller;
use App\Models\MasPaySlab;
use Illuminate\Http\Request;

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
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:150',
            'effective_date' => 'required|date',
            'formula' => 'nullable|string',
        ]);

        // Create a new PaySlab instance and save it to the database
        $paySlab = new MasPaySlab();
        $paySlab->name = $request->name;
        $paySlab->effective_date = $request->effective_date;
        $paySlab->formula = $request->formula;
        $paySlab->created_by = auth()->user()->id;
        $paySlab->save();

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
        // Find the PaySlab by ID for editing
        $paySlab = MasPaySlab::findOrFail($id);
        return view('paymaster.pay-slabs.edit', compact('paySlab'));
    }

    public function update(Request $request, string $id)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:150',
            'effective_date' => 'required|date',
            'formula' => 'nullable|string',
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

    
}
