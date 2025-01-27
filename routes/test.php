<?php

use App\Services\PayrollService;
use App\Http\Controllers\Api\SAP\ApiController;

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

 Route::get('/debug', function () {
    $sap = new ApiController();
    $pay = new PayrollService();

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
        ENDIF"));

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
ENDIF"));

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
