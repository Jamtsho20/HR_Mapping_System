<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\AnnualIncrement;
use App\Models\AnnualIncrementDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AnnualIncrementController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:payroll/annual-increment,view')->only('index');
        $this->middleware('permission:payroll/annual-increment,create')->only('store');
        $this->middleware('permission:payroll/annual-increment,edit')->only('update');
        $this->middleware('permission:payroll/annual-increment,delete')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $year = date("Y");
        $month = date("m");
        $employees = User::whereRaw("MONTH(date_of_appointment) = ?", [$month])
            ->whereHas('empJob', function ($query) {
                $query->where('mas_employment_type_id', '<>', 8);
            })
            ->with(['empJob'])
            ->get();

        $increments = AnnualIncrement::all();
        $annualIncrement = AnnualIncrement::whereForMonth("$year-$month-01")->first();
        if (!$annualIncrement) {
            $annualIncrement = AnnualIncrement::create([
                "for_month" => "$year-$month-01",
                "status" => 0,
            ]);
        }

        foreach ($employees as $employee) {
            $employeeHasRecord = AnnualIncrementDetail::whereAnnualIncrementId($annualIncrement->id)->whereMasEmployeeId($employee->id)->count();
            $executive = $employee->empJob->grade?->id == 1;
            $executiveBasic = $employee->empJob->basic_pay * (5/100);

            if (!$employeeHasRecord) {
                $empJob = $employee->empJob;
                if ($empJob) {
                    AnnualIncrementDetail::create([
                        'annual_increment_id' => $annualIncrement->id,
                        'mas_employee_id' => $employee->id,
                        'amount' => $executive == 1 ? $executiveBasic : $employee->empJob->gradeStep->increment,
                        'status' => 0,
                    ]);
                }
            }
        }

        return view('payroll.annual-increments.index', compact('increments', 'privileges'));
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
        $annualIncrement = AnnualIncrement::whereId($id)->first();
        $details = $annualIncrement->incrementDetails()->paginate(config('global.pagination'))->withQueryString()->setPath(url('payroll/annual-increment/*'));;

        return view('payroll.annual-increments.show', compact('annualIncrement', 'details'));
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
            $record = AnnualIncrementDetail::findOrFail($request->id);

            if (!$record) {
                return response()->json(['success' => false, 'message' => 'Record not found'], 404);
            }
            DB::beginTransaction();

            $record->status = $request->status;
            $record->save();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Increment status updated successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating increment status: ' . $e->getMessage());

            return response()->json(['success' => false, 'message' => 'An error occurred while updating the increment status.']);
        }
    }

    public function updateRemarks(Request $request)
    {
        try {
            $record = AnnualIncrementDetail::find($request->id);

            if (!$record) {
                return response()->json(['success' => false, 'message' => 'Record not found'], 404);
            }
            DB::beginTransaction();

            $record->remarks = $request->remarks;
            $record->save();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Increment Remarks updated successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating increment remarks: ' . $e->getMessage());

            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function finalizeAnnualIncrement($id)
    {
        try {
            $record = AnnualIncrement::find($id);

            if (!$record) {
                return redirect()->route('annual-increment.index')->with('msg_error', 'Record not found.');
            }

            DB::beginTransaction();

            $record->status = 2;
            $record->save();

            $details = $record->incrementDetails()->whereRaw("coalesce(status,0) = 1")->get();

            foreach ($details as $detail) {
                $this->finalizeDetail($detail);
            }

            DB::commit();

            return redirect()->route('annual-increment.index')->with('msg_success', 'Annual increment finalized successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error finalizing the annual increment: ' . $e->getMessage());

            return redirect()->route('annual-increment.index')->with('msg_error', 'An error occurred while finalizing the annual increment.');
        }
    }

    private function finalizeDetail($detail)
    {
        if ($detail->status === 1) {
            $employee = $detail->employee;
            if (!$employee) {
                return redirect()->route('annual-increment.index')->with('msg_error', 'Employee not found');
            }

            $empJob = $employee->empJob;

            if (!$empJob) {
                return redirect()->route('annual-increment.index')->with('msg_error', 'Job details for ' . $employee->name . ' not found.');
            }

            $empJob->basic_pay = $empJob->basic_pay + $empJob->gradeStep->increment;
            $empJob->save();
        }
    }
}
