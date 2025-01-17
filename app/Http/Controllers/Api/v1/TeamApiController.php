<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\MasEmployeeJob;
use App\Models\MasSection;
use App\Models\User;
use Illuminate\Http\Request;

class TeamApiController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Retrieve authenticated user's roles
            $roles = auth()->user()->roles; // Eager-loaded roles collection
            $roleSelect = $roles->whereIn('id', [DEPARTMENT_HEAD, IMMEDIATE_HEAD])->first();

            // Fetch authenticated user's job details
            $userJob = auth()->user()->empJob;
            if (!$userJob) {
                return response()->json([
                    'success' => false,
                    'message' => 'User job information is missing.',
                ], 403);
            }

            // Determine filter column and value based on role
            $filterColumn = $roleSelect && $roleSelect->id == DEPARTMENT_HEAD ? 'mas_department_id' : 'mas_section_id';
            $filterValue = $filterColumn === 'mas_department_id' ? $userJob->mas_department_id : $userJob->mas_section_id;

            // Retrieve query parameters for filtering
            $filters = $request->only(['section_id', 'name', 'employee_id']);
            $sections = MasSection::orderBy('name')->where('mas_department_id', auth()->user()->empJob->department->id)->get(['id', 'name']);
            // Build the query
            $teams = User::join('mas_employee_jobs', 'mas_employees.id', '=', 'mas_employee_jobs.mas_employee_id')
                ->join('mas_sections', 'mas_sections.id', '=', 'mas_employee_jobs.mas_section_id')
                ->select(
                    'mas_employees.name as employee_name',
                    'mas_employees.contact_number',
                    'mas_employees.email',
                    'mas_employees.username',
                    'mas_employee_jobs.mas_section_id',
                    'mas_sections.name as section_name',
                    'mas_employees.id as mas_employee_id' // Ensure employee ID is selected
                )
                ->where('mas_employee_jobs.' . $filterColumn, $filterValue)
                ->where('mas_employees.id', '!=', auth()->id()) // Exclude the logged-in user
                ->when(!empty($filters['mas_section_id']), function ($query) use ($filters) {
                    $query->where('mas_employee_jobs.mas_section_id', $filters['section_id']);
                })
                ->when(!empty($filters['name']), function ($query) use ($filters) {
                    $query->where('mas_employees.name', 'LIKE', '%' . $filters['name'] . '%');
                })
                ->when(!empty($filters['employee_id']), function ($query) use ($filters) {
                    $query->where('username', 'LIKE', '%' . $filters['employee_id']); // Ensure it is filtering by employee ID
                })
                ->groupBy(
                    'mas_sections.name',
                    'mas_employee_jobs.mas_section_id',
                    'mas_employees.name',
                    'mas_employees.contact_number',
                    'mas_employees.email',
                    'mas_employees.username',
                    'mas_employees.id' // Add group by for the employee ID
                )
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Teams fetched successfully.',
                'sections' => $sections,
                'data' => $teams,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }

    }


}
