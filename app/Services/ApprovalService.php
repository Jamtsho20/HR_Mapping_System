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
															$query->whereStatus(1)->orderBy('sequence');
														}])
														->where('id', $appvlCondition->system_hierarchy_id)->first();
														
						// Get the numeric level of `max_level_id` from `hierarchyLevels`
    					$maxLevel = $systemHierarchy->hierarchyLevels->firstWhere('id', $appvlCondition->max_level_id);
						//get the level below max level
						$levelsBelowOrEqualMax = $systemHierarchy->hierarchyLevels
							->filter(function ($level) use ($maxLevel) {
								return $level->sequence <= $maxLevel->sequence;
							})
							->sortBy('sequence')
							->values();
						// next level is level where the application will be forwarded
						$nextLevel = $levelsBelowOrEqualMax->first();

						$approverDetail = $this->getApproverDetail($nextLevel);
						return ['next_level' => $nextLevel, 'approver_details' => $approverDetail, 'hierarchy_id' => $systemHierarchy->id, 'approval_option' => HIERARCHICAL_APPVL_OPTION];

					}else if($appvlCondition->approval_option == SINGLE_USER_APPVL_OPTION){ // then it will be approved in level 1 it self

					}else{ //auto approval option this also will be approved in level 1 it self

					}
				}
			}
		}
		//if lowest level not found
		return null;
	}

	// after completion of applying and forwarding to user based on approval options and 
	// using other parameters then we no need to check for those parameter can do directly with the help of application_histories table
	public function applicationForwardedTo($id, $applicationType){
		$applicationHistory = ApplicationHistory::where('application_type', $applicationType)->where('application_id', $id)->where('approver_emp_id', auth()->user()->id)->first();
		if($applicationHistory && $applicationHistory->approval_option == HIERARCHICAL_APPVL_OPTION){
			$systemHierarchy = SystemHierarchy::with(['hierarchyLevels' => function($query) {
				$query->whereStatus(1)->orderBy('sequence');
			}])
			->where('id', $applicationHistory->hierarchy_id)->first();
			
			if($systemHierarchy){
				$currentLevel = $applicationHistory->level_id;
				$currentLevelSequence = $systemHierarchy->hierarchyLevels
														->where('id', $currentLevel)
														->first()
														->sequence ?? null;
				// Find the next level based on the sequence
				$nextLevel = $systemHierarchy->hierarchyLevels
					->where('sequence', $currentLevelSequence + 1)
					->first();
			}
			if($nextLevel){
				$approverDetail = $this->getApproverDetail($nextLevel);
				return ['next_level' => $nextLevel, 'approver_details' => $approverDetail];
			}else{
				// return status or sth to indicate application has reached its maximum level
				return ['status' => 'max_level_reached'];
			}
		}
	}

	private function getApproverDetail($nextLevel){
		// dd($nextLevel);
		$loggedInUserDeptIdAndSecId = MasEmployeeJob::where('mas_employee_id', auth()->user()->id)->get(['mas_department_id', 'mas_section_id'])[0];
		// dd($loggedInUserDeptIdAndSecId);
		$approvingAuthorityRoleId = ApprovingAuthority::where('id', $nextLevel->approving_authority_id)->pluck('role_id')[0];
		// dd($approvingAuthorityRoleId); 
		$userWithApprovingRole = User::whereHas('roles', function ($query) use ($approvingAuthorityRoleId) {
			$query->where('roles.id', $approvingAuthorityRoleId);
		})
		->whereHas('empJob', function ($query) use ($loggedInUserDeptIdAndSecId) {
			$query->where('mas_department_id', $loggedInUserDeptIdAndSecId->mas_department_id)
				  ->where('mas_section_id', $loggedInUserDeptIdAndSecId->mas_section_id);
		})
		->first();
		// dd($userWithApprovingRole);
		return ['user_with_approving_role' => $userWithApprovingRole, 'approver_role_id' => $approvingAuthorityRoleId];
	}
}
