<?php

namespace App\Http\Controllers\PayMaster;

use App\Http\Controllers\Controller;
use App\Models\AccAccountHead;
use Illuminate\Http\Request;

class AccountHeadsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:paymaster/account-heads,view')->only('index');
        $this->middleware('permission:paymaster/account-heads,create')->only('store');
        $this->middleware('permission:paymaster/account-heads,edit')->only('update');
        $this->middleware('permission:paymaster/account-heads,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $accountHeads = AccAccountHead::filter($request)->orderBy('name')->paginate(30);

        return view('paymaster.account-heads.index', compact('accountHeads', 'privileges'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('paymaster.account-heads.create');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'code' => 'required|string|max:30|unique:acc_account_heads,code',
            'name' => 'required|string|max:100',
            'type' => 'required|integer|in:1,2', // Assuming 1 for Credit, 2 for Debit
        ]);

        // Create a new AccAccountHead instance and assign properties
        $accountHead = new AccAccountHead();
        $accountHead->code = $request->code;
        $accountHead->name = $request->name;
        $accountHead->type = $request->type;
        $accountHead->created_by = auth()->user()->id; // Assuming you're storing the ID of the user who created it
        $accountHead->save();

        // Redirect with a success message
        return redirect('paymaster/account-heads')->with('msg_success', 'Account head created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $accountHead = AccAccountHead::findOrFail($id);
        return view('paymaster.account-heads.edit', compact('accountHead'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate the incoming request data
        $request->validate([
            'code' => 'required|string|max:30|unique:acc_account_heads,code,' . $id,
            'name' => 'required|string|max:100',
            'type' => 'required|integer|in:1,2', // Assuming 1 for Credit, 2 for Debit
        ]);

        // Find the existing account head by ID
        $accountHead = AccAccountHead::findOrFail($id);

        // Update the account head properties with the request data
        $accountHead->code = $request->code;
        $accountHead->name = $request->name;
        $accountHead->type = $request->type;
        $accountHead->edited_by = auth()->user()->id; // Assuming you're tracking who edited the record

        // Save the updated model instance to the database
        $accountHead->save();

        // Redirect to the account heads listing page with a success message
        return redirect('paymaster/account-heads')->with('msg_success', 'Account head updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            // Attempt to find and delete the account head
            AccAccountHead::findOrFail($id)->delete();

            // Redirect back with a success message
            return back()->with('msg_success', 'Account head has been deleted');
        } catch (\Exception $e) {
            // Handle the exception, typically due to foreign key constraints
            return back()->with('msg_error', 'Account head cannot be deleted as it has been used by another module. For further information, contact the system admin.');
        }
    }
}
