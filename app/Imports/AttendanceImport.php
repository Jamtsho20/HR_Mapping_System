<?php

namespace App\Imports;

use App\Models\EmployeeAttendanceDetail;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;

class AttendanceImport implements ToModel
{
    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Skip the header row
        if ($row[0] === 'month') { // Match the header name of the first column
            return null;
        }

        // Find the user by employee_id
        $user = User::where('employee_id', $row[1])->first();

        if (!$user) {
            return null;
        }

        // Check if the record already exists
        $recordExists = EmployeeAttendanceDetail::where('attendance_id', $this->id)
            ->where('employee_id', $user->id)
            ->exists();

        if ($recordExists) {
            return null;
        }

        return new EmployeeAttendanceDetail([
            'attendance_id' => $this->id,
            'employee_id' => $user->id,
            'physical_days' => (int) $row[2],
            'working_days' => (int) $row[3],
        ]);
    }
}
