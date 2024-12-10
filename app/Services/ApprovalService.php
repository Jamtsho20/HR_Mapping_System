<?php

namespace App\Services;

use App\Models\ApplicationHistory;
use App\Models\ApprovingAuthority;
use App\Models\MasApprovalRule;
use App\Models\MasApprovalRuleConditionOperator;
use App\Models\MasEmployeeJob;
use App\Models\SystemHierarchy;
use App\Models\User;

class ApprovalService
{
	// for the first time to get approver we need to check using approval option as well as using approval rule so it is done differently
	// public function getApproverByHierarchy($approvableId, $approvableType, $conditionfields)
	// { // parameter need to be passed from wherever this class is being invoked
	// 	//here need to check if condition field not defined
	// 	$approvalRule = MasApprovalRule::with('approvalConditions')
	// 									->where('approvable_id', $approvableId)
	// 									->where('approvable_type', $approvableType)
	// 									->whereIsActive(1)
	// 									->first();

	// 	if (!$approvalRule) { // incase if there is no approval rule defined for particular head
	// 		return [];
	// 	}
	// 	//loop through $approvalRule->approvalConditions as there will be multiple approval conditions
	// 	if (($approvalRule && $approvalRule->approvalConditions) && !empty($conditionfields)) {
	// 		// dd($approvalRule->approvalConditions);
	// 		foreach ($approvalRule->approvalConditions as $appvlCondition) {
	// 			// get operator sign from mas_approval_rule_condition_operators table using $appvlCondition->operator_id
	// 			// dd($appvlCondition);
	// 			$operatorData = MasApprovalRuleConditionOperator::where('id', $appvlCondition->operator_id)->first();
	// 			// dd((float)$conditionfields[0]['value'] ,$operatorData->value . $appvlCondition->value);
	// 			if ($appvlCondition->mas_condition_field_id == $conditionfields[0]['id'] && (float)$conditionfields[0]['value'] . $operatorData->value . (float)$appvlCondition->value) {
	// 				// dd($appvlCondition);
	// 				// based on this conditions check get heirarchy / auto approval / single user
	// 				if($appvlCondition->approval_option == HIERARCHICAL_APPVL_OPTION){
	// 					//get matching hierarchy and its level
	// 					$systemHierarchy = SystemHierarchy::with(['hierarchyLevels' => function($query) {
	// 														$query->whereStatus(1)->orderBy('sequence');
	// 													}])
	// 													->where('id', $appvlCondition->system_hierarchy_id)->first();
	// 					// Get the numeric level of `max_level_id` from `hierarchyLevels`
	// 					$maxLevel = $systemHierarchy->hierarchyLevels->firstWhere('id', $appvlCondition->max_level_id);
	// 					//get the level below max level
	// 					$levelsBelowOrEqualMax = $systemHierarchy->hierarchyLevels
	// 						->filter(function ($level) use ($maxLevel) {
	// 							return $level->sequence <= $maxLevel->sequence;
	// 						})
	// 						->sortBy('sequence')
	// 						->values();
	// 					// next level is level where the application will be forwarded
	// 					$nextLevel = $levelsBelowOrEqualMax->first();

	// 					$approverDetail = $this->getApproverDetail($nextLevel);
	// 					return ['max_level_id' => $maxLevel->id, 'next_level' => $nextLevel, 'approver_details' => $approverDetail, 'hierarchy_id' => $systemHierarchy->id, 'approval_option' => HIERARCHICAL_APPVL_OPTION, 'application_status' => 1];

	// 				}else if($appvlCondition->approval_option == SINGLE_USER_APPVL_OPTION){ // then it will be approved as soon as appvl_emp_id approve it.
	// 					// $approverDetail = $this->getApproverDetail($appvlCondition->appvl_employee_id);
	// 					$approverDetail['user_with_approving_role'] = User::where('id', $appvlCondition->appvl_employee_id)->first();

