<?php

namespace App\Http\Controllers\Ltc;

use App\Http\Controllers\Controller;
use App\Models\FinalPaySlip;
use App\Models\LeaveTravelConcession;
use App\Models\LeaveTravelConcessionDetail;
use App\Models\PaySlipDetailView;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LeaveTravelConcessionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ltc/ltc,view')->only('index');
        $this->middleware('permission:ltc/ltc,create')->only('store');
        $this->middleware('permission:ltc/ltc,edit')->only('update');
        $this->middleware('permission:ltc/ltc,delete')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $privileges = $request->instance();

        $year = date("Y");
        $month = Carbon::now()->month;

        // Retrieve employees based on employment type and length of service
        $employees = User::with(['empJob' => function ($query) {
            $query->select('mas_employee_id', 'mas_employment_type_id');
        }])
            ->whereIsActive(1)
            ->select(['id', 'name', 'date_of_appointment', 'no_probation'])
            ->get()
            ->filter(function ($employee) use ($month) {
                $appointmentDate = Carbon::parse($employee->date_of_appointment);
                $appointmentMonth = $appointmentDate->month;
                $serviceDays = $appointmentDate->diffInDays(Carbon::now());
                $serviceMonths = $appointmentDate->diffInMonths(Carbon::now());

                $isProbationer   = optional($employee->empJob)->mas_employment_type_id == 2;
                $isRegular       = optional($employee->empJob)->mas_employment_type_id == 1;
                $isNoProbation   = $employee->no_probation == 1;

                // 🚫 Rule 1: Probationers need at least 18 months (and only in their appointment month)
                if ($isProbationer) {
                    return $serviceDays >= (365 * 1.5) && $appointmentMonth == $month;
                }

                // 🚫 Rule 2: Regular employees need at least 12 months (and only in their appointment month)
                if ($isRegular) {
                    return $serviceDays >= 365 && $appointmentMonth == $month;
                }

                // 🚫 Rule 3: No-probation employees → eligible only in their appointment month after 12 months
                if ($isNoProbation) {
                    return $serviceMonths >= 12 && $appointmentMonth == $month;
                }

                // 🚫 Rule 4: Joined before May 2013 → only in January
                if ($appointmentDate->lt(Carbon::create(2013, 5, 1))) {
                    return $month == 1;
                }

                return false;
            });

        dd($employees);

        $ltcs = LeaveTravelConcession::all();

        // Retrieve or create LeaveTravelConcession for the month
        $ltc = LeaveTravelConcession::firstOrCreate(
            ['for_month' => "$year-$month-01"],
            ['status' => 0]
        );

        foreach ($employees as $employee) {
            $eligible = false; // reset each iteration

            $isFirstTimeLTC = LeaveTravelConcessionDetail::where('mas_employee_id', $employee->id)->count() == 0;
            $noProbation = $employee->no_probation == 1;
            $employmentType = $employee->empJob->mas_employment_type_id;
            $durationOfService = $employee->durationOfService();
            $monthsSinceRegularization = $durationOfService['months'];
            $monthsInService = $durationOfService['monthsOfService'];

            // ✅ Condition 1: Employees who joined before May 2013 → Only in January
            if (Carbon::parse($employee->date_of_appointment)->lt(Carbon::create(2013, 5, 1))) {
                if ($month == 1) {
                    $eligible = true;
                } else {
                    continue; // 🚫 Skip them outside January
                }
            }

            // ✅ Condition 2: Long-term executive employees → Only in January
            if ($employee->is_long_term_executive ?? false) {
                if ($month == 1) {
                    $eligible = true;
                } else {
                    continue; // 🚫 Skip outside January
                }
            }

            // Existing condition for first-time LTC
            if (!$eligible && $isFirstTimeLTC) {
                if ($noProbation && ($employmentType == 1 || $employmentType == 2)) {
                    $eligible = $monthsInService >= 12;
                } elseif ($employmentType == 1 || $employmentType == 2) {
                    $eligible = $monthsSinceRegularization >= 18;
                }
            }

            if ($eligible) {
                $amount = FinalPaySlip::where('mas_employee_id', $employee->id)
                    ->where('for_month', Carbon::now()->subMonth()->format('Y-m-01'))
                    ->selectRaw("JSON_VALUE(details, '$.basic_pay') as basic_pay")
                    ->value('basic_pay');

                if (is_null($amount)) {
                    continue;
                    return redirect()->back()->with('msg_error', 'Error processing LTC! Salary for previous month is not processed.');
                }

                LeaveTravelConcessionDetail::updateOrCreate(
                    [
                        'ltc_id' => $ltc->id,
                        'mas_employee_id' => $employee->id,
                    ],
                    [
                        'amount' => $amount,
                    ]
                );
            }
        }

        return view('payroll.ltc.index', compact('privileges', 'ltcs'));
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
        $details = $ltc->ltcDetails()->paginate(config('global.pagination'))->withQueryString();

        return view('payroll.ltc.show', compact('ltc', 'details'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $ltc = LeaveTravelConcession::whereId($id)->first();
        $details = $ltc->ltcDetails()->paginate(config('global.pagination'))->withQueryString();

        return view('payroll.ltc.edit', compact('ltc', 'details'));
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
