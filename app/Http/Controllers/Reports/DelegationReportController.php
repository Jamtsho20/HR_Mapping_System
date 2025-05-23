<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Delegation;
use Illuminate\Http\Request;

class DelegationReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:report/delegation-report,view')->only('index');
        $this->middleware('permission:report/delegation-report,create')->only('store');
        $this->middleware('permission:report/delegation-report,edit')->only('update');
        $this->middleware('permission:report/delegation-report,delete')->only('destroy');
    }

    public function index(Request $request)
    {

        $privileges = $request->instance();
        $delegations = Delegation::filter($request)->paginate(config('global.pagination'))->withQueryString();
        $employee = employeeList();

        return view('report.delegation-report.index', compact('privileges', 'employee', 'delegations'));
    }
}
