<?php

namespace App\Http\Controllers\Leave;

use App\Http\Controllers\Controller;
use App\Models\LeavePolicyPlan;
use App\Models\LeavePolicyRule;
use App\Models\LeavePolicyYearEndProcessing;
use App\Models\MasEmploymentType;
use App\Models\MasGradeStep;
use App\Models\MasLeavePolicy;
use App\Models\MasLeaveType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeavePolicyController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:leave/leave-policy,view')->only('index', 'show');
        $this->middleware('permission:leave/leave-policy,create')->only('store');
        $this->middleware('permission:leave/leave-policy,edit')->only('update');
        $this->middleware('permission:leave/leave-policy,destroy')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    protected $rules = [
        //rules for leave_policy
        'leave_policy.mas_leave_type_id' => 'required',
        'leave_policy.name' => 'required',
        'leave_policy.start_date' => 'required|date',
        // //rules for leave_plan
        'leave_plan.gender' => 'required',
        'leave_plan.leave_year' => 'required',
        'leave_plan.credit_frequency' => 'required',
        'leave_plan.credit' => 'required',
        // //rules for leave_policy_rule
        'leave_policy_rule.*.mas_grade_step_id' => 'required|array|min:1', // Ensures at least one grade is selected
        'leave_policy_rule.*.mas_grade_step_id.*' => 'required|integer|exists:mas_grade_steps,id', // Validate each selected grade ID
        'leave_policy_rule.*.uom' => 'required',
        'leave_policy_rule.*.duration' => 'required',
        'leave_policy_rule.*.start_date' => 'required|date',
        'leave_policy_rule.*.is_loss_of_pay' => 'required',
        'leave_policy_rule.*.mas_employment_type_id' => 'required',
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
        $gradeSteps = MasGradeStep::get(['id', 'name']);
        $employmentTypes = MasEmploymentType::get(['id', 'name']);

        return view('leave.leave-policy.create', compact('leaves',  'gradeSteps', 'employmentTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $this->validate($request, $this->rules);
        DB::beginTransaction();
        // dd($request->year_end_processing);
        try {

            $leavePolicyId = $this->saveLeavePolicy($request->leave_policy);
            $leavePolicyPlanId = $this->saveLeavePolicyPlan($request->leave_plan, $leavePolicyId);
            $this->saveLeavePolicyRule($request->leave_policy_rule, $leavePolicyPlanId);
            $this->saveYearEndProcessing($request->year_end_processing, $leavePolicyId);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('msg_error', $e->getMessage());
        }

        return redirect('leave/leave-policy')->with('msg_success', 'Leave policy created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request)
    {
        $instance = $request->instance();
        $canUpdate = (int) $instance->edit;

        $leavePolicy = MasLeavePolicy::findOrFail($id);



        // dd($leavePolicy->leavePolicyPlan);
        return view('leave.leave-policy.show', compact('canUpdate', 'leavePolicy'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        $leavePolicy = MasLeavePolicy::findOrFail($id);
        // dd($leavePolicy->yearEnd);
        $leaves = MasLeaveType::get();
        $gradeSteps = MasGradeStep::get(['id', 'name']);
        $employmentTypes = MasEmploymentType::get(['id', 'name']);

        return view('leave.leave-policy.edit', compact('leavePolicy', 'leaves', 'employmentTypes', 'gradeSteps'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, $this->rules);
        DB::beginTransaction();
        // dd($request->year_end_processing);
        try {

            $leavePolicyId = $this->saveLeavePolicy($request->leave_policy);
            $leavePolicyPlanId = $this->saveLeavePolicyPlan($request->leave_plan, $leavePolicyId);
            $this->saveLeavePolicyRule($request->leave_policy_rule, $leavePolicyPlanId);
            $this->saveYearEndProcessing($request->year_end_processing, $leavePolicyId);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('msg_error', $e->getMessage());
        }

        return redirect('leave/leave-policy')->with('msg_success', 'Leave policy Updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    private function saveLeavePolicy($policy)
    {

        $leavePolicy = new MasLeavePolicy();
        $leavePolicy->mas_leave_type_id = $policy['mas_leave_type_id'];
        $leavePolicy->name = $policy['name'];
        $leavePolicy->description = $policy['description'];
        $leavePolicy->start_date = $policy['start_date'];
        $leavePolicy->end_date = $policy['end_date'];
        $leavePolicy->status = $policy['status'];
        $leavePolicy->is_information_only = isset($policy['is_information_only']) ? $policy['is_information_only'] : 0;
        $leavePolicy->save();

        return $leavePolicy->id;
    }

    private function saveLeavePolicyPlan($policyPlan, $leavePolicyId)
    {
        $leavePolicyPlan = new LeavePolicyPlan();
        $leavePolicyPlan->mas_leave_policy_id = $leavePolicyId;
        $leavePolicyPlan->attachment_required = isset($policyPlan['attachment_required']) ? $policyPlan['attachment_required'] : 0;
        $leavePolicyPlan->gender = $policyPlan['gender'];
        $leavePolicyPlan->leave_year = $policyPlan['leave_year'];
        $leavePolicyPlan->credit_frequency = $policyPlan['credit_frequency'];
        $leavePolicyPlan->credit = $policyPlan['credit'];
        $leavePolicyPlan->leave_limits = json_encode($policyPlan['leave_limits']);
        $leavePolicyPlan->can_avail_in = json_encode($policyPlan['can_avail_in']);
        $leavePolicyPlan->save();
        return $leavePolicyPlan->id;
    }

    private function saveYearEndProcessing($yearEndProcessing, $leavePolicyId)
    {
        $leaveYearEndProcessing = new LeavePolicyYearEndProcessing();
        $leaveYearEndProcessing->mas_leave_policy_id = $leavePolicyId;
        $leaveYearEndProcessing->allow_carryover = $yearEndProcessing['allow_carry_over'] ?? 0;
        $leaveYearEndProcessing->carryover_limit = $yearEndProcessing['carryover_limit'] ?? 0;
        $leaveYearEndProcessing->pay_at_year_end = $yearEndProcessing['pay_at_year_end'] ?? 0;
        $leaveYearEndProcessing->min_balance_required = $yearEndProcessing['min_balance_required'] ?? 0;
        $leaveYearEndProcessing->min_encashment_per_year = $yearEndProcessing['min_encashment_per_year'] ?? 0;
        $leaveYearEndProcessing->carry_forward_to_el = $yearEndProcessing['carry_forward_to_el'] ?? 0;
        $leaveYearEndProcessing->carry_forward_limit = $yearEndProcessing['carry_forward_limit'] ?? 0;
        $leaveYearEndProcessing->save();
    }


    private function saveLeavePolicyRule($policyRule, $leavePolicyPlanId)
    {
        $data = [];

        foreach ($policyRule as $key => $rule) {
            // Check if 'mas_grade_step_id' is an array (because of multiple select)
            if (isset($rule['mas_grade_step_id']) && is_array($rule['mas_grade_step_id'])) {
                foreach ($rule['mas_grade_step_id'] as $gradeStepId) {
                    $data[] = [
                        'leave_policy_plan_id' => $leavePolicyPlanId,
                        'mas_grade_step_id' => $gradeStepId, // Each selected grade step gets a new record
                        'uom' => $rule['uom'],
                        'duration' => $rule['duration'],
                        'start_date' => $rule['start_date'],
                        'end_date' => $rule['end_date'],
                        'is_loss_of_pay' => $rule['is_loss_of_pay'],
                        'mas_employment_type_id' => $rule['mas_employment_type_id'],
                        'status' => $rule['status'],
                    ];
                }
            }
        }

        // Insert all the records at once
        LeavePolicyRule::insert($data);
    }
}
