<?php

namespace App\Traits;

use App\Models\EmployeeLeave;
use App\Models\LeaveApplication;

trait UpdateLeaveBalanceTrait
{
    /**
     * Update the leave balance after leave application is approved.
     *
     * @param \App\Models\LeaveApplication $leaveApplication
     * @return bool
     */
    public function updateLeaveBalance(?LeaveApplication $leaveApplication, $leaveEncashment = null)
    {
        if($leaveEncashment != null ){
            $employeeId = $leaveEncashment->created_by;
            $noOfDays = $leaveEncashment->leave_applied_for_encashment; //aviled no of days

            // Fetch the leave balance for the employee and leave type
            $employeeLeave = EmployeeLeave::where('mas_employee_id', $employeeId)
                ->where('mas_leave_type_id', EARNED_LEAVE)
                ->first();

                if ($employeeLeave && $leaveEncashment->status == -1) {
                    $employeeLeave->leaves_availed -= $noOfDays;
                    $employeeLeave->closing_balance += $noOfDays;
                    $employeeLeave->save();
                    return;
                }

            if ($employeeLeave) {
                // Deduct the leave days from the balance
                $employeeLeave->closing_balance -= $noOfDays;
                $employeeLeave->leaves_availed += $noOfDays;

                $employeeLeave->save();
            }
        }
        // Check if the leave status is approved (status 3)
        if ($leaveApplication != null  ) { // Status 3 means 'Approved'
            $employeeId = $leaveApplication->created_by;
            $leaveTypeId = $leaveApplication->type_id;
            $noOfDays = $leaveApplication->no_of_days; //aviled no of days

            // Fetch the leave balance for the employee and leave type
            $employeeLeave = EmployeeLeave::where('mas_employee_id', $employeeId)
                ->where('mas_leave_type_id', $leaveTypeId)
                ->first();
            if ($employeeLeave && $leaveApplication->status == -1) {
                $employeeLeave->leaves_availed -= $noOfDays;
                $employeeLeave->closing_balance += $noOfDays;
                $employeeLeave->save();
                return;
            }
            if ($employeeLeave ) {
                // Deduct the leave days from the balance
                $employeeLeave->closing_balance -= $noOfDays;
                $employeeLeave->leaves_availed += $noOfDays;

                $employeeLeave->save();
            }
        }


        return false; // Leave not approved, no balance update
    }
}
