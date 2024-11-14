<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\BudgetCode;
use App\Models\BudgetTypes;
use Illuminate\Http\Request;

class BudgetCodeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:master/budget-code,view')->only('index');
        $this->middleware('permission:master/budget-code,create')->only('store');
        $this->middleware('permission:master/budget-code,edit')->only('update');
        $this->middleware('permission:master/budget-code,delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $privileges = $request->instance();
        $budgetCodes = BudgetCode::paginate(10);

        return view('masters.budget-code.index', compact('privileges','budgetCodes'));
    }

    public function create()
    {
        $budgetTypes = BudgetTypes::all(['id', 'name']);

        return view('masters.budget-code.create', compact('budgetTypes'));
    }
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'code' => 'required|string|max:255|unique:budget_codes,code',
            'particular' => 'required|string|max:255',
            'budget_type' => 'required|exists:budget_types,id',
        ]);

        // Create a new BudgetCode instance and populate it with validated data
        $budgetCode = new BudgetCode();
        $budgetCode->code = $validatedData['code'];
        $budgetCode->particular = $validatedData['particular'];
        $budgetCode->budget_type_id = $validatedData['budget_type'];

        // Save the BudgetCode instance to the database
        $budgetCode->save();

        // Redirect with a success message
        return redirect()->route('budget-code.index')->with('success', 'Budget Code created successfully.');
    }

    public function edit($id)
    {
        $budgetTypes = BudgetTypes::all(['id', 'name']);
        $budgetCode = BudgetCode::findOrFail($id);

        return view('masters.budget-code.edit', compact('budgetTypes','budgetCode'));
    }
    public function update(Request $request, $id)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'code' => 'required|string|max:255', 
            'particular' => 'required|string|max:255', 
            'budget_type' => 'required|exists:budget_types,id',
        ]);

        $budgetCode = BudgetCode::findOrFail($id);

        $budgetCode->code = $validatedData['code'];
        $budgetCode->particular = $validatedData['particular'];
        $budgetCode->budget_type_id = $validatedData['budget_type']; 
        $budgetCode->updated_by = auth()->user()->id; 

        $budgetCode->save();

        return redirect()->route('budget-code.index')->with('success', 'Budget Code updated successfully.');
    }
    public function destroy(string $id)
    {
        try {
            BudgetCode::findOrFail($id)->delete();
            return back()->with('msg_success', 'Budget Code has been deleted');
        } catch (\Exception $e) {
            return back()->with('msg_error', 'Budget Code cannot be deleted as it has been used by other modules. For further information, contact the system admin.');
        }
    }
}
