<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\AdvanceApplication;
use App\Models\MasDepartment;
use App\Models\MasSection;
use Illuminate\Http\Request;

class AdvanceLoanReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:report/advance-loan-report,view')->only('index');
        $this->middleware('permission:report/advance-loan-report,create')->only('store');
        $this->middleware('permission:report/advance-loan-report,edit')->only('update');
        $this->middleware('permission:report/advance-loan-report,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $employeeLists = employeeList();
 
        $departments = MasDepartment::select('name', 'id')->get();
        $sections = MasSection::select('name', 'id')->get();
        $advanceReports=AdvanceApplication::paginate(30)->withQueryString();
       

        return view('report.advance-loan-report.index', compact('privileges', 'employeeLists', 'departments', 'sections', 'advanceReports'));
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
