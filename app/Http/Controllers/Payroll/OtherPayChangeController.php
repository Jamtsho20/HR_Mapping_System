<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\MasGradeStep;
use App\Models\OtherPayChange;
use App\Models\OtherPayChangeDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OtherPayChangeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:payroll/other-pay-changes,view')->only('index');
        $this->middleware('permission:payroll/other-pay-changes,create')->only('store');
        $this->middleware('permission:payroll/other-pay-changes,edit')->only('update', 'addPayChangeDetail', 'finalizePayChange');
        $this->middleware('permission:payroll/other-pay-changes,delete')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $payChanges = OtherPayChange::filter($request)->orderBy('for_month')->paginate(30);

        return view('payroll.other-pay-changes.index', compact('privileges', 'payChanges'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('payroll.other-pay-changes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'for_month' => 'required|date_format:Y-m',
        ]);

        try {
            $payChange = new OtherPayChange();
            $payChange->for_month = $request->for_month;
            $payChange->status = 1;
            $payChange->save();

            $month = Carbon::parse($payChange->for_month)->format('F Y');

            return redirect()->route('other-pay-changes.index')->with('msg_success', 'Pay Changes for the month of ' . $month . ' has been created successfully');

        } catch (\Exception $e) {
            Log::error('Error creating pay changes: ' . $e->getMessage());

            return redirect()->back()->withInput()->with('msg_error', 'An error occurred while creating the pay changes. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $employees = User::filter($request)->select(['id', 'name', 'employee_id'])->get();
        $gradeSteps = MasGradeStep::select(['id', 'name'])->get();

        $payChange = OtherPayChange::whereId($id)->first();
        $details = $payChange->details()->paginate(30)->withQueryString();

        return view('payroll.other-pay-changes.show', compact('payChange', 'details', 'employees', 'gradeSteps'));
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
        $request->validate([
            'for_month' => 'required|date_format:Y-m',
        ]);

        try {
            $payChange = OtherPayChange::findOrFail($id);
            $payChange->for_month = $request->for_month;
            $payChange->save();

            $month = Carbon::parse($payChange->for_month)->format('F Y');

            return redirect()->route('other-pay-changes.show', $id)->with('msg_success', 'Payslip for the month of ' . $month . ' has been updated successfully');
        } catch (\Exception $e) {
            Log::error('Error updating pay change: ' . $e->getMessage());

            return redirect()->back()->withInput()->with('msg_error', 'An error occurred while updating the pay change. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function calculateNewBasicPay(Request $request)
    {
        $employeeId = $request->query('employee_id');
        $gradeStepId = $request->query('grade_step_id');
        $noOfIncrements = $request->query('no_of_increments', 0);

        $employee = User::find($employeeId);
        $gradeStepNew = MasGradeStep::find($gradeStepId);
        $increment = $gradeStepNew->increment;
        $basePay = $gradeStepNew->starting_salary;
        $endingPay = $gradeStepNew->ending_salary;

        $newBasicPay = $this->calculateNewBasicPayValue($employee->basic_pay, $increment, $noOfIncrements, $basePay, $endingPay);

        return response()->json(['new_basic_pay' => $newBasicPay]);
    }

    protected function calculateNewBasicPayValue($oldBasicPay, $increment, $multiplier, $basePay, $endingPay)
    {
        $multiplier = ($multiplier === '') ? 0 : $multiplier;

        if (($basePay - $oldBasicPay) < $increment) {
            $newBasicPay = $basePay + $increment;
            while ((($newBasicPay - $oldBasicPay) < $increment) && (($newBasicPay + $increment) <= $endingPay)) {
                $newBasicPay += $increment;
            }
        } else {
            $newBasicPay = $basePay;
        }

        $newBasicPay += doubleval($multiplier) * $increment;
        return $newBasicPay;
    }
    public function addPayChangeDetail(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $employeeId = $request->mas_employee_id;
            $payHeadId = $request->mas_pay_head_id;

            $payChangeDetail = new OtherPayChangeDetail();
            $payChangeDetail->other_pay_change_id = $id;
            $payChangeDetail->mas_employee_id = $employeeId;
            $payChangeDetail->mas_grade_step_id = $request->mas_grade_step_id;
            $payChangeDetail->no_of_increments = $request->no_of_increments;
            $payChangeDetail->new_basic_pay = $request->new_basic_pay;
            $payChangeDetail->remarks = $request->remarks;
            $payChangeDetail->status = 1;
            $payChangeDetail->save();

            $employee = $payChangeDetail->employee->name;

            DB::commit();

            return redirect()->route('other-pay-changes.show', $id)->with('msg_success', 'Pay change details for ' . $employee . ' has been added successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error adding pay change detail: ' . $e->getMessage());

            return redirect()->back()->with('msg_error', 'An unexpected error occurred while adding the pay change detail.');
        }
    }

    public function toggleStatus(Request $request)
    {
        try {
            $record = OtherPayChangeDetail::findOrFail($request->id);

            if (!$record) {
                return response()->json(['success' => false, 'message' => 'Record not found'], 404);
            }
            DB::beginTransaction();

            $record->status = $request->status;
            $record->save();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Pay change status updated successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating pay change status: ' . $e->getMessage());

            return response()->json(['success' => false, 'message' => 'An error occurred while updating the pay change status.']);
        }
    }

    public function updateRemarks(Request $request)
    {
        try {
            $record = OtherPayChangeDetail::find($request->id);

            if (!$record) {
                return response()->json(['success' => false, 'message' => 'Record not found'], 404);
            }
            DB::beginTransaction();

            $record->remarks = $request->remarks;
            $record->save();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Pay change Remarks updated successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating pay change remarks: ' . $e->getMessage());

            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function finalizePayChange($id)
    {
        try {
            $record = OtherPayChange::findOrFail($id);

            DB::beginTransaction();

            $record->status = 4;
            $record->save();

            $details = $record->details()->whereRaw("coalesce(status,0) = 1")->get();

            foreach ($details as $detail) {
                $this->finalizeDetail($detail);
            }

            DB::commit();

            return redirect()->route('other-pay-changes.index')->with('msg_success', 'Pay change finalized successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error finalizing the pay change: ' . $e->getMessage());

            return redirect()->route('other-pay-changes.index')->with('msg_error', 'An error occurred while finalizing the pay change.');
        }
    }

    private function finalizeDetail($detail)
    {
        if ($detail->status === 1) {
            $employee = $detail->employee;
            if (!$employee) {
                throw new \Exception('Employee not found');
            }

            $empJob = $employee->empJob;

            if (!$empJob) {
                throw new \Exception('Job details for ' . $employee->name . ' not found.');
            }

            $empJob->basic_pay = $detail->new_basic_pay;
            $empJob->mas_grade_step_id = $detail->mas_grade_step_id;
            $empJob->save();
        }
    }
}
