<?php

namespace App\Http\Controllers\PayMaster;

use App\Models\User;
use App\Models\MasPayHead;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ExecutiveFixedAllowance;

class ExecutiveFixedAllowanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:paymaster/executive-fixed-allowances,view')->only('index');
        $this->middleware('permission:paymaster/executive-fixed-allowances,create')->only('store');
        $this->middleware('permission:paymaster/executive-fixed-allowances,edit')->only('update');
        $this->middleware('permission:paymaster/executive-fixed-allowances,delete')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $records = ExecutiveFixedAllowance::filter($request)->orderBy('employee_id')->paginate(config('global.pagination'));
        $employees = User::select('id', 'name', 'username', 'title')->get();
        $allowances = MasPayHead::whereIn('id', [1, 5])->pluck('name', 'id');

        return view('paymaster.executive-fixed-allowances.index', compact('records', 'privileges', 'employees', 'allowances'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'employee_id' => 'required',
                'pay_head_id' => 'required',
                'amount' => 'required'
            ]);

            $record = new ExecutiveFixedAllowance();
            $record->employee_id = $request->employee_id;
            $record->pay_head_id = $request->pay_head_id;
            $record->amount = $request->amount;
            $record->save();

            return redirect()->route('executive-fixed-allowances.index')->with(['msg_success' => 'The record has been successfully added.']);

        } catch(\Exception $e) {
            return redirect()->back()->with('msg_error', 'Something went wrong while adding the record.');
        }
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $record = ExecutiveFixedAllowance::findOrFail($id);

            $record->amount = $request->amount;
            $record->save();

            return redirect()->route('executive-fixed-allowances.index')->with(['msg_success' => 'The record has been successfully updated.']);
        } catch(\Exception $e) {
            return redirect()->back()->with('msg_error', 'Something went wrong while updating the record.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $deleted = ExecutiveFixedAllowance::whereId($id)->delete();
            if ($deleted) {
                return redirect()->route('executive-fixed-allowances.index')->with(['msg_success' => 'The record has been successfully deleted.']);
            } else {
                return redirect()->route('executive-fixed-allowances.index')->with(['msg_error' => 'No record found to delete.']);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('msg_error', 'Something went wrong while deleting the record.');
        }        
    }
}
