<?php

namespace App\Http\Controllers\Leave;

use App\Http\Controllers\Controller;
use App\Models\LeavePolicyPlan;
use App\Models\LeavePolicyRule;
use App\Models\LeavePolicyYearEndProcessing;
use App\Models\MasGradeStep;
use App\Models\MasLeavePolicy;
use App\Models\MasLeaveType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeavePolicyController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:leave/leave-policy,view')->only('index');
        $this->middleware('permission:leave/leave-policy,create')->only('create');
        $this->middleware('permission:leave/leave-policy,edit')->only('update');
        $this->middleware('permission:leave/leave-policy,destroy')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    protected $rules = [
        //rules for leave_policy
        'leave_policy.mas_leave_type_id' => 'required', 
        'leave_policy.name' => 'required|date', 
        'leave_policy.start_date' => 'required', 
        //rules for Leave_plan
        'Leave_plan.gender' => 'required',
        'Leave_plan.leave_year' => 'required',
        'Leave_plan.credit_frequency' => 'required',
        'Leave_plan.credit' => 'required',
        //rules for leave_policy_rule
        'leave_policy_rule.mas_grade_step_id' => 'required',
        'leave_policy_rule.uom' => 'required',
        'leave_policy_rule.duration' => 'required',
        'leave_policy_rule.start_date' => 'required|date',
        'leave_policy_rule.is_loss_of_pay' => 'required',
        'leave_policy_rule.mas_employment_type_id' => 'required',
    ];

    public function index(Request $request)
    {
        $privileges = $request->instance();
        $leaveTypes = MasLeaveType::get(['id', 'name']);
        $leavePolicy = MasLeavePolicy::filter($request)->orderBy('name')->paginate(30);
        return view('leave.leave-policy.index', compact('privileges', 'leaveTypes', 'leavePolicy'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $leaves = MasLeaveType::get();
        $gradeSteps=MasGradeStep::get(['id', 'name']);

        return view('leave.leave-policy.create', compact('leaves',  'gradeSteps'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->rules);
        DB::beginTransaction();
        try{
            $leavePolicyId = $this->saveLeavePolicy($request->leave_policy);
            $leavePolicyPlanId = $this->saveLeavePolicyPlan($request->Leave_plan, $leavePolicyId);
            $this->saveYearEndProcessing($request->year_end_processing, $leavePolicyId);
            $this->saveLeavePolicyRule($request->leave_policy_rule, $leavePolicyPlanId);
            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
            return back()->withInput()->with('msg_error', $e->getMessage());
        }

        return redirect('leave/leave-policy/index')->with('msg_success', 'Leave policy created successfully.');
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    private function saveLeavePolicy($policy){
        $leavePolicy = new MasLeavePolicy();
        $leavePolicy->mas_leave_type_id = $policy['mas_leave_type_id'];
        $leavePolicy->name = $policy['name'];
        $leavePolicy->description = $policy['description'];
        $leavePolicy->start_date = $policy['start_date'];
        $leavePolicy->end_date = $policy['end_date'];
        $leavePolicy->status = $policy['status'];
        $leavePolicy->is_information_only = $policy['is_information_only'];
        $leavePolicy->save();

        return $leavePolicy->id;
    }

    private function saveLeavePolicyPlan($policyPlan, $leavePolicyId){
        $leavePolicyPlan = new LeavePolicyPlan();
        $leavePolicyPlan->mas_leave_policy_id = $leavePolicyId;
        $leavePolicyPlan->attachment_required = $policyPlan['attachment_required'];
        $leavePolicyPlan->gender = $policyPlan['gender'];
        $leavePolicyPlan->leave_year = $policyPlan['leave_year'];
        $leavePolicyPlan->credit_frequency = $policyPlan['credit_frequency'];
        $leavePolicyPlan->credit = $policyPlan['credit'];
        $leavePolicyPlan->leave_limits = $policyPlan['leave_limits'];
        $leavePolicyPlan->can_avail_in = $policyPlan['can_avail_in'];
        $leavePolicyPlan->save();
        return $leavePolicyPlan->id;
    }

    private function saveYearEndProcessing($yearEndProcessing, $leavePolicyId){
        $leaveYearEndProcessing = new LeavePolicyYearEndProcessing();
        $leaveYearEndProcessing->mas_leave_policy_id = $leavePolicyId;
        $leaveYearEndProcessing->allow_carry_over = $yearEndProcessing['allow_carry_over'];
        $leaveYearEndProcessing->carryover_limit = $yearEndProcessing['carryover_limit'];
        $leaveYearEndProcessing->pay_at_year_end = $yearEndProcessing['pay_at_year_end'];
        $leaveYearEndProcessing->min_balance_required = $yearEndProcessing['min_balance_required'];
        $leaveYearEndProcessing->min_encashment_per_year = $yearEndProcessing['min_encashment_per_year'];
        $leaveYearEndProcessing->carry_forward_to_el = $yearEndProcessing['carry_forward_to_el'];
        $leaveYearEndProcessing->carry_forward_limit = $yearEndProcessing['carry_forward_limit'];
        $leaveYearEndProcessing->save();
    }

    private function saveLeavePolicyRule($policyRule, $leavePolicyPlanId){
        // $leavePolicyRule = new LeavePolicyRule();
        $data = [];
        foreach($policyRule->grade_step as $key => $value){
            $data[] = [
                'leave_policy_plan_id' => $leavePolicyPlanId,
                'mas_grade_step_id' => $value['mas_grade_step_id'],
                'uom' => $policyRule['uom'],
                'duration' => $policyRule['duration'],
                'start_date' => $policyRule['start_date'],
                'end_date' => $policyRule['end_date'],
                'is_loss_of_pay' => $policyRule['is_loss_of_pay'],
                'mas_employment_type_id' => $policyRule['mas_employment_type_id'],
                'status' => $policyRule['status'],
            ];
        }
        LeavePolicyRule::insert($data);
    }
}
