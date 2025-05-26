<?php

namespace App\Http\Controllers\Advance;

use App\Http\Controllers\Controller;
use App\Models\AdvanceApplication;
use App\Models\MasAdvanceTypes;
use App\Services\ApplicationHistoriesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdvanceSifaLoanController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:advance-loan/sifa-disburse,view')->only('index');
        $this->middleware('permission:advance-loan/sifa-disburse,create')->only('store');
        $this->middleware('permission:advance-loan/sifa-disburse,edit')->only('update');
        $this->middleware('permission:advance-loan/sifa-disburse,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
        // Fetch advance applications where type_id = 7 and status = 3
        $applications = AdvanceApplication::where('type_id', 7)
            ->where('status', 3)
            ->paginate(30);
        return view('advance-loan.sifa-disburse.index', compact('privileges', 'applications'));
    }
    public function show($id)
    {
        $advance = AdvanceApplication::with('advanceType')->findOrFail($id);
        $empDetails = empDetails($advance->created_by);
        $advanceTypes = MasAdvanceTypes::all();
        $advance->mode_of_travel_name = $this->travelModes[$advance->mode_of_travel] ?? 'Unknown';

        $approvalDetail = getApplicationLogs(\App\Models\AdvanceApplication::class, $advance->id);

        $employeeId = loggedInUser();
        $lastMonth = now()->subMonth()->startOfMonth()->format('Y-m-d');

        $netPay = DB::table('final_pay_slips')
            ->where('mas_employee_id', $employeeId)
            ->where('for_month', $lastMonth)
            ->value(DB::raw("JSON_UNQUOTE(JSON_EXTRACT(details, '$.net_pay'))"));

        $netPay = floatval($netPay); // cast safely
        $eligibilityAmount = min($netPay * 3, 100000);
        $advance->netPay = $netPay;
        return view('advance-loan.sifa-disburse.show', compact('advance', 'advanceTypes', 'approvalDetail', 'empDetails', 'eligibilityAmount', 'netPay', 'lastMonth'));
    }

    // public function update(Request $request, $id)
    // {

    //     $advanceApplication = AdvanceApplication::findOrFail($id);
    //     $advanceApplication->status = $request->status;
    //     $advanceApplication->save();
    // }
    public function disburse($id)
    {
        $application = AdvanceApplication::findOrFail($id);

        if ($application->status != 3) {
            return redirect()->back()->with('error', 'Only applications with status 3 can be disbursed.');
        }

        $application->status = 4;
        $application->save();

        return redirect()->route('sifa-disburse.show', $id)
            ->with('success', 'Application successfully disbursed.');
    }
}
