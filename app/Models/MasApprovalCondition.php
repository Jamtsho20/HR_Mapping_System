<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasApprovalCondition extends Model
{
    use HasFactory;

    public function approvalRule()
    {
        return $this->belongsTo(MasApprovalRule::class, 'mas_approval_rule_id');
    }

    public function systemHierarchy()
    {
        return $this->belongsTo(SystemHierarchy::class, 'system_hierarchy_id');
    }

    public function maxLevel()
    {
        return $this->hasOne(SystemHierarchyLevel::class, 'id');
    }

    public function getFormulaDisplayAttribute($value)
    {
        $field = MasConditionField::whereId($this->mas_condition_field_id)->first();
        $displayFormula = "";

        $operator = MasApprovalRuleConditionOperator::whereId($this->operator_id)->first();
        if($field->has_employee_field) {
            $employee = User::whereId($this->mas_employee_id)->select('id', 'name')->first();
            $displayFormula .= " User ";
            $displayFormula .= $operator->name;
            $displayFormula .= " " . $employee->name;
        } else {
            $displayFormula .= $field->name;
            $displayFormula .= " " . $operator->name;
            $displayFormula .= " " . $this->value;
        }

        return $displayFormula;
    }

    public function getFormulaAttribute($value)
    {
        return $this->mas_condition_field_id . ' ' . $this->operator_id . ' ' . $this->value;
    }
}
