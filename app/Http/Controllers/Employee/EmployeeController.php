<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:employee/employee-list,view')->only('index');
        $this->middleware('permission:employee/employee-list,create')->only('store');
        $this->middleware('permission:employee/employee-list,edit')->only('update');
        $this->middleware('permission:employee/employee-list,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
      
        return view('employee/employee-list.index',compact('privileges'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        return view('employee/employee-list.create');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        dd($request->all());
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

    private function savePersonalInfo(){
        
    }
}
