<?php

namespace App\Services;

// use App\Models\MasApprovalHead;

use App\Models\MasApprovalCondition;
use App\Models\MasApprovalRule;
use App\Models\MasApprovalRuleConditionOperator;
use App\Models\SystemHierarchy;
use App\Models\SystemHierarchyLevel;

class ApprovalService
{
	public function notifyApprover($approvableId, $approvableType, $conditionfields)
	{ // parameter need to be passed from wherever this class is being invoked
		$approvalRule = MasApprovalRule::with('approvalConditions')
										->where('approvable_id', $approvableId)
										->where('approvable_type', $approvableType)
										->whereIsActive(1)
										->first();


		if (!$approvalRule) { // incase if there is no approval rule defined for particular head
			return [];
		}
		$approver = "";
		if (($approvalRule && $approvalRule->approvalConditions) && !empty($conditionfields)) {
			foreach ($approvalRule->approvalConditions as $appvlCondition) {
				dd($appvlCondition);
				$operatorData = MasApprovalRuleConditionOperator::where('id', $appvlCondition->operator_id)->first(); // get operator sign
				if ($appvlCondition->mas_condition_field_id == $conditionfields[0]['id'] && $conditionfields[0]['value'] . $operatorData->value . $appvlCondition->value) {
					// based on this conditions check get heirarchy / auto approval / single user
					if($appvlCondition->approval_option == HIERARCHICAL_APPVL_OPTION){
						//get matching hierarchy and its level
						$sytemHierarchy = SystemHierarchy::with(['hierarchyLevels' => function($query) use($appvlCondition){
															$query->where('id', $appvlCondition->max_level_id);
														}])
														->where('id', $appvlCondition->system_hierarchy_level)->first();
						$hierarchyLevel = SystemHierarchyLevel::where('system_hierarchy_id', $appvlCondition->system_hierarchy_level)->get(); //fetch collection of levels
						$highestLevel = $hierarchyLevel->where('id', $appvlCondition->max_level_id)->first(); //level upto which application can reach for approval
						
					}else if($appvlCondition->approval_option == SINGLE_USER_APPVL_OPTION){

					}else{ //auto approval option

					}
				}
			}
		}
	}

	private function sentEmailToApprover() {

	}
}
