<?php

namespace App\Http\Controllers\Team;

use App\Http\Controllers\Controller;
use App\Models\MasEmployeeJob;
use App\Models\MasSection;
use App\Models\User;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:team/my-team,view')->only('index');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $roles = auth()->user()->roles; // Eager-loaded roles collection

        $roleSelect = $roles->whereIn('id', [DEPARTMENT_HEAD, IMMEDIATE_HEAD])->first();



        $sections = MasSection::orderBy('name')->where('mas_department_id', auth()->user()->empJob->department->id)->get(['id', 'name']);

        $userJob = auth()->user()->empJob;
        if (!$userJob) {
            abort(403, 'User job information is missing.');
        }

        $filterColumn = $roleSelect && $roleSelect->id == 7 ? 'mas_department_id' : 'mas_section_id';
        $filterValue = $filterColumn === 'mas_department_id' ? $userJob->mas_department_id : $userJob->mas_section_id;

        // $teams = User::with('empJob')
        //     ->whereHas('empJob', function ($query) use ($filterColumn, $filterValue) {
        //         $query->where($filterColumn, $filterValue);
        //     })

        //     ->where('id', '!=', auth()->id()) // Exclude the logged-in user
        //     ->filter($request)
        //     ->paginate(config('global.pagination'));

        $teams = User::join('mas_employee_jobs', 'mas_employees.id', '=', 'mas_employee_jobs.mas_employee_id')
            ->join('mas_sections', 'mas_sections.id', '=', 'mas_employee_jobs.mas_section_id')
            ->select('mas_employees.name as employee_name', 'mas_employees.contact_number', 'mas_employees.email', 'mas_employees.username', 'mas_employee_jobs.mas_section_id', 'mas_sections.name')
            ->where('mas_employee_jobs.' . $filterColumn, $filterValue)
            ->where('mas_employees.id', '!=', auth()->id())
            ->groupBy('mas_sections.name', 'mas_employee_jobs.mas_section_id', 'mas_employees.name', 'mas_employees.contact_number', 'mas_employees.email', 'mas_employees.username') // Include necessary columns in groupBy
            ->filter($request)
            ->paginate(config('global.pagination'));




        return view('teams.index', compact('teams', 'privileges', 'sections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
