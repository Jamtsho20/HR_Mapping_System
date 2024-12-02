<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\LeaveTravelConcession;
use App\Models\LeaveTravelConcessionDetail;
use App\Models\PaySlipDetailView;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LTCController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:payroll/ltc,view')->only('index');
        $this->middleware('permission:payroll/ltc,create')->only('store');
        $this->middleware('permission:payroll/ltc,edit')->only('update');
        $this->middleware('permission:payroll/ltc,delete')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $privileges = $request->instance();

        $year = date("Y");
        $month = 10; // Explicitly setting October here

        // Retrieve employees based on employment type and length of service
        $employees = User::with(['empJob' => function ($query) {
            $query->select('mas_employee_id', 'mas_employment_type_id');
        }])
            ->where(function ($query) {
                $query->whereHas('empJob', function ($q) {
                    $q->where('mas_employment_type_id', 2) // Probationers
                        ->whereRaw("DATEDIFF(CURDATE(), date_of_appointment) >= ?", [365 * 1.5]);
                })
                    ->orWhereHas('empJob', function ($q) {
                        $q->where('mas_employment_type_id', 1) // Regular employees
                            ->whereRaw("DATEDIFF(CURDATE(), date_of_appointment) >= ?", [365 * 1]);
                    });
            })
            ->whereRaw("MONTH(date_of_appointment) = ?", [$month])
            ->select(['id', 'name', 'date_of_appointment', 'no_probation'])
            ->get();

        $ltcs = LeaveTravelConcession::all();
        // Retrieve or create LeaveTravelConcession for the month
        $ltc = LeaveTravelConcession::firstOrCreate(
            ['for_month' => "$year-$month-01"],
            ['status' => 0]
        );

        foreach ($employees as $employee) {
            $isFirstTimeLTC = LeaveTravelConcessionDetail::whereMasEmployeeId($employee->id)->count() == 0;
            $noProbation = $employee->no_probation == 1 ? true : false;
            $employementType = $employee->empJob->mas_employment_type_id;

            $durationOfService = $employee->durationOfService();
            $monthsSinceRegularization = $durationOfService['months'];
            $monthsInService = $durationOfService['monthsOfService'];

            if ($isFirstTimeLTC) {
                if ($noProbation) { // No Probation
                    if ($employementType == 1) { // Regular
                        $eligible = $monthsInService >= 12;
                    }
                } else {
                    if ($employementType == 1) { // Regular
                        $eligible = $monthsSinceRegularization >= 18;
                    }
                }
            } else {
                $eligible = true;
            }

            if ($eligible) {
                $amount = PaySlipDetailView::whereMasEmployeeId($employee->id)->whereForMonth(Carbon::now()->subMonth()->format('Y-m-01'))->value('basic_pay');
                if (is_null($amount)) {
                    return redirect()->back()->with('msg_error', 'Error processing LTC! Salary for previous month is not processed.');
                }
                LeaveTravelConcessionDetail::firstOrCreate([
                    'ltc_id' => $ltc->id,
                    'mas_employee_id' => $employee->id,
                    'amount' => $amount,
                ]);
            }
        }

        return view('ltc.index', compact('privileges', 'ltcs'));
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
        $ltc = LeaveTravelConcession::whereId($id)->first();
        $details = $ltc->ltcDetails()->paginate(30)->withQueryString();

        return view('ltc.show', compact('ltc', 'details'));
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

    public function toggleStatus(Request $request)
    {
        try {
            $record = LeaveTravelConcessionDetail::findOrFail($request->id);

            if (!$record) {
                return response()->json(['success' => false, 'message' => 'Record not found'], 404);
            }
            DB::beginTransaction();

            $record->status = $request->status;
            $record->save();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'LTC status updated successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating LTC status: ' . $e->getMessage());

            return response()->json(['success' => false, 'message' => 'An error occurred while updating the LTC status.']);
        }
    }

    public function updateRemarks(Request $request)
    {
        try {
            $record = LeaveTravelConcessionDetail::find($request->id);

            if (!$record) {
                return response()->json(['success' => false, 'message' => 'Record not found'], 404);
            }
            DB::beginTransaction();

            $record->remarks = $request->remarks;
            $record->save();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'LTC Remarks updated successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating LTC remarks: ' . $e->getMessage());

            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function finalizeLtc($id)
    {
        try {
            $record = LeaveTravelConcession::find($id);

            if (!$record) {
                return redirect()->route('ltc.index')->with('msg_error', 'Record not found.');
            }

            DB::beginTransaction();

            $record->status = 2;
            $record->save();

            $details = $record->ltcDetails()->whereRaw("coalesce(status,0) = 1")->get();

            foreach ($details as $detail) {
                $this->finalizeDetail($detail);
            }

            DB::commit();

            return redirect()->route('ltc.index')->with('msg_success', 'LTC finalized successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error finalizing the LTC: ' . $e->getMessage());

            return redirect()->route('ltc.index')->with('msg_error', 'An error occurred while finalizing the LTC.');
        }
    }

    private function finalizeDetail($detail)
    {
        if ($detail->status === 1) {
            $employee = $detail->employee;
            if (!$employee) {
                return redirect()->route('ltc.index')->with('msg_error', 'Employee not found');
            }

            $empJob = $employee->empJob;

            if (!$empJob) {
                return redirect()->route('ltc.index')->with('msg_error', 'Job details for ' . $employee->name . ' not found.');
            }

            $empJob->basic_pay = $empJob->basic_pay + $empJob->gradeStep->increment;
            $empJob->save();
        }
    }
}
