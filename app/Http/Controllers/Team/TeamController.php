<?php

namespace App\Http\Controllers\Team;

use App\Http\Controllers\Controller;
use App\Models\MasEmployeeJob;
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

        // Check if the roles collection contains the desired role and fetch its details
        $roleSelect = $roles->firstWhere('id', DEPARTMENT_HEAD);

        if ($roleSelect) {
            // $teams = MasEmployeeJob::where('mas_department_id', auth()->user()->empJob->mas_department_id)->get();
            $teams = User::with('empJob')
                ->whereHas('empJob', function ($query) {
                    $query->where('mas_department_id', auth()->user()->empJob->mas_department_id);
                })
                ->get();

            // dd($teams);
        }



        return view('teams.index', compact('teams', 'privileges'));
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
