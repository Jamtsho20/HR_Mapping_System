<?php

namespace App\Services;

// use App\Models\MasApprovalHead;

use App\Models\MasApprovalRule;
use App\Models\MasApprovalRuleConditionOperator;
use App\Models\SystemHierarchy;

class ApprovalService
{
	public function getApproverByHierarchy($approvableId, $approvableType, $conditionfields)
	{ // parameter need to be passed from wherever this class is being invoked
		$approvalRule = MasApprovalRule::with('approvalConditions')
										->where('approvable_id', $approvableId)
										->where('approvable_type', $approvableType)
										->whereIsActive(1)
										->first();


		if (!$approvalRule) { // incase if there is no approval rule defined for particular head
			return [];
		}
		//loop through $approvalRule->approvalConditions as there will be multiple approval conditions
		if (($approvalRule && $approvalRule->approvalConditions) && !empty($conditionfields)) {
			foreach ($approvalRule->approvalConditions as $appvlCondition) {
				// get operator sign from mas_approval_rule_condition_operators table using $appvlCondition->operator_id
				$operatorData = MasApprovalRuleConditionOperator::where('id', $appvlCondition->operator_id)->first(); 
				if ($appvlCondition->mas_condition_field_id == $conditionfields[0]['id'] && $conditionfields[0]['value'] . $operatorData->value . $appvlCondition->value) {
					
					// based on this conditions check get heirarchy / auto approval / single user
					if($appvlCondition->approval_option == HIERARCHICAL_APPVL_OPTION){
						//get matching hierarchy and its level
						$systemHierarchy = SystemHierarchy::with(['hierarchyLevels' => function($query) {
															$query->whereStatus(1)->orderBy('level');
														}])
														->where('id', $appvlCondition->system_hierarchy_id)->first();

						// Get the numeric level of `max_level_id` from `hierarchyLevels`
    					$maxLevel = $systemHierarchy->hierarchyLevels->firstWhere('id', $appvlCondition->max_level_id);

						// Convert the `level` field to an integer for comparison 
						$parsedLevels = $systemHierarchy->hierarchyLevels->map(function($level) {
							$level->numeric_level = (int) filter_var($level->level, FILTER_SANITIZE_NUMBER_INT);
							return $level;
						});

						// Sort by `numeric_level` that has been assigned above by $parsedLevels & order by descending
						$sortedLevels = $parsedLevels->sortByDesc('numeric_level')->values();
						
						// Filter levels below the max level's numeric level 
						$levelsBelowMax = $sortedLevels->filter(function($level) use ($maxLevel) {
							return $level->numeric_level < $maxLevel->numeric_level;
						})->sortByDesc('numeric_level')->values();

						// Check if we have any levels below max, otherwise take the max level itself
						$lowestLevel = $levelsBelowMax->isNotEmpty() ? $levelsBelowMax->sortBy('numeric_level')->first() : $maxLevel;
						$this->getApproverDetail($lowestLevel);
						// return $lowestLevel;

					}else if($appvlCondition->approval_option == SINGLE_USER_APPVL_OPTION){ // then it will be approved in level 1 it self

					}else{ //auto approval option this also will be approved in level 1 it self

					}
				}
			}
		}
	}

	private function getApproverDetail($lowestLevel){
		$loggedInUserDetails = loggedInUser();
		
		if($lowestLevel->approving_authority_id == IMMEDIATE_HEAD){

		}
		dd($lowestLevel);

	}

	private function sentEmailToApprover() {

	}
}
