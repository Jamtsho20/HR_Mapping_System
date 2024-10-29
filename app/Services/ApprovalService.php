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
				// dd($appvlCondition);
				$operatorData = MasApprovalRuleConditionOperator::where('id', $appvlCondition->operator_id)->first(); // get operator sign
				if ($appvlCondition->mas_condition_field_id == $conditionfields[0]['id'] && $conditionfields[0]['value'] . $operatorData->value . $appvlCondition->value) {
					
					// based on this conditions check get heirarchy / auto approval / single user
					if($appvlCondition->approval_option == HIERARCHICAL_APPVL_OPTION){
						//get matching hierarchy and its level
						$systemHierarchy = SystemHierarchy::with(['hierarchyLevels' => function($query) {
															$query->whereStatus(1)->order_by('level');
														}])
														->where('id', $appvlCondition->system_hierarchy_id)->first();
						// Convert the `level_number` to an integer for comparison
						$parsedLevels = $systemHierarchy->hierarchyLevels->map(function($level) {
							// Extract numeric part and store it as `numeric_level`
							$level->numeric_level = (int) filter_var($level->level_number, FILTER_SANITIZE_NUMBER_INT);
							return $level;
						});
						dd($parsedLevels);
						// Sort by `numeric_level` descending
						$sortedLevels = $parsedLevels->sortByDesc('numeric_level')->values();

						// Get the highest level
						$highestLevel = $sortedLevels->first();

						// Collect all levels below the highest level
						$levelsBelowHighest = $sortedLevels->filter(function($level) use ($highestLevel) {
							return $level->numeric_level < $highestLevel->numeric_level;
						})->values();
					}else if($appvlCondition->approval_option == SINGLE_USER_APPVL_OPTION){ // then it will be approved in level 1 it self

					}else{ //auto approval option this also will be approved in level 1 it self

					}
				}
			}
		}
	}

	private function sentEmailToApprover() {

	}
}
