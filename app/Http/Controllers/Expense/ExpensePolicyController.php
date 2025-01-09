<?php

namespace App\Http\Controllers\Expense;

use App\Http\Controllers\Controller;
use App\Models\ExpensePolicyEnforcement;
use App\Models\ExpenseRateDefinition;
use App\Models\ExpenseRateLimit;
use App\Models\MasExpensePolicy;
use App\Models\MasExpenseType;
use App\Models\MasGradeStep;
use App\Models\MasRegion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpensePolicyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:expense/expense-policy,view')->only('index', 'show');
        $this->middleware('permission:expense/expense-policy,create')->only('store');
        $this->middleware('permission:expense/expense-policy,edit')->only('update');
        $this->middleware('permission:expense/expense-policy,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
        // $Types = MasType::get(['id', 'name']);
        $expensePolicy = MasExpensePolicy::filter($request)->orderBy('name')->paginate(config('global.pagination'));
        return view('expense.expense-policy.index', compact('privileges', 'expensePolicy'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $expenses = MasExpenseType::get();
        $gradeSteps = MasGradeStep::get(['id', 'name']);
        $regions = MasRegion::get();

        return view('expense.expense-policy.create', compact('expenses', 'gradeSteps', 'regions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // $this->validate($request, $this->rules);
        DB::beginTransaction();

        try {

            $expensePolicyId = $this->saveExpensePolicy($request->expense_policy, null);
            $rateDefinitionId = $this->saveRateDefinition($request->rate_definition, $expensePolicyId);
            $this->saveExpensePolicyRule($request->rate_definition_rule, $rateDefinitionId, false);
            $this->savepolicyEnforcement($request->policy_enforcement, $expensePolicyId);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('msg_error', $e->getMessage());
        }

        return redirect('expense/expense-policy')->with('msg_success', 'Expense policy created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request)
    {
        $instance = $request->instance();
        $canUpdate = (int) $instance->edit;
        $expenses = MasExpenseType::get();
        $gradeSteps = MasGradeStep::get(['id', 'name']);


        $expensePolicy = MasExpensePolicy::findOrFail($id);



        // dd($expensePolicy->expensePolicyPlan);
        return view('expense.expense-policy.show', compact('canUpdate', 'expensePolicy', 'expenses', 'gradeSteps'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $expensePolicy = MasExpensePolicy::findOrFail($id);

        $expenses = MasExpenseType::get();
        $gradeSteps = MasGradeStep::get(['id', 'name']);
        $regions = MasRegion::get();

        return view('expense.expense-policy.edit', compact('expensePolicy', 'expenses', 'gradeSteps', 'regions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {

            $expensePolicyId = $this->saveExpensePolicy($request->expense_policy, $id);
            $rateDefinitionId = $this->saveRateDefinition($request->rate_definition, $expensePolicyId);
            $this->saveExpensePolicyRule($request->rate_definition_rule, $rateDefinitionId, true);
            $this->savepolicyEnforcement($request->policy_enforcement, $expensePolicyId);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('msg_error', $e->getMessage());
        }

        return redirect('expense/expense-policy')->with('msg_success', 'Expense policy Updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            MasExpensePolicy::findOrFail($id)->delete();

            return back()->with('msg_success', 'Expense policy has been deleted');
        } catch (\Exception $e) {
            return back()->with('msg_error', 'Expense policy cannot be delete as it has been used by other module. For further information contact system admin.');
        }
    }
    private function saveExpensePolicy($policy, $id)
    {
        $expensePolicyData = [
            'type_id' => $policy['type_id'],
            'name' => $policy['policy_name'],
            'description' => $policy['description'],
            'start_date' => $policy['start_date'],
            'end_date' => $policy['end_date'],
            'status' => $policy['status'],
        ];

        $expensePolicy = MasExpensePolicy::updateOrCreate(
            ['id' => $id],
            $expensePolicyData

        );
        return $expensePolicy->id;
    }
    private function saverateDefinition($rateDefinition, $expensePolicyId)
    {
        $rateDefinitionData = [
            'mas_expense_policy_id' => $expensePolicyId,
            'attachment_required' => isset($rateDefinition['attachment_required']) ? $rateDefinition['attachment_required'] : 0,
            'travel_type' => $rateDefinition['travel_type'],
            'rate_currency' => $rateDefinition['rate_currency'],
            'currency' => $rateDefinition['currency'],
            'rate_limit' => $rateDefinition['rate_limit'],

        ];
        $rateDefinition = ExpenseRateDefinition::updateOrCreate(
            ['mas_expense_policy_id' => $expensePolicyId],
            $rateDefinitionData

        );

        return $rateDefinition->id;
    }
    private function saveExpensePolicyRule($policyRule, $rateDefinitionId, $isUpdate = false)
    {

        if ($isUpdate) {
            // Handle single selection update
            foreach ($policyRule as $key => $rule) {
                if (is_null($rateDefinitionId)) {
                    // Handle the case where expense_rate_definition_id is missing
                    continue;
                }

                // Check if mas_grade_step_id exists in $rule
                if (!isset($rule['mas_grade_step_id'])) {
                    \Log::warning('mas_grade_step_id is missing in rule:', $rule);
                    continue; // Skip this iteration if key is missing
                }

                $ruleData = [
                    'expense_rate_definition_id' => $rateDefinitionId,
                    'mas_grade_step_id' => $rule['mas_grade_step_id'],
                    'mas_region_id' => $rule['region'] ?? null, // Handle missing keys gracefully
                    'limit_amount' => $rule['limit_amount'] ?? null,
                    'start_date' => $rule['start_date'] ?? null,
                    'end_date' => $rule['end_date'] ?? null,
                    'status' => $rule['status'] ?? null,

                ];

                // Debugging: Output the ruleData to check values
                \Log::info('Updating or Creating Expense Rate Limit:', $ruleData);

                // Update or create record
                ExpenseRateLimit::updateOrCreate(
                    [
                        'expense_rate_definition_id' => $rateDefinitionId,
                        'mas_grade_step_id' => $rule['mas_grade_step_id'],
                    ],
                    $ruleData
                );
            }

            // Fetch existing records for this expense_rate_definition_id
            $existingRules = ExpenseRateLimit::where('expense_rate_definition_id', $rateDefinitionId)
                ->pluck('mas_grade_step_id')
                ->toArray();

            // Determine which rules are to be deleted
            $newGradeStepIds = array_column($policyRule, 'mas_grade_step_id');
            $rulesToDelete = array_diff($existingRules, $newGradeStepIds);

            // Delete records that are no longer in the new data
            if (!empty($rulesToDelete)) {
                ExpenseRateLimit::where('expense_rate_definition_id', $rateDefinitionId)
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
                        'expense_rate_definition_id' => $rateDefinitionId,
                        'mas_grade_step_id' => $gradeStepId,
                        'mas_region_id' => $rule['region'] ?? null, // Handle missing keys gracefully
                        'limit_amount' => $rule['limit_amount'] ?? null,
                        'start_date' => $rule['start_date'] ?? null,
                        'end_date' => $rule['end_date'] ?? null,
                        'status' => $rule['status'] ?? null,
                    ];

                    // Use updateOrCreate to insert or u pdate the record
                    ExpenseRateLimit::updateOrCreate(
                        ['expense_rate_definition_id' => $rateDefinitionId, 'mas_grade_step_id' => $gradeStepId],
                        $ruleData
                    );
                }
            }
        }
    }

    private function savepolicyEnforcement($policyEnforcement, $expensePolicyId)
    {


        $policyEnforcementData = [
            'mas_expense_policy_id' => $expensePolicyId,
            'prevent_report_submission' => $policyEnforcement['prevent_report_submission'] ?? 0,
            'display_warning_to_user' => $policyEnforcement['display_warning_to_user'] ?? 0,

        ];
        $policyEnforcement = ExpensePolicyEnforcement::updateOrCreate(
            ['mas_expense_policy_id' => $expensePolicyId],
            $policyEnforcementData

        );
        return $policyEnforcement->id;
    }
}
