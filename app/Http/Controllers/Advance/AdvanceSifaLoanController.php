<?php

namespace App\Http\Controllers\Advance;

use App\Http\Controllers\Controller;
use App\Models\AdvanceApplication;
use App\Models\ApplicationAuditLog;
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

    public function disburse(Request $request, $id)
    {
        //here transaction is used since on update aplication audit log also gets updtaed/create
        $disbursedStatus = 4;
        $application = AdvanceApplication::findOrFail($id);
        $auditData = ApplicationAuditLog::where('application_id', $id)->where('application_type', \App\Models\AdvanceApplication::class)->first();

        try{
            DB::beginTransaction();
            $application->update([
                'status' => $disbursedStatus,
                // 'remarks' => $request->remarks
            ]);

            ApplicationAuditLog::create([
                'application_type' => $auditData->application_type,
                'application_id' => $auditData->application_id,
                'approval_option' => $auditData->approval_option ?? null,
                'hierarchy_id' => $auditData->hierarchy_id ?? null,
                'status' => $disbursedStatus,
                'remarks' => $request->remarks ?? null,
                'action_performed_by' => auth()->user()->id,
                'edited_by' => $auditData->edited_by ?? null,
                'sap_response' => $auditData->sap_response ?? null,
            ]);
            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
            // \Log::error($e->getMessage());
            return back()->with('msg_error', 'Something went wrong. Please try again.');
        }
        return redirect()->route('sifa-disburse.index')
            ->with('success', 'SIFA Loan Payment has been successfully disbursed.');
    }
}
