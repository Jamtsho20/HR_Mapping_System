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

        return view('leave.leave-policy.create', compact('leaves', 'gradeSteps', 'employmentTypes'));
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

            $leavePolicyId = $this->saveLeavePolicy($request->leave_policy, null);
            $leavePolicyPlanId = $this->saveLeavePolicyPlan($request->leave_plan, $leavePolicyId);
            $this->saveLeavePolicyRule($request->leave_policy_rule, $leavePolicyPlanId, false);
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

        $leaves = MasLeaveType::get();
        $gradeSteps = MasGradeStep::get(['id', 'name']);
        $employmentTypes = MasEmploymentType::get(['id', 'name']);

        return view('leave.leave-policy.edit', compact('leavePolicy', 'leaves', 'employmentTypes', 'gradeSteps'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // $this->validate($request, $this->rules);
        DB::beginTransaction();
        // dd($request->year_end_processing);
        try {

            $leavePolicyId = $this->saveLeavePolicy($request->leave_policy, $id);


            $leavePolicyPlanId = $this->saveLeavePolicyPlan($request->leave_plan, $leavePolicyId);
            $this->saveLeavePolicyRule($request->leave_policy_rule, $leavePolicyPlanId, true);
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

    private function saveLeavePolicy($policy, $id)
    {

        // $leavePolicy = new MasLeavePolicy();
        // $leavePolicy->mas_leave_type_id = $policy['mas_leave_type_id'];
        // $leavePolicy->name = $policy['name'];
        // $leavePolicy->description = $policy['description'];
        // $leavePolicy->start_date = $policy['start_date'];
        // $leavePolicy->end_date = $policy['end_date'];
        // $leavePolicy->status = $policy['status'];
        // $leavePolicy->is_information_only = isset($policy['is_information_only']) ? $policy['is_information_only'] : 0;
        // $leavePolicy->save();


        $leavePolicyData = [
            'mas_leave_type_id' => $policy['mas_leave_type_id'],
            'name' => $policy['name'],
            'description' => $policy['description'],
            'start_date' => $policy['start_date'],
            'end_date' => $policy['end_date'],
            'status' => $policy['status'],
            'is_information_only' => isset($policy['is_information_only']) ? $policy['is_information_only'] : 0
        ];

        $leavePolicy = MasLeavePolicy::updateOrCreate(
            ['id' => $id],
            $leavePolicyData

        );


        return $leavePolicy->id;
    }

    private function saveLeavePolicyPlan($policyPlan, $leavePolicyId)
    {
        // $leavePolicyPlan = new LeavePolicyPlan();
        // $leavePolicyPlan->mas_leave_policy_id = $leavePolicyId;
        // $leavePolicyPlan->attachment_required = isset($policyPlan['attachment_required']) ? $policyPlan['attachment_required'] : 0;
        // $leavePolicyPlan->gender = $policyPlan['gender'];
        // $leavePolicyPlan->leave_year = $policyPlan['leave_year'];
        // $leavePolicyPlan->credit_frequency = $policyPlan['credit_frequency'];
        // $leavePolicyPlan->credit = $policyPlan['credit'];
        // $leavePolicyPlan->leave_limits = json_encode($policyPlan['leave_limits']);
        // $leavePolicyPlan->can_avail_in = json_encode($policyPlan['can_avail_in']);
        // $leavePolicyPlan->save();
        // return $leavePolicyPlan->id;

        $leavePolicyPlanData = [
            'mas_leave_policy_id' => $leavePolicyId,
            'attachment_required' => isset($policyPlan['attachment_required']) ? $policyPlan['attachment_required'] : 0,
            'gender' => $policyPlan['gender'],
            'leave_year' => $policyPlan['leave_year'],
            'credit_frequency' => $policyPlan['credit_frequency'],
            'credit' => $policyPlan['credit'],
            'leave_limits' => json_encode($policyPlan['leave_limits']),
            'can_avail_in' => json_encode($policyPlan['can_avail_in']),
        ];
        $leavePolicyPlan = LeavePolicyPlan::updateOrCreate(
            ['mas_leave_policy_id' => $leavePolicyId],
            $leavePolicyPlanData

        );


        return $leavePolicyPlan->id;
    }

    private function saveYearEndProcessing($yearEndProcessing, $leavePolicyId)
    {
        // $leaveYearEndProcessing = new LeavePolicyYearEndProcessing();
        // $leaveYearEndProcessing->mas_leave_policy_id = $leavePolicyId;
        // $leaveYearEndProcessing->allow_carryover = $yearEndProcessing['allow_carry_over'] ?? 0;
        // $leaveYearEndProcessing->carryover_limit = $yearEndProcessing['carryover_limit'] ?? 0;
        // $leaveYearEndProcessing->pay_at_year_end = $yearEndProcessing['pay_at_year_end'] ?? 0;
        // $leaveYearEndProcessing->min_balance_required = $yearEndProcessing['min_balance_required'] ?? 0;
        // $leaveYearEndProcessing->min_encashment_per_year = $yearEndProcessing['min_encashment_per_year'] ?? 0;
        // $leaveYearEndProcessing->carry_forward_to_el = $yearEndProcessing['carry_forward_to_el'] ?? 0;
        // $leaveYearEndProcessing->carry_forward_limit = $yearEndProcessing['carry_forward_limit'] ?? 0;
        // $leaveYearEndProcessing->save();

        $leaveYearEndProcessingData = [
            'mas_leave_policy_id' => $leavePolicyId,
            'allow_carryover' => $yearEndProcessing['allow_carry_over'] ?? 0,
            'carryover_limit' => $yearEndProcessing['carryover_limit'] ?? 0,
            'pay_at_year_end' => $yearEndProcessing['pay_at_year_end'] ?? 0,
            'min_balance_required' => $yearEndProcessing['min_balance_required'] ?? 0,
            'min_encashment_per_year' => $yearEndProcessing['min_encashment_per_year'] ?? 0,
            'carry_forward_to_el' => $yearEndProcessing['carry_forward_to_el'] ?? 0,
            'carry_forward_limit' => $yearEndProcessing['carry_forward_limit'] ?? 0
        ];
        $leaveYearEndProcessing = LeavePolicyYearEndProcessing::updateOrCreate(
            ['mas_leave_policy_id' => $leavePolicyId],
            $leaveYearEndProcessingData

        );
        return $leaveYearEndProcessing->id;
    }


    // private function saveLeavePolicyRule($policyRule, $leavePolicyPlanId)
    // {
    //     $data = [];

    //     foreach ($policyRule as $key => $rule) {
    //         // Check if 'mas_grade_step_id' is an array (because of multiple select)
    //         if (isset($rule['mas_grade_step_id']) && is_array($rule['mas_grade_step_id'])) {
    //             foreach ($rule['mas_grade_step_id'] as $gradeStepId) {
    //                 $data[] = [
    //                     'leave_policy_plan_id' => $leavePolicyPlanId,
    //                     'mas_grade_step_id' => $gradeStepId, // Each selected grade step gets a new record
    //                     'uom' => $rule['uom'],
    //                     'duration' => $rule['duration'],
    //                     'start_date' => $rule['start_date'],
    //                     'end_date' => $rule['end_date'],
    //                     'is_loss_of_pay' => $rule['is_loss_of_pay'],
    //                     'mas_employment_type_id' => $rule['mas_employment_type_id'],
    //                     'status' => $rule['status'],
    //                 ];
    //             }
    //         }
    //     }

    //     // Insert all the records at once
    //     LeavePolicyRule::UpdateOrCreate([
    //             'leave_policy_plan_id' => $leavePolicyPlanId,
    //         ],
    //         $data);
    // }
    private function saveLeavePolicyRule($policyRule, $leavePolicyPlanId, $isUpdate = false)
    {
        if ($isUpdate) {
            // Handle single selection update
            foreach ($policyRule as $key => $rule) {
                // Check if leave_policy_plan_id is correctly assigned
                if (is_null($leavePolicyPlanId)) {
                    // Handle the case where leave_policy_plan_id is missing
                    continue;
                }

                // Check if mas_grade_step_id exists in $rule
                if (!isset($rule['mas_grade_step_id'])) {
                    \Log::warning('mas_grade_step_id is missing in rule:', $rule);
                    continue; // Skip this iteration if key is missing
                }

                $ruleData = [
                    'leave_policy_plan_id' => $leavePolicyPlanId,
                    'mas_grade_step_id' => $rule['mas_grade_step_id'],
                    'uom' => $rule['uom'] ?? null, // Handle missing keys gracefully
                    'duration' => $rule['duration'] ?? null,
                    'start_date' => $rule['start_date'] ?? null,
                    'end_date' => $rule['end_date'] ?? null,
                    'is_loss_of_pay' => $rule['is_loss_of_pay'] ?? null,
                    'mas_employment_type_id' => $rule['mas_employment_type_id'] ?? null,
                    'status' => $rule['status'] ?? null,
                ];

                // Debugging: Output the ruleData to check values
                \Log::info('Updating or Creating LeavePolicyRule:', $ruleData);

                // Update or create record
                LeavePolicyRule::updateOrCreate(
                    [
                        'leave_policy_plan_id' => $leavePolicyPlanId,
                        'mas_grade_step_id' => $rule['mas_grade_step_id'],
                    ],
                    $ruleData
                );
            }

            // Fetch existing records for this leave_policy_plan_id
            $existingRules = LeavePolicyRule::where('leave_policy_plan_id', $leavePolicyPlanId)
                ->pluck('mas_grade_step_id')
                ->toArray();

            // Determine which rules are to be deleted
            $newGradeStepIds = array_column($policyRule, 'mas_grade_step_id');
            $rulesToDelete = array_diff($existingRules, $newGradeStepIds);

            // Delete records that are no longer in the new data
            if (!empty($rulesToDelete)) {
                LeavePolicyRule::where('leave_policy_plan_id', $leavePolicyPlanId)
                    ->whereIn('mas_grade_step_id', $rulesToDelete)
                    ->delete();
            }
        } else {
            // Handle multiple selection creation
            foreach ($policyRule as $key => $rule) {
                // Check if mas_grade_step_id exists in $rule
                if (!isset($rule['mas_grade_step_id']) || !is_array($rule['mas_grade_step_id'])) {
                    \Log::warning('mas_grade_step_id is missing or not an array in rule:', $rule);
                    continue; // Skip this iteration if key is missing or not an array
                }

                foreach ($rule['mas_grade_step_id'] as $gradeStepId) {
                    $ruleData = [
                        'leave_policy_plan_id' => $leavePolicyPlanId,
                        'mas_grade_step_id' => $gradeStepId,
                        'uom' => $rule['uom'] ?? null,
                        'duration' => $rule['duration'] ?? null,
                        'start_date' => $rule['start_date'] ?? null,
                        'end_date' => $rule['end_date'] ?? null,
                        'is_loss_of_pay' => $rule['is_loss_of_pay'] ?? null,
                        'mas_employment_type_id' => $rule['mas_employment_type_id'] ?? null,
                        'status' => $rule['status'] ?? null,
                    ];

                    // Use updateOrCreate to insert or update the record
                    LeavePolicyRule::updateOrCreate(
                        ['leave_policy_plan_id' => $leavePolicyPlanId, 'mas_grade_step_id' => $gradeStepId],
                        $ruleData
                    );
                }
            }
        }


    }

}
