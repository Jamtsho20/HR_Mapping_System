<?php

namespace App\Services;

use App\Models\ApplicationHistory;
use App\Models\ApprovingAuthority;
use App\Models\MasApprovalRule;
use App\Models\MasApprovalRuleConditionOperator;
use App\Models\MasEmployeeJob;
use App\Models\SystemHierarchy;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ApprovalService
{
	public function getApproverByHierarchy($approvableId, $approvableType, $conditionfields)
	{
		$loggedInUserRoleId = Auth::user()->roles->collect();
		// incase if employee has this two roles then it will be different from normal hierarchy
		// Filter and collect the desired roles
		$desiredRoles = $loggedInUserRoleId->filter(function ($role) {
			return $role['id'] === IMMEDIATE_HEAD || $role['id'] === DEPARTMENT_HEAD;
		})->pluck('id')->toArray(); // Get role IDs as an array
		// $departmentHead = $loggedInUserRoleId->firstWhere('id', DEPARTMENT_HEAD);

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
							}, 'hierarchyLevels.approvingAuthority'])->find($appvlCondition->system_hierarchy_id);

							if (!$systemHierarchy) {
								continue; // Skip if hierarchy not found
							}

							// Determine levels up to max_level_id (level that has been set as max level in approver rules)
							$maxLevel = $systemHierarchy->hierarchyLevels->firstWhere('id', $appvlCondition->max_level_id);
							// Collect only the approvingAuthority data and compare with desiredRoles if there is any approver role id that is present in desiredRoles then approve using roles
							$approvingAuthorities = $systemHierarchy->hierarchyLevels->pluck('approvingAuthority');
							$matchingApprovingAuthority = $approvingAuthorities->first(function ($authority) use ($desiredRoles) {
								return in_array($authority->role_id, $desiredRoles);
							});
							
							// incase if it matches return data from here it self because hierarchy process will be customized as applying user will either be Immediate Head or Department Head
							//which exists within the hierarchy level
							if ($matchingApprovingAuthority) {
								// max level despite of max level that has been set in approver rules
								$originalLevel = $systemHierarchy->hierarchyLevels;
								return $this->approverUsingRole($matchingApprovingAuthority, $originalLevel, $maxLevel, $systemHierarchy);
							}

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
				if ($currentLevel < $applicationHistory->max_level_id) {
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


	// this function is written for those cases when Immediate Head and Department Head applies if they exist in hierarchy as approver
	private function approverUsingRole($matchingApprovingAuthority, $originalLevel, $evaluatedMaxLevel, $systemHierarchy)
	{
		$originalMaxLevel = $originalLevel->sortByDesc('sequence')->first();
		//old block of code
		// $useEvaluatedLevel = $matchingApprovingAuthority->role_id === IMMEDIATE_HEAD && $originalMaxLevel->sequence !== $evaluatedMaxLevel->sequence;
		// $nextLevel = $useEvaluatedLevel ? $evaluatedMaxLevel : $originalMaxLevel;

		// Determine the next level based on the role and available levels
		if ($matchingApprovingAuthority->role_id === IMMEDIATE_HEAD) {
			// IMMEDIATE_HEAD progresses to DEPARTMENT_HEAD by going to the next available level
			$nextLevel = $originalLevel->firstWhere('sequence', $originalMaxLevel->sequence - 1) ?? $originalMaxLevel;
		} elseif ($matchingApprovingAuthority->role_id === DEPARTMENT_HEAD) {
			// DEPARTMENT_HEAD takes the max level
			$nextLevel = $originalMaxLevel;
		} else {
			// Fallback: Use the evaluated max level
			$nextLevel = $evaluatedMaxLevel;
		}
		// dd($nextLevel);
		$approverDetail = $this->getApproverDetail($nextLevel);

		return [
			'max_level_id' => $nextLevel->id,
			'next_level' => $nextLevel,
			'approver_details' => $approverDetail,
			'hierarchy_id' => $systemHierarchy->id,
			'approval_option' => HIERARCHICAL_APPVL_OPTION,
			'application_status' => 1
		];
	}

	private function getApproverDetail($nextLevel)
	{
		//if next level donot have has_employee_field
		$approvingAuthorityRoleId = ApprovingAuthority::where('id', $nextLevel->approving_authority_id)->pluck('role_id')[0];
		// incase if $approvingAuthorityRoleId is Managing Director
		if ($approvingAuthorityRoleId == MANAGING_DIRECTOR) {
			$userWithApprovingRole = User::whereHas('roles', function ($query) use ($approvingAuthorityRoleId) {
				$query->where('roles.id', $approvingAuthorityRoleId);
			})->first();

			return [
				'user_with_approving_role' => $userWithApprovingRole,
				'approver_role_id' => $approvingAuthorityRoleId,
			];
		}
		//incase if there is no mas_employee_id in $next level, need to find the associated employee using department and section
		if (!$nextLevel->mas_employee_id) {
			$loggedInUserDeptIdAndSecId = MasEmployeeJob::where('mas_employee_id', auth()->user()->id)->get(['mas_department_id', 'mas_section_id'])[0];
			$userWithApprovingRole = User::whereHas('roles', function ($query) use ($approvingAuthorityRoleId) {
				$query->where('roles.id', $approvingAuthorityRoleId);
			})
				->whereHas('empJob', function ($query) use ($loggedInUserDeptIdAndSecId) {
					$query->where('mas_department_id', $loggedInUserDeptIdAndSecId->mas_department_id)
						//   ->where('mas_section_id', $loggedInUserDeptIdAndSecId->mas_section_id);
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
