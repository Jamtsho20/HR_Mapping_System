<?php

namespace App\Http\Controllers\SystemSetting;

use App\Http\Controllers\Controller;
use App\Models\MasAdvanceTypes;
use App\Models\MasApprovalCondition;
use App\Models\MasApprovalHead;
use App\Models\MasApprovalRule;
use App\Models\MasApprovalRuleConditionOperator;
use App\Models\MasConditionField;
use App\Models\MasExpenseType;
use App\Models\MasLeaveType;
use App\Models\SystemHierarchy;
use App\Models\User;
use Illuminate\Http\Request;

class ApprovalRuleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:system-setting/approval-rules,view')->only('index', 'show');
        $this->middleware('permission:system-setting/approval-rules,create')->only('store');
        $this->middleware('permission:system-setting/approval-rules,edit')->only('update', 'edit', 'addCondition');
        $this->middleware('permission:system-setting/approval-rules,delete')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $approvalRules = MasApprovalRule::filter($request)->orderBy('name')->paginate(config('global.pagination'))->withQueryString();
        $heads = MasApprovalHead::get();

        return view('system-settings.approval-rule.index', compact('privileges', 'approvalRules', 'heads'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $privileges = $request->instance();
        $employees = User::select('id', 'name', 'employee_id')->get();
        $heads = MasApprovalHead::get();
        $operators = MasApprovalRuleConditionOperator::select('id', 'name', 'value')->orderBy('name')->get();
        $hierarchies = SystemHierarchy::select('id', 'name')->get();

        return view('system-settings.approval-rule.create', compact('privileges', 'employees', 'heads', 'operators', 'hierarchies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'mas_approval_head_id' => 'required',
            'name' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ],
        [
            'mas_approval_head_id.required' => 'Approval head field is required.'
        ]
    );

        $models = [
            1 => MasLeaveType::class,
            2 => MasExpenseType::class,
            3 => MasAdvanceTypes::class,
        ];

        if (isset($models[$request->mas_approval_head_id])) {
            $modelInstance = $models[$request->mas_approval_head_id]::find($request->approvable_id);

            $approvableRule = $modelInstance->approvableRule()->create([
                'mas_approval_head_id' => $request->mas_approval_head_id,
                'name' => $request->name,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'is_active' => $request->is_active,
            ]);
        }

        return redirect()->route('approval-rules.edit', $approvableRule->id)->with('msg_success', 'Approval rule has been added successfully. Add conditions');
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
    public function edit(Request $request, string $id)
    {
        $privileges = $request->instance();
        $rule = MasApprovalRule::whereId($id)->firstOrFail();
        $approvalHeadId = $rule->mas_approval_head_id;
        $fields = MasConditionField::whereMasApprovalHeadId($approvalHeadId)->select('id', 'name', 'label', 'has_employee_field')->get();
        $conditions = MasApprovalCondition::whereMasApprovalRuleId($id)->get();
        $employees = User::select('id', 'name', 'employee_id')->get();
        $heads = MasApprovalHead::get();
        $operators = MasApprovalRuleConditionOperator::select('id', 'name', 'value')->orderBy('name')->get();
        $hierarchies = SystemHierarchy::select('id', 'name')->get();

        return view('system-settings.approval-rule.edit', compact('privileges', 'rule', 'fields', 'conditions', 'employees', 'heads', 'operators', 'hierarchies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $rule = MasApprovalRule::whereId($id)->first();
            $rule->name = $request->name;
            $rule->is_active = $request->is_active;
            $rule->save();

            return redirect()->back()->with('msg_success', 'Approval rule has been updated successfully');

        } catch (\Exception $e) {
            return redirect()->back()->with('msg_error', 'Error processing formula: ' . $e->getMessage());
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function addCondition(Request $request)
    {
        $request->validate([
            'mas_approval_rule_id' => 'required',
            'system_hierarchy_id' => 'nullable',
            'max_level_id' => 'required_if:system_hierarchy_id,!=,null|integer',
        ],
            [
                'mas_approval_rule_id.required' => 'Approval rule is required.',
            ]
        );

        try {
            $formula = $request->formula;
            if ($formula) {
                preg_match_all('/([^\[\]]+)|(\[.*?\].*?)(?=\[|$)/', $formula, $matches);
                $result = [];

                foreach ($matches[0] as $match) {
                    $result[] = trim($match);
                }

                $ruleId = $request->mas_approval_rule_id;

                foreach ($result as $key => $values) {
                    $condition = new MasApprovalCondition();
                    $condition->mas_approval_rule_id = $ruleId;

                    $values = explode(" ", $values);

                    if ($key == 0) {
                        $field = MasConditionField::whereId($values[0])->first();
                        $condition->operator_id = $values[1];
                    } else {
                        $field = MasConditionField::whereId($values[1])->first();
                        $condition->delimiter = $values[0];
                        $condition->operator_id = $values[2];
                    }

                    if ($field) {
                        $condition->mas_condition_field_id = $field->id;
                        if ($field->has_employee_field == 1) {
                            if ($key == 0) {
                                $condition->mas_employee_id = $values[2];
                            } else {
                                $condition->mas_employee_id = $values[3];
                            }
                        } else {
                            if ($key == 0) {
                                $condition->value = $values[2];
                            } else {
                                $condition->value = $values[3];
                            }
                        }
                    }

                    $approvalOption = $request->approval_option;

                    $condition->approval_option = $approvalOption;

                    if ($approvalOption == 1) {
                        $condition->system_hierarchy_id = $request->system_hierarchy_id;
                        $condition->max_level_id = $request->max_level_id;
                    } elseif ($approvalOption == 2) {
                        $condition->is_single_user = 1;
                        $condition->appvl_employee_id = $request->appvl_employee_id;
                    } else {
                        $condition->auto_approval = 1;
                    }

                    $condition->fyi_employee_id = $request->fyi_level ?? null;
                    $condition->email = $request->email ?? null;
                    $condition->fyi_employee_id = $request->fyi_employee_id ?? null;
                    $condition->save();
                }

                return redirect()->back()->with('msg_success', 'Approval rule conditions have been added successfully.');
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('msg_error', 'Error processing formula: ' . $e->getMessage());
        }
    }

    public function getEditCondition(Request $request, $id) {

        $privileges = $request->instance();
        $condition = MasApprovalCondition::whereId($id)->firstOrFail();
        $rule = $condition->approvalRule;
        $approvalHeadId = $rule->mas_approval_head_id;
        $fields = MasConditionField::whereMasApprovalHeadId($approvalHeadId)->select('id', 'name', 'label', 'has_employee_field')->get();
        $employees = User::select('id', 'name', 'employee_id')->get();
        $heads = MasApprovalHead::get();
        $operators = MasApprovalRuleConditionOperator::select('id', 'name', 'value')->orderBy('name')->get();
        $hierarchies = SystemHierarchy::select('id', 'name')->get();

        return view('system-settings.approval-rule.conditions.edit', compact('privileges', 'rule', 'fields', 'condition', 'employees', 'heads', 'operators', 'hierarchies'));
    }

    public function updateCondition(Request $request, $id)
    {
        $request->validate([
            'mas_approval_rule_id' => 'required',
            'system_hierarchy_id' => 'nullable',
            'max_level_id' => 'required_if:system_hierarchy_id,!=,null|integer',
        ],
            [
                'mas_approval_rule_id.required' => 'Approval rule is required.',
            ]
        );

        try {
            $formula = $request->formula;
            if ($formula) {
                preg_match_all('/([^\[\]]+)|(\[.*?\].*?)(?=\[|$)/', $formula, $matches);
                $result = [];

                foreach ($matches[0] as $match) {
                    $result[] = trim($match);
                }

                $ruleId = $request->mas_approval_rule_id;

                foreach ($result as $key => $values) {
                    $condition = MasApprovalCondition::whereId($id)->firstOrFail();

                    $condition->mas_approval_rule_id = $ruleId;

                    $values = explode(" ", $values);

                    if ($key == 0) {
                        $field = MasConditionField::whereId($values[0])->first();
                        $condition->operator_id = $values[1];
                    } else {
                        $field = MasConditionField::whereId($values[1])->first();
                        $condition->delimiter = $values[0];
                        $condition->operator_id = $values[2];
                    }

                    if ($field) {
                        $condition->mas_condition_field_id = $field->id;
                        if ($field->has_employee_field == 1) {
                            if ($key == 0) {
                                $condition->mas_employee_id = $values[2];
                            } else {
                                $condition->mas_employee_id = $values[3];
                            }
                        } else {
                            if ($key == 0) {
                                $condition->value = $values[2];
                            } else {
                                $condition->value = $values[3];
                            }
                        }
                    }

                    $approvalOption = $request->approval_option;

                    $condition->approval_option = $approvalOption;

                    if ($approvalOption == 1) {
                        $condition->system_hierarchy_id = $request->system_hierarchy_id;
                        $condition->max_level_id = $request->max_level_id;
                    } elseif ($approvalOption == 2) {
                        $condition->is_single_user = 1;
                        $condition->appvl_employee_id = $request->appvl_employee_id;
                    } else {
                        $condition->auto_approval = 1;
                    }

                    $condition->fyi_employee_id = $request->fyi_level ?? null;
                    $condition->email = $request->email ?? null;
                    $condition->fyi_employee_id = $request->fyi_employee_id ?? null;
                    $condition->save();
                }

                return redirect()->back()->with('msg_success', 'Approval rule conditions have been added successfully.');
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('msg_error', 'Error processing formula: ' . $e->getMessage());
        }
    }
}
