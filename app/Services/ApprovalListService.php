<?php

namespace App\Services;

use App\Models\ApplicationHistory;
use App\Models\User;

class ApprovalListService {

    public function getList($applicationType, $loggedInUserId){
        $roleId = User::with('roles')->where('id', $loggedInUserId)->first();
        dd($roleId);
        // $list = ApplicationHistory::where('application_type', $applicationType)->where('')
    }
}