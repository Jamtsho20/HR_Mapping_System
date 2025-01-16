<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\MasLoanType;
use Illuminate\Http\Request;

class MasLoanTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:master/loan-types,view')->only('index');
        $this->middleware('permission:master/loan-types,create')->only('store');
        $this->middleware('permission:master/loan-types,edit')->only('update');
        $this->middleware('permission:master/loan-types,delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $privileges = $request->instance();
        $loanTypes = MasLoanType::paginate(20);

        return view('masters.loan-types.index', compact('privileges','loanTypes'));
    }

    public function create()
    {
        return view('masters.loan-types.create');
    }

    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'code' => '',
        ]);

        $loanType = new MasLoanType();
        $loanType->name = $validatedData['name'];
        $loanType->code = $validatedData['code'];

        // Save the Office instance to the database
        $loanType->save();

        // Redirect with success message
        return redirect()->route('loan-types.index')->with('success', 'Loan Types created successfully.');
    }

    public function edit($id)
    {
        $loanType = MasLoanType::findOrFail($id);
        return view('masters.loan-types.edit', compact('loanType'));
    }

    public function update(Request $request, string $id)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => '',
        ]);

        // Find the office by ID or throw a 404 error
        $loanType = MasLoanType::findOrFail($id);

        // Update the office details
        $loanType->name = $request->input('name');
        $loanType->code = $request->input('code');


        // Save the updated loanType
        $loanType->save();

        // Redirect with a success message
        return redirect('master/loan-types')->with('msg_success', 'Loan Type updated successfully');
    }
    public function destroy(string $id)
    {
        try {
            MasLoanType::findOrFail($id)->delete();
            return back()->with('msg_success', 'Region has been deleted');
        } catch (\Exception $e) {
            return back()->with('msg_error', 'Region cannot be deleted as it has been used by other modules. For further information, contact the system admin.');
        }
    }
}
