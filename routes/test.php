<?php

use App\Models\User;
use App\Models\MasPayHead;
use App\Models\MasEmployeeJob;
use App\Models\SifaRegistration;
use App\Services\PayrollService;
use App\Models\EmployeeAttendance;
use App\Models\EmployeeSalarySaving;
use App\Models\EmployeeAttendanceDetail;
use App\Http\Controllers\Api\SAP\ApiController;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('debug', function () {
    $sap = new ApiController();
    $pay = new PayrollService();
    // dd(Hash::make('bccl@2025$'));

    $employees = User::active()->completed()->whereKey(260)->get();
    $userId = 185;
    $payHeads = MasPayHead::orderBy("Name")->get();

    $salarySavingDeduction = EmployeeSalarySaving::whereEmployeeId($employee->id)->whereRaw("pay_head_id = ?", [$payHeadId])->sum('amount');

    // foreach ($employees as $employee) {
    //     $durationOfService = $employee->durationOfService();
    //     $employeeJob = MasEmployeeJob::whereMasEmployeeId($employee->id)->first();
    //     $sifaMember = SifaRegistration::whereMasEmployeeId($employee->id)->whereIsRegistered(1)->whereStatus(SIFA_APPROVED)->first();

    //     $employeeVariableValues = [];
    //     $employeeVariableValues['grade'] = $employee->empJob->grade->name;
    //     $employeeVariableValues['gradeStep'] = $employee->empJob->gradeStep->name;
    //     $employeeVariableValues['yearsInService'] = $durationOfService['yearsOfService'];
    //     $employeeVariableValues['monthsInService'] = $durationOfService['monthsOfService'];
    //     $employeeVariableValues['yearsSinceRegularization'] = $durationOfService['years'];
    //     $employeeVariableValues['monthsSinceRegularization'] = $durationOfService['months'];
    //     $employeeVariableValues['employmentType'] = $employeeJob->empType->id;
    //     $employeeVariableValues['sifaMember'] = $sifaMember ? 1 : 0;

    //     // Attendance for Basic Pay Calculation
    //     $attendance = EmployeeAttendance::whereForMonth(date('m-Y'))->first();
    //     $employeeAttendance = EmployeeAttendanceDetail::whereEmployeeId($employee->id)->whereAttendanceId($attendance->id)->first();
    //     $basicPay = $employee->empJob->basic_pay;
    //     if ($attendance && $employeeAttendance && !is_null($employeeAttendance->working_days) && $employeeAttendance->working_days > 0 && !is_null($employeeAttendance->physical_days) && $employeeAttendance->physical_days > 0) {
    //         $workingDays = $employeeAttendance->working_days;
    //         $physicalDays = $employeeAttendance->physical_days;

    //         $basicPay = round(($basicPay / $workingDays) * $physicalDays, 0);
    //     }
    //     $employeeVariableValues['basicPay'] = $basicPay;

    //     dd($employeeVariableValues);
    // }


    $paySlipDetailViews = DB::table("pay_slip_detail_views")->get();
    foreach ($paySlipDetailViews as $paySlipDetailView) {
        $allowanceTotal = $deductionTotal = 0;

        // Attendance for Basic Pay Calculation
        $attendance = EmployeeAttendance::whereForMonth(date('m-Y'))->first();
        $employeeAttendance = EmployeeAttendanceDetail::whereEmployeeId($paySlipDetailView->mas_employee_id)->whereAttendanceId($attendance->id)->first();
        $basicPay = $paySlipDetailView->basic_pay;

        if ($attendance && $employeeAttendance && !is_null($employeeAttendance->working_days) && $employeeAttendance->working_days > 0 && !is_null($employeeAttendance->physical_days) && $employeeAttendance->physical_days > 0) {
            $workingDays = $employeeAttendance->working_days;
            $physicalDays = $employeeAttendance->physical_days;

            $basicPay = round(($basicPay / $workingDays) * $physicalDays, 0);
        }



        foreach ($payHeads as $payHead) {
            $columnName = str_replace(" ", "_", $payHead->name);
            $payHeadType = (int) $payHead->payhead_type;
            if ($payHeadType === 1) {
                $allowanceTotal += $paySlipDetailView->$columnName;
            } else {
                $deductionTotal += $paySlipDetailView->$columnName;
            }
        }
        // $grossPay = $paySlipDetailView->basic_pay + $allowanceTotal;
        $grossPay = $basicPay + $allowanceTotal;
        $netPay = $grossPay - $deductionTotal;

        if ($paySlipDetailView->mas_employee_id == 260) {
            dd($grossPay, $basicPay, $netPay);
        }
        // DB::table("pay_slip_detail_views")
        //     ->whereRaw("id = ?", [$paySlipDetailView->id])
        //     ->update(['gross_pay' => $grossPay, 'net_pay' => $netPay]);
    }

    // $forMonth = '2024-12-01';
    // $departments = App\Models\MasDepartment::pluck('code', 'id');

    // foreach($departments as $key => $value) {
    //     $entries = App\Models\PaySlipSummary::where('for_month', $forMonth)
    //     // ->where('mas_department_id', $key)
    //     ->where('mas_department_id', 7)
    //         ->get();

    //     $journalLines = [];

    //         foreach ($entries as $data) {
    //             $amount = $data->amount;
    //             $costingCode2 = $data->department_code;
    //             $accountCode = $data->general_ledger_code;
    //             $accountCode2 = UNPAID_SALARY_STAFF;
    //             $lineMemo = $data->pay_type ?? "Salary Entry";
    //             $isCredit = $data->payhead_type === 1;

    //             if ($isCredit) {
    //                 // For Allowances (Credit → Debit)
    //                 $journalLines[] = [
    //                     "AccountCode" => $accountCode,
    //                     "CostingCode2" => $costingCode2,
    //                     "Credit" => 0,
    //                     "Debit" => $amount,
    //                     "LineMemo" => $lineMemo
    //                 ];
    //             } else {
    //                 if ($data->payhead_id === 16) {
    //                     $shortName = DB::select('SELECT username FROM users WHERE id = ?', [$data->employee_id]);

    //                     dd($shortName);
    //                     $journalLines[] = [
    //                         "ShortCode" => $shortName,
    //                         "CostingCode2" => $costingCode2,
    //                         "Credit" => 0,
    //                         "Debit" => $amount,
    //                         "LineMemo" => $lineMemo
    //                     ];
    //                 }
    //                 // For Deductions (Debit → Credit)
    //                 $journalLines[] = [
    //                     "AccountCode" => $accountCode,
    //                     "CostingCode2" => $costingCode2,
    //                     "Credit" => 0,
    //                     "Debit" => $amount,
    //                     "LineMemo" => $lineMemo
    //                 ];
    //                 // $journalLines[] = [
    //                 //     "AccountCode" => $accountCode2,
    //                 //     "CostingCode2" => $costingCode2,
    //                 //     "Credit" => $amount,
    //                 //     "Debit" => 0,
    //                 //     "LineMemo" => "Un-Paid Salary (Staff)"
    //                 // ];
    //             }
    //         }

    //         return $journalLines;
    // }


    // $payslip = PaySlip::first();
    // return $pay->generateAndMailPaySlip($payslip, 2);


    dd($pay->checkFormulaValidity(
        "IF ([EMPLOYMENT_TYPE] == 2 || [EMPLOYMENT_TYPE] == 4 || [EMPLOYMENT_TYPE] == 5)
        IF ([BASIC_PAY] <= 15999)
        THEN (200)
        ENDIF
        IF ([BASIC_PAY] >= 16000 & [BASIC_PAY] <= 29999)
        THEN (300)
        ENDIF
        IF ([BASIC_PAY] >= 30000 & [BASIC_PAY] <= 59999)
        THEN (400)
        ENDIF
        IF ([BASIC_PAY] >= 60000)
        THEN (500)
        ENDIF
        ELSE
        THEN (0)
        ENDIF"
    ));

    dd($pay->checkFormulaValidity(
        "IF ([EMPLOYMENT_TYPE] == 2)
THEN ([BASIC_PAY] * 0.15)
ELSEIF ([EMPLOYMENT_TYPE] == 4)
THEN ([BASIC_PAY] * 0.15)
ELSEIF ([EMPLOYMENT_TYPE] == 6)
THEN ([BASIC_PAY] * 0.05)
ELSEIF ([EMPLOYMENT_TYPE] == 7)
THEN ([BASIC_PAY] * 0.05)
ELSE
THEN 0
ENDIF"
    ));
});

// private function finalizeDetail($detail)
    // {
    //     if ($detail->status === 1) {
    //         $employee = $detail->employee;
    //         if (!$employee) {
    //             return redirect()->route('annual-increment.index')->with('msg_error', 'Employee not found');
    //         }

    //         $empJob = $employee->empJob;

    //         if (!$empJob) {
    //             return redirect()->route('annual-increment.index')->with('msg_error', 'Job details for ' . $employee->name . ' not found.');
    //         }

    //         $empJob->basic_pay = $empJob->basic_pay + $empJob->gradeStep->increment;
    //         $empJob->save();
    //     }
    // }
