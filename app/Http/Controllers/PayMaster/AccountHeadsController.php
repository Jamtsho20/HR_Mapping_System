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
      
        return view('paymaster.account-heads.index',compact('privileges'));
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
        $request->validate([
            'code' => 'required|string|max:30',
            'name' => 'required|string|max:100',
            'type' => 'required|integer|between:1,255', 
        ]);

        $accountHead = new AccAccountHead;
        $accountHead->code = $request->input('code');
        $accountHead->name = $request->input('name');
        $accountHead->type = $request->input('type');
        $accountHead->created_by = auth()->user()->id; // Assuming the authenticated user's ID
        $accountHead->save();

        return redirect()->route('paymaster.account-heads.index')
            ->with('msg_success', 'Account head created successfully.');
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
    public function update(Request $request, string $id)
    {
        $request->validate([
            'code' => 'required|string|max:30',
            'name' => 'required|string|max:100',
            'type' => 'required|integer|between:1,255', // Adjust as per the type definition
        ]);
    
        $accountHead = AccAccountHead::findOrFail($id);
        $accountHead->code = $request->input('code');
        $accountHead->name = $request->input('name');
        $accountHead->type = $request->input('type');
        $accountHead->edited_by = auth()->user()->id; // Track who made the edit
    
        $accountHead->save();
    
        return redirect()->route('paymaster.account-heads.index')->with('msg_success', 'Account head updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $accountHead = AccAccountHead::findOrFail($id);
            $accountHead->delete();

            return back()->with('msg_success', 'Account head has been deleted successfully');
        } catch (\Exception $e) {
            return back()->with('msg_error', 'Account head cannot be deleted as it is referenced by other records. Please contact the system administrator for more information.');
        }
    }
}
