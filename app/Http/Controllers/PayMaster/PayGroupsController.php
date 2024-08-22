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
        'mas_pay_group_details.*.employee_category' => 'required',
        'mas_pay_group_details.*.grade' => 'required',
        'mas_pay_group_details.*.calculation_method' => 'required',
        'mas_pay_group_details.*.amount' => 'required'
    ];

    public function index(Request $request)
    {
        $privileges = $request->instance();
        $payGroups = MasPayGroup::filter($request)->orderBy('name')->paginate(30);
        return view('paymaster.pay-groups.index', compact('payGroups', 'privileges'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('paymaster.pay-groups.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->rules);
        DB::beginTransaction();

        try {
            $payGroup = new MasPayGroup();
            $payGroup->name = $request->name;
            $payGroup->applicable_on = $request->applicable_on;
            $payGroup->created_by = auth()->user()->id;
            $payGroup->save();

            // Save associated pay group details
            if (isset($request->details)) {
                $this->savePayGroupDetail($request->details, $payGroup->id);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('msg_error', 'The pay group could not be created, please try again.');
        }

        return redirect('paymaster/pay-groups')->with('msg_success', 'Pay Group created successfully');
    }


    public function show(string $id)
    {
        $payGroup = MasPayGroup::findOrFail($id);
        return view('paymaster.pay-groups.show', compact('payGroup'));
    }

    public function edit(string $id)
    {
        // $payGroup = MasPayGroup::findOrFail($id);
        // new code
        $employeeGroups = MasEmployeeGroup::all();
        $payGroup = MasPayGroup::findOrFail($id);
        $grades = MasGrade::all();
        $payGroupDetails = $payGroup->payGroupDetails()->paginate(10);
        return view('paymaster.pay-groups.edit', compact('payGroup', 'payGroupDetails', 'employeeGroups','grades'));
    }

    // public function update(Request $request, string $id)
    // {
    //     $this->validate($request, $this->rules);
    //     if ($request->has('name')) {
    //         // Validate the incoming request data for Pay Slab

    //         // Find the existing PaySlab by ID and update its properties
    //         $payGroup = MasPayGroup::findOrFail($id);
    //         $payGroup->name = $request->name;
    //         $payGroup->applicable_on = $request->applicable_on;
    //         $payGroup->edited_by = auth()->user()->id;
    //         $payGroup->save();

    //         return redirect('paymaster/pay-groups')->with('msg_success', 'Pay group updated successfully');
    //     }

    //     // Check if the request is for updating Pay Slab Details
    //     if ($request->has('employee_category')) {
    //         // Validate the incoming request data for Pay Slab Details

    //         // Find the existing Pay Slab Detail by ID and update its properties
    //         $payGroupDetail = MasPayGroupDetail::findOrFail($id);
    //         $payGroupDetail->employee_category = $request->employee_category;
    //         $payGroupDetail->grade = $request->grade;
    //         $payGroupDetail->calculation_method = $request->calculation_method;
    //         $payGroupDetail->amount = $request->amount;
    //         $payGroupDetail->created_at = $request->created_at;
    //         $payGroupDetail->updated_at = $request->updated_at;
    //         $payGroupDetail->save();

    //         return redirect()->back()->with('msg_success', 'Pay Group detail updated successfully.');
    //     }
    // }
    public function update(Request $request, string $id)
    {
        $this->validate($request, $this->rules);

        $payGroup = MasPayGroup::findOrFail($id);
        $payGroup->name = $request->name;
        $payGroup->applicable_on = $request->applicable_on;
        $payGroup->edited_by = auth()->user()->id;
        $payGroup->save();

        // Handle the update of dynamic fields based on pay group name
        if ($request->has('details')) {
            foreach ($request->details as $detail) {
                $payGroupDetail = MasPayGroupDetail::findOrFail($detail['id']);
                if (isset($detail['employee_category'])) {
                    $payGroupDetail->employee_category = $detail['employee_category'];
                }
                if (isset($detail['grade'])) {
                    $payGroupDetail->grade = $detail['grade'];
                }
                $payGroupDetail->calculation_method = $detail['calculation_method'];
                $payGroupDetail->amount = $detail['amount'];
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

    private function savePayGroupDetail($details, $payGroupId)
    {
        $payGroupDetails = [];

        foreach ($details as $key => $value) {
            $payGroupDetails[] = [
                'mas_pay_group_id' => $payGroupId,
                'employee_category' => $value['employee_category'] ?? null,
                'grade' => $value['grade'] ?? null,
                'calculation_method' => $value['calculation_method'],
                'amount' => $value['amount'],
            ];
        }

        MasPayGroup::findOrFail($payGroupId)->payGroupDetails()->createMany($payGroupDetails);
    }
}
