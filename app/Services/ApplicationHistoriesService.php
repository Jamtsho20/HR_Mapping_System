<?php

namespace App\Services;

class ApplicationHistoriesService {

    public function saveHistory($relation, $approverByHierarchy, $remark) {
        $relation->create([
            'approval_option' => $approverByHierarchy['approval_option'],
            'hierarchy_id' => $approverByHierarchy['hierarchy_id'] ?? null,
            'max_level_id' => $approverByHierarchy['max_level_id'] ?? null,
            'next_level_id' => $approverByHierarchy['next_level']->id ?? null,
            'approver_role_id' => $approverByHierarchy['approver_details']['approver_role_id'] ?? null,
            'approver_emp_id' => $approverByHierarchy['approver_details']['user_with_approving_role']->id ?? null,
            'level_sequence' => $approverByHierarchy['next_level']->sequence ?? null,
            'status' => $approverByHierarchy['application_status'],
            'remarks' => $remark ?? null,
            'action_performed_by' => loggedInUser(),
        ]);
    }
}