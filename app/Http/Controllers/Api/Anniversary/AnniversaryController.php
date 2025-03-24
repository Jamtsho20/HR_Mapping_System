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
            ->get();

        // Transform employees if needed (e.g., to make sure all necessary fields are returned)
        $employees = $employees->map(function ($employee) {
            //dd($employee);
            $office = $employee->empJob->office->name ?? 'N/A';


            $region = MasRegionLocation::where('name', $office)
                ->first()?->region->name ?? 'N/A';
            return [
                'emp_id' => $employee->username,
                'empname' => $employee->name,
                'department' => $employee->empJob->department->name ?? 'N/A',
                'office' => $employee->empJob->office->name ?? 'N/A',
                'region' => $region,
                'dzongkhag' => $employee->empPresentAddress->masDzongkhag->dzongkhag ?? 'N/A', // assuming dzongkhag is in PresentAddress
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
        // Transform the employee data for response
        $employeeData = [
            'emp_id' => $employee->username,
            'empname' => $employee->name,
            'department' => $employee->empJob->department->name ?? 'N/A',
           
            'office' => $employee->empJob->office->name ?? 'N/A',
            'region' => $region,
            'dzongkhag' => $employee->empPresentAddress->masDzongkhag->dzongkhag ?? 'N/A', // Assuming it's in PresentAddress
        ];

        return response()->json($employeeData);
    }
}