	// 					return ['max_level_id' => null, 'next_level' => null, 'approver_details' => $approverDetail, 'hierarchy_id' => null, 'approval_option' => SINGLE_USER_APPVL_OPTION, 'application_status' => 1];
	// 				}else{ //auto approval option this also will be approved in level 1 it self
	// 					return ['max_level_id' => null, 'next_level' => null, 'approver_details' => null, 'hierarchy_id' => null, 'approval_option' => AUTO_APPVL_OPTION, 'application_status' => 3];
	// 				}
	// 			}
	// 		}
	// 	}
	// 	//if lowest level not found
	// 	return null;
	// }

	public function getApproverByHierarchy($approvableId, $approvableType, $conditionfields)
	{
		$approvalRule = MasApprovalRule::with('approvalConditions')
			->where('approvable_id', $approvableId)
			->where('approvable_type', $approvableType)
			->whereIsActive(1)
			->first();
		if (!$approvalRule) {
			return [];
		}

		if ($approvalRule->approvalConditions && !empty($conditionfields)) {
			foreach ($approvalRule->approvalConditions as $appvlCondition) {
				// Fetch operator symbol
				$operatorData = MasApprovalRuleConditionOperator::find($appvlCondition->operator_id);
				if (!$operatorData) {
					continue; // Skip if operator not found
				}

				$operatorSymbol = $operatorData->value;

				// Match condition field and evaluate condition
				if ($appvlCondition->mas_condition_field_id == $conditionfields[0]['id']) {
					$conditionValue = !$conditionfields[0]['has_employee_field'] ? (float)$conditionfields[0]['value'] : $conditionfields[0]['value'];
					// Dynamically evaluate the condition
					if ($this->evaluateCondition(!$conditionfields[0]['has_employee_field'] ? (float)$conditionValue : $conditionValue, $operatorSymbol, !$conditionfields[0]['has_employee_field'] ? (float)$appvlCondition->value : $appvlCondition->value)) {
						if ($appvlCondition->approval_option == HIERARCHICAL_APPVL_OPTION) {
							
							$systemHierarchy = SystemHierarchy::with(['hierarchyLevels' => function ($query) {
								$query->whereStatus(1)->orderBy('sequence');
							}])->find($appvlCondition->system_hierarchy_id);

							if (!$systemHierarchy) {
								continue; // Skip if hierarchy not found
							}

							// Determine levels up to max_level_id
							$maxLevel = $systemHierarchy->hierarchyLevels->firstWhere('id', $appvlCondition->max_level_id);
							$levelsUpToMax = $systemHierarchy->hierarchyLevels
								->filter(fn($level) => $level->sequence <= $maxLevel->sequence)
								->sortBy('sequence')
								->values();

							$nextLevel = $levelsUpToMax->first();

							$approverDetail = $this->getApproverDetail($nextLevel);

							return [
								'max_level_id' => $maxLevel->id,
								'next_level' => $nextLevel,
								'approver_details' => $approverDetail,
								'hierarchy_id' => $systemHierarchy->id,
								'approval_option' => HIERARCHICAL_APPVL_OPTION,
								'application_status' => 1
							];
						} elseif ($appvlCondition->approval_option == SINGLE_USER_APPVL_OPTION) {
							$approverDetail['user_with_approving_role'] = User::find($appvlCondition->appvl_employee_id);

							return [
								'max_level_id' => null,
								'next_level' => null,
								'approver_details' => $approverDetail,
								'hierarchy_id' => null,
								'approval_option' => SINGLE_USER_APPVL_OPTION,
								'application_status' => 1
							];
						} else { // Auto approval
							return [
								'max_level_id' => null,
								'next_level' => null,
								'approver_details' => null,
								'hierarchy_id' => null,
								'approval_option' => AUTO_APPVL_OPTION,
								'application_status' => 3
							];
						}
					}
				}
			}
		}

		return null;
	}

