<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\MasExpenseType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $expenses = MasExpenseType::with('children')->filter($request)->where('mas_expense_type_id', null)->orderBy('name')->paginate(30);


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

        return view('masters.expense-types.edit', compact('expense'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, $id)
    // {


    //     DB::transaction(function () use ($request, $id) {
    //         // Find the expense by ID or fail
    //         $expense = MasExpenseType::findOrFail($id);

    //         // Update the expense name
    //         $expense->name = $request->name;
    //         $expense->save();

    //         // Loop through the children from the request
    //         foreach ($request->children as $key => $value) {

    //             // Update or create each child (expense type)
    //             $expense->children()->updateOrCreate(
    //                 ['id' => $value['id'] ], // Use null for new records
    //                 [
    //                     'name' => $value['name']
    //                 ]
    //             );
    //         }

    //         // Optionally handle deletions if any children (expense types) were removed
    //         // For example:
    //         $existingChildIds = $expense->children->pluck('id')->toArray();
    //         $submittedChildIds = array_column($request->children, 'id');
    //         $childrenToDelete = array_diff($existingChildIds, $submittedChildIds);
    //         MasExpenseType::whereIn('id', $childrenToDelete)->delete();
    //     });

    //     return redirect('master/expense-types')->with('msg_success', 'Expense Type updated successfully');
    // }
    public function update(Request $request, $id)
    {
   

        DB::transaction(function () use ($request, $id) {
            // Find the expense type by ID
            $expense = MasExpenseType::findOrFail($id);
            $expense->name = $request->name;
            $expense->save();

            // Get all existing children IDs from the database
            $existingChildrenIds = $expense->children()->pluck('id')->toArray();

            // Keep track of current children IDs that should remain after update
            $currentChildrenIds = [];

            // Loop through the submitted children data and update or create new records
            foreach ($request->children as $key => $value) {
                // If there's an ID, update the existing record; otherwise, create a new one
                $child = $expense->children()->updateOrCreate(
                    ['id' => $value['id'] ?? null], // If no ID, create a new record
                    ['name' => $value['name']]
                );

                // Add to the array of current children IDs
                $currentChildrenIds[] = $child->id;
            }

            // Find the children that were not included in the current request and delete them
            $childrenToDelete = array_diff($existingChildrenIds, $currentChildrenIds);
            if (!empty($childrenToDelete)) {
                $expense->children()->whereIn('id', $childrenToDelete)->delete();
            }
        });

        return redirect('master/expense-types')->with('msg_success', 'Expense Type and Subtypes updated successfully.');
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
