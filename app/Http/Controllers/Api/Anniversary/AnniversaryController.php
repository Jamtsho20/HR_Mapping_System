<?php

namespace App\Http\Controllers\Api\Anniversary;

use App\Http\Controllers\Controller;
use App\Models\MasRegionLocation;
use App\Models\User;
use Illuminate\Http\Request;

class AnniversaryController extends Controller
{
    // Fetch all employees
    public function getEmployees()
    {
        $employees = User::with('empJob.department', 'empJob.office', 'region') // Eager load relationships
            ->where('is_active', 1) 
            ->get();

        $employees = $employees->map(function ($employee) {
            // dd($employee);
            $office = $employee->empJob->office->name ?? 'N/A';


            $region = MasRegionLocation::where('name', $office)
                ->first()?->region->name ?? 'N/A';
            return [
                'emp_id' => $employee->username,
                'empname' => $employee->name,
                'department' => $employee->empJob->department->name ?? 'N/A',
                'office' => $employee->empJob->office->name ?? 'N/A',
                'email' => $employee->email ?? 'N/A',
                'region' => $region,
                'dzongkhag' => $employee->empPresentAddress->masDzongkhag->dzongkhag ?? 'N/A',
                'contact' => $employee->contact_number
            ];
        });

        return response()->json($employees);
    }


    public function getEmployeeById($id)
    {
        $employee = User::with('empJob.department', 'empJob.office', 'region', 'empPresentAddress') // Eager load relationships
            ->where('username', $id)
            ->first();

        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], 404);
        }
        $office = $employee->empJob->office->name ?? 'N/A';


        $region = MasRegionLocation::where('name', $office)
            ->first()?->region->name ?? 'N/A';
        $employeeData = [
            'emp_id' => $employee->username,
            'empname' => $employee->name,
            'department' => $employee->empJob->department->name ?? 'N/A',
            'office' => $employee->empJob->office->name ?? 'N/A',
            'email' => $employee->email ?? 'N/A',
            'region' => $region,
            'dzongkhag' => $employee->empPresentAddress->masDzongkhag->dzongkhag ?? 'N/A', 
            'contact' => $employee->contact_number,
        ];

        return response()->json($employeeData);
    }
}
