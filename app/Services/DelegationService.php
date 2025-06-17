<?php

namespace App\Services;

use App\Models\MasEmployeeJob;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DelegationService {

    public  function delegatedRole($userId)
    {
        $today = now()->toDateString();
        // Delegated roles
        $delegatedRole = DB::table('delegations')
            ->where('delegatee_id', $userId)
            ->where('status', 1)
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->pluck('role_id')
            ->toArray();

        return $delegatedRole;
    }

    public function getDelegations($roleId)
    {
        $today = now()->toDateString();

        $delegations = DB::table('delegations')
            ->where('role_id', $roleId)
            ->where('status', 1)
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->get();

        return $delegations;
    }

    public function getDelegatee($delegatorId)
    {
        $delegations = DB::table('delegations')
            ->where('delegator_id', $delegatorId)
            ->get(['delegatee_id', 'start_date', 'end_date']);

        return $delegations;
    }

    public function getDelegateeRecords($query, $delegatee, $modelClass, $statuses)
    {
        foreach ($delegatee as $user) {
            $query->orWhereHas('audit_logs', function ($q) use ($user, $modelClass, $statuses) {
                $q->where('application_type', $modelClass)
                    ->where('action_performed_by', $user->delegatee_id)
                    ->whereIn('status', $statuses);
            });
        }
    }

    public function getDeleagteeList($roleId)
    {
        $employees = [];
        if ($roleId == DEPARTMENT_HEAD) {
            $departmentId = MasEmployeeJob::where('mas_employee_id', auth()->user()->id)->value('mas_department_id');
            $employees = User::whereHas('empJob', function ($query) use ($departmentId) {
                $query->where('mas_department_id', $departmentId);
            })
            //if they want all user from department then comment out this below query where it checks for role
            ->whereHas('roles', function ($query1) {
                $query1->where('roles.id', IMMEDIATE_HEAD); // assuming role has `name` column
            })
                ->get()->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'emp_id_name' => $user->emp_id_name, // uses accessor safely
                    ];
                });
        } else if ($roleId == MANAGING_DIRECTOR) {
            $employees = User::whereHas('roles', function ($query) {
                $query->whereIn('roles.id', [DEPARTMENT_HEAD, IMMEDIATE_HEAD]);
            })
                ->get()->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'emp_id_name' => $user->emp_id_name
                    ];
                });
        } else {

            $sectionId = MasEmployeeJob::where('mas_employee_id', auth()->user()->id)->value('mas_section_id');
            $employees = User::whereHas('empJob', function ($query) use ($sectionId) {
                $query->where('mas_section_id', $sectionId);
            })
                ->get()->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'emp_id_name' => $user->emp_id_name, // uses accessor safely
                    ];
                });
        }

        return $employees;
    }
}