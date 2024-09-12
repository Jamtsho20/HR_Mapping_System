<?php

namespace App\Http\Controllers\PayMaster;

use App\Http\Controllers\Controller;
use App\Models\MasEmployeeGroup;
use App\Models\MasGrade;
use App\Models\MasPayGroup;
use App\Models\MasPayGroupDetail;
use Illuminate\Http\Request;

class PayGroupDetailsController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:paymaster/pay-slab-details,view')->only('index');
        // $this->middleware('permission:paymaster/pay-slab-details,create')->only('store');
        // $this->middleware('permission:paymaster/pay-slab-details,edit')->only('update');
        // $this->middleware('permission:paymaster/pay-slab-details,delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $privileges = $request->instance();
        
        $payGroupDetails = MasPayGroupDetail::with('employeeGroup')
            ->filter($request)
            ->orderBy('created_at', 'desc')
            ->paginate(30);

        return view('paymaster.pay-group-details.index', compact('payGroupDetails', 'privileges'));
    }

    public function create(Request $request)
    {
        $payGroupId = $request->payGroupId;
        $payGroup = MasPayGroup::whereId($payGroupId)->first();
        $grades = MasGrade::pluck('name', 'id');
        return view('paymaster.pay-group-details.create', compact('payGroup', 'grades'));
    }
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'mas_pay_group_id' => 'required|exists:mas_pay_groups,id',
            'mas_grade_id' => 'required|exists:mas_grades,id',
            'calculation_method' => 'required|integer',
            'amount' => 'required|numeric',
        ]);

        // Create a new MasPayGroupDetails instance and save it to the database
        $payGroupDetail = new MasPayGroupDetail();
        $payGroupDetail->mas_pay_group_id = $request->mas_pay_group_id;
        $payGroupDetail->mas_grade_id = $request->mas_grade_id;
        $payGroupDetail->calculation_method = $request->calculation_method;
        $payGroupDetail->amount = $request->amount;
        $payGroupDetail->created_by = auth()->user()->id;
        $payGroupDetail->save();

        return redirect()->back()->with('msg_success', 'Pay group detail created successfully');
    }
    public function show(string $id)
    {
        // Find the MasPayGroupDetail by ID and display it
        $payGroupDetail = MasPayGroupDetail::findOrFail($id);
        return view('paymaster.pay-group-details.show', compact('payGroupDetail'));
    }

    public function edit(string $id)
    {
        // Find the PayGroupDetail and associated PayGroup by ID
        $payGroupDetail = MasPayGroupDetail::findOrFail($id);
        $payGroup = MasPayGroup::findOrFail($id);
        // Handle null dates
        // $payGroupDetail->created_at = $payGroupDetail->created_at ? $payGroupDetail->created_at->format('Y-m-d') : '';
        // $payGroupDetail->updated_at = $payGroupDetail->updated_at ? $payGroupDetail->updated_at->format('Y-m-d') : '';

        return view('paymaster.pay-group-details.edit', compact('payGroupDetail', 'payGroup'));
    }


    public function update(Request $request, string $id)
    {
        // Validate the incoming request data for Pay Group Details
        $request->validate([

            'employee_category' => '',
            'mas_grade_id' => '',
            'calculation_method' => 'required',
            'amount' => 'required|numeric',
        ]);

        // Find the existing Pay Group Detail by ID and update its properties
        $payGroupDetail = MasPayGroupDetail::findOrFail($id);
        // $payGroupDetail->employee_category = $request->employee_category;
        $payGroupDetail->mas_grade_id = $request->mas_grade_id;
        $payGroupDetail->calculation_method = $request->calculation_method;
        $payGroupDetail->amount = $request->amount;
        $payGroupDetail->save();

        return redirect()->back()->with('msg_success', 'Pay group detail updated successfully');
    }

    public function destroy(string $id)
    {
        try {
            // Attempt to find and delete the PayGroupDetail
            MasPayGroupDetail::findOrFail($id)->delete();
            return back()->with('msg_success', 'Pay group detail has been deleted');
        } catch (\Exception $e) {
            // Handle the exception, typically due to foreign key constraints
            return back()->with('msg_error', 'Pay group detail cannot be deleted as it has been used by another module. For further information, contact the system admin.');
        }
    }
}