	/**
	 * Dynamically evaluates a condition.
	 *
	 * @param mixed $value1
	 * @param string $operator
	 * @param mixed $value2
	 * @return bool
	 */
	private function evaluateCondition($value1, $operator, $value2)
	{
		switch ($operator) {
			case '<':
				return $value1 < $value2;
			case '<=':
				return $value1 <= $value2;
			case '=':
			case '==':
				return $value1 == $value2;
			case '>':
				return $value1 > $value2;
			case '>=':
				return $value1 >= $value2;
			default:
				return false;
		}
	}


	// after completion of applying and forwarding to user based on approval options and
	// using other parameters then we no need to check for those parameter can do directly with the help of application_histories table
	public function applicationForwardedTo($id, $applicationType)
	{
		$applicationHistory = ApplicationHistory::where('application_type', $applicationType)->where('application_id', $id)->where('approver_emp_id', auth()->user()->id)->first();
		if ($applicationHistory && $applicationHistory->approval_option == HIERARCHICAL_APPVL_OPTION) {
			$systemHierarchy = SystemHierarchy::with(['hierarchyLevels' => function ($query) {
				$query->whereStatus(1)->orderBy('sequence');
			}])
				->where('id', $applicationHistory->hierarchy_id)->first();

			if ($systemHierarchy) {
				$currentLevel = $applicationHistory->next_level_id;
				$currentLevelSequence = $systemHierarchy->hierarchyLevels
					->where('id', $currentLevel)
					->first()
					->sequence ?? null;
				// Find the next level based on the sequence after comparing with max_level_id
				$nextLevel = null;
				if($currentLevel < $applicationHistory->max_level_id){
					$nextLevel = $systemHierarchy->hierarchyLevels
						->where('sequence', $currentLevelSequence + 1)
						->first();
				}
			}
			if ($nextLevel) {
				$approverDetail = $this->getApproverDetail($nextLevel);
				return ['next_level' => $nextLevel, 'approver_details' => $approverDetail, 'application_status' => null];
			} else {
				// return status or sth to indicate application has reached its maximum level

				return ['application_status' => 'max_level_reached'];
			}
		} elseif ($applicationHistory && $applicationHistory->approval_option == SINGLE_USER_APPVL_OPTION) {
			return ['application_status' => 3];
		}
	}

	private function getApproverDetail($nextLevel)
	{ //if next level donot have has_employee_field
		$approvingAuthorityRoleId = ApprovingAuthority::where('id', $nextLevel->approving_authority_id)->pluck('role_id')[0];
		//incase if there is no mas_employee_id in $next level, need to find the associated employee usingdepartment and section
		if (!$nextLevel->mas_employee_id) {
			$loggedInUserDeptIdAndSecId = MasEmployeeJob::where('mas_employee_id', auth()->user()->id)->get(['mas_department_id', 'mas_section_id'])[0];
			$userWithApprovingRole = User::whereHas('roles', function ($query) use ($approvingAuthorityRoleId) {
				$query->where('roles.id', $approvingAuthorityRoleId);
			})
				->whereHas('empJob', function ($query) use ($loggedInUserDeptIdAndSecId) {
					$query->where('mas_department_id', $loggedInUserDeptIdAndSecId->mas_department_id)
					// 	->where('mas_section_id', $loggedInUserDeptIdAndSecId->mas_section_id);
					->where(function ($query) use ($loggedInUserDeptIdAndSecId) {
						$query->where('mas_section_id', $loggedInUserDeptIdAndSecId->mas_section_id)
							  ->orWhereNull('mas_section_id');
					});
				})
				->first();
		} else {
			$userWithApprovingRole = User::whereHas('roles', function ($query) use ($approvingAuthorityRoleId) {
				$query->where('roles.id', $approvingAuthorityRoleId);
			})->where('id', $nextLevel->mas_employee_id)->first();
		}

		return ['user_with_approving_role' => $userWithApprovingRole, 'approver_role_id' => $approvingAuthorityRoleId];
	}
}
