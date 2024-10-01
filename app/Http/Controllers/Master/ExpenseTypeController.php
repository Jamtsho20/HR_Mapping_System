<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\MasExpenseType;
use Illuminate\Http\Request;

class ExpenseTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('permission:master/expense-types,view')->only('index');
        $this->middleware('permission:master/expense-types,create')->only('store');
        $this->middleware('permission:master/expense-types,edit')->only('update');
        $this->middleware('permission:master/expense-types,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $expenses = MasExpenseType::with('parent')->filter($request)->orderBy('name')->paginate(30);


        return view('masters.expense-types.index', compact('expenses', 'privileges'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $parentExpenseTypes = MasExpenseType::whereNull('mas_expense_type_id')->get(); // Fetch all top-level expense types

        return view('masters.expense-types.create', compact('parentExpenseTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */



    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'mas_expense_type_id' => 'nullable|exists:mas_expense_types,id',
            'expense_names' => 'required|array|min:1',
            'expense_names.*' => 'required|string|max:255', // Validate each expense name
        ]);



        // Create the expense types
        foreach ($request->expense_names as $name) {

            // Create a new expense type first
            $expenseType = MasExpenseType::create([
                'mas_expense_type_id' => $request->mas_expense_type_id ?? null, // Use the selected parent ID or null
                'name' => $name,

            ]);
        }
    

        // Redirect back with a success message
        return redirect()->route('expense-types.index')->with('success', 'Expense types created successfully.');
    }






    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $expense = MasExpenseType::findOrFail($id);
        $parentExpenseTypes = MasExpenseType::whereNull('mas_expense_type_id')->get(); // Fetch all top-level expense types
        return view('masters.expense-types.edit', compact('expense', 'parentExpenseTypes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // $request->validate([
        //     'expense_type' => 'required',
        // ]);

        $expense = MasExpenseType::findOrFail($id);
        $expense->mas_expense_type_id = $request->mas_expense_type_id;    
        $expense->name = $request->expense_names;
        $expense->save();

        return redirect('master/expense-types')->with('msg_success', 'Expense Type updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            MasExpenseType::findOrFail($id)->delete();

            return back()->with('msg_success', 'Expense Type has been deleted');
        } catch (\Exception $e) {
            return back()->with('msg_error', 'Expense Type cannot be delete as it has been used by other module. For further information contact system admin.');
        }
    }
}
