<?php

namespace App\Http\Controllers\Payroll;

use App\Models\User;
use App\Models\MasPayHead;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EmployeeSalarySaving;

class SSSController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:payroll/salary-saving-schemes,view')->only('index');
        $this->middleware('permission:payroll/salary-saving-schemes,create')->only('store');
        $this->middleware('permission:payroll/salary-saving-schemes,edit')->only('update');
        $this->middleware('permission:payroll/salary-saving-schemes,delete')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $salarySavings = EmployeeSalarySaving::filter($request)->orderBy('id')->paginate(config('global.pagination'));
        $employees = User::filter($request)->select(['id', 'name', 'employee_id', 'username', 'title'])->get();

       return view('payroll.salary-saving-schemes.index', compact('privileges', 'salarySavings', 'employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $payHeads = MasPayHead::whereCalculationMethod(7)->wherePayheadType(2)->whereId(11)->get(); // only loans
        $employees = User::select(['id', 'name', 'employee_id', 'username', 'title'])->get();

        return view('payroll.salary-saving-schemes.create', compact('payHeads', 'employees')); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'pay_head_id' => ['required'],
                'employee_id' => ['required'],
                'policy_number' => ['required'],
                'amount' => ['required', 'numeric', 'min:0'],
            ]);  
            
            $salarySaving = new EmployeeSalarySaving();
            $salarySaving->pay_head_id = $request->pay_head_id;
            $salarySaving->employee_id = $request->employee_id;
            $salarySaving->policy_number = $request->policy_number;
            $salarySaving->amount = $request->amount;
            $salarySaving->save();

            return redirect()->back()->with(['msg_success' => 'SSS has been successfully added.']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['msg_error' => 'Something went wrong while adding SSS.']);
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
        $salarySaving = EmployeeSalarySaving::findOrFail($id);
        $payHeads = MasPayHead::whereCalculationMethod(7)->wherePayheadType(2)->whereId(11)->get(); // only loans
        $employees = User::select(['id', 'name', 'employee_id', 'username', 'title'])->get();

        return view('payroll.salary-saving-schemes.edit', compact('salarySaving', 'payHeads', 'employees')); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'pay_head_id' => ['required'],
                'employee_id' => ['required'],
                'policy_number' => ['required'],
                'amount' => ['required', 'numeric', 'min:0'],
            ]);

            $salarySaving = EmployeeSalarySaving::findOrFail($id);

            $salarySaving->pay_head_id = $request->pay_head_id;
            $salarySaving->employee_id = $request->employee_id;
            $salarySaving->policy_number = $request->policy_number;
            $salarySaving->amount = $request->amount;
            $salarySaving->save();

            return redirect()->back()->with(['msg_success' => 'SSS has been successfully updated.']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['msg_error' => 'Something went wrong while updating SSS.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $salarySaving = EmployeeSalarySaving::findOrFail($id);
            $salarySaving->delete();

            return redirect()->back()->with(['msg_success' => 'SSS has been successfully deleted.']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['msg_error' => 'Something went wrong while deleting SSS.']);
        }
    }
}
