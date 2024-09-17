<?php

namespace App\Http\Controllers\PayMaster;

use App\Http\Controllers\Controller;
use App\Models\MasEmployeeGroup;
use App\Models\MasGrade;
use App\Models\MasPayGroup;
use App\Models\MasPayGroupDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayGroupsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:paymaster/pay-groups,view')->only('index');
        $this->middleware('permission:paymaster/pay-groups,create')->only('store');
        $this->middleware('permission:paymaster/pay-groups,edit')->only('update');
        $this->middleware('permission:paymaster/pay-groups,delete')->only('destroy');
    }

    protected $rules = [
        'name' => 'required|string|max:150',
        'applicable_on' => 'required',
        'mas_pay_group_details.*.mas_employee_group_id' => 'required',
        'mas_pay_group_details.*.mas_grade_id' => 'required',
        'mas_pay_group_details.*.calculation_method' => 'required',
        'mas_pay_group_details.*.amount' => 'required'
    ];

    public function index(Request $request)
    {
        $privileges = $request->instance();
        $payGroups = MasPayGroup::filter($request)->orderBy('name')->paginate(30);
        $empCategories = MasEmployeeGroup::get(['id', 'name']);

        return view('paymaster.pay-groups.index', compact('payGroups', 'privileges', 'empCategories'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employeeGroups = MasEmployeeGroup::all();
        $grades = MasGrade::all();
        return view('paymaster.pay-groups.create',compact('employeeGroups','grades'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $this->validate($request, $this->rules);
    
        DB::beginTransaction();
    
        try {
            // Create a new pay group
            $payGroup = new MasPayGroup();
            $payGroup->name = $request->name;
            $payGroup->applicable_on = $request->applicable_on;
            $payGroup->created_by = auth()->user()->id;
            $payGroup->save();
    
            // Save associated pay group details
            if ($request->has('details')) {
                $this->savePayGroupDetail($request->details, $payGroup->id);
            }
    
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            // Log the exception or handle as needed
            return back()->with('msg_error', 'The pay group could not be created, please try again.');
        }
    
        return redirect('paymaster/pay-groups')->with('msg_success', 'Pay Group created successfully');
    }
    
    private function savePayGroupDetail($details, $payGroupId)
    {
        $payGroupDetails = [];
    
        foreach ($details as $key => $value) {
            $payGroupDetails[] = [
                'mas_pay_group_id' => $payGroupId,
                'mas_employee_group_id' => $value['mas_employee_group_id'] ?? null,
                'mas_grade_id' => $value['mas_grade_id'] ?? null,
                'calculation_method' => $value['calculation_method'],
                'amount' => $value['amount'],
                'created_by' => auth()->user()->id, 
            ];
        }
    
        MasPayGroupDetail::insert($payGroupDetails);
    }
    

    public function show(string $id)
    {
        $payGroup = MasPayGroup::findOrFail($id);
        return view('paymaster.pay-groups.show', compact('payGroup'));
    }

    public function edit(string $id)
    {
        $payGroup = MasPayGroup::findOrFail($id);
        $employeeGroups = MasEmployeeGroup::all();
        $grades = MasGrade::all();
        $payGroupDetails = $payGroup->payGroupDetails()->paginate(10);
        $empCategories = MasEmployeeGroup::get(['id', 'name']);
        //dd($payGroupDetails);

        return view('paymaster.pay-groups.edit', compact('payGroup', 'payGroupDetails', 'employeeGroups', 'grades', 'empCategories'));
    }


    public function update(Request $request, string $id)
{
    $this->validate($request, $this->rules);

    $payGroup = MasPayGroup::findOrFail($id);
    $payGroup->name = $request->name;
    $payGroup->applicable_on = $request->applicable_on;
    $payGroup->edited_by = auth()->user()->id;
    $payGroup->save();

    if ($request->has('details')) {
        foreach ($request->details as $detail) {
            $payGroupDetail = MasPayGroupDetail::findOrFail($detail['id']);
            if (isset($detail['mas_employee_group_id'])) {
                $payGroupDetail->mas_employee_group_id = $detail['mas_employee_group_id'];
            }
            if (isset($detail['mas_grade_id'])) {
                $payGroupDetail->mas_grade_id = $detail['mas_grade_id'];
            }
            $payGroupDetail->calculation_method = $detail['calculation_method'];
            $payGroupDetail->amount = $detail['amount'];
            $payGroupDetail->edited_by = auth()->user()->id;  // Add this line if applicable
            $payGroupDetail->save();
        }
    }

    return redirect('paymaster/pay-groups')->with('msg_success', 'Pay group updated successfully');
}



    public function destroy(string $id)
    {
        try {
            // Attempt to find and delete the pay group
            MasPayGroup::findOrFail($id)->delete();

            // Redirect back with a success message
            return back()->with('msg_success', 'Pay group has been deleted');
        } catch (\Exception $e) {
            // Handle the exception, typically due to foreign key constraints
            return back()->with('msg_error', 'Pay group cannot be deleted as it has been used by another module. For further information, contact the system admin.');
        }
    }

    // private function savePayGroupDetail($details, $payGroupId)
    // {
    //     $payGroupDetails = [];

    //     foreach ($details as $key => $value) {
    //         $payGroupDetails[] = [
    //             'mas_pay_group_id' => $payGroupId,
    //             'employee_category' => $value['employee_category'] ?? null,
    //             'grade' => $value['grade'] ?? null,
    //             'calculation_method' => $value['calculation_method'],
    //             'amount' => $value['amount'],
    //         ];
    //     }

    //     MasPayGroup::findOrFail($payGroupId)->payGroupDetails()->createMany($payGroupDetails);
    // }
}
