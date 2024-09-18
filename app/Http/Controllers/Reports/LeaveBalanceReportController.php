<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LeaveBalanceReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:report/leave-balance-report,view')->only('index');
        $this->middleware('permission:report/leave-balance-report,create')->only('store');
        $this->middleware('permission:report/leave-balance-report,edit')->only('update');
        $this->middleware('permission:report/leave-balance-report,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
               
        return view('report.leave-balance-report.index', compact( 'privileges'));
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
