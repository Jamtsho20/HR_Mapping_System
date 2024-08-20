<?php

namespace App\Services;

use App\Models\EmployeeOvertime;
use App\Models\LoanEMIDeduction;
use App\Models\MasPayHead;
use App\Models\PaySlipDetail;
use App\Models\PaySlipDetailView;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class PayrollService
{
    public static function processPaySlip($payslip)
    {
        $paySlipId = $payslip->id;
        PaySlipDetail::whereRaw("pay_slip_id = ?", [$paySlipId])->delete();
        $employees = User::where('username', '<>', 'admin')->get();
        // $userId = DB::table("users")->whereRaw("email = ?", [auth()->user()->email])->value('id');
        $userId = 1;
        foreach ($employees as $employee) {
            $durationOfService = $employee->durationOfService();
            $employeeVariableValues = [];
            $employeeVariableValues['grade'] = $employee->empJob->grade->name;
            $employeeVariableValues['gradeStep'] = $employee->empJob->gradeStep->name;
            $employeeVariableValues['yearsInService'] = $durationOfService['yearsOfService'];
            $employeeVariableValues['monthsInService'] = $durationOfService['monthsOfService'];
            $employeeVariableValues['yearsSinceRegularization'] = $durationOfService['years'];
            $employeeVariableValues['monthsSinceRegularization'] = $durationOfService['months'];
            $basicPay = $employeeVariableValues['basicPay'] = $employee->empJob->basic_pay;
            $forMonthObject = date_create($payslip->for_month);
            $overtimeHours = EmployeeOvertime::whereMasEmployeeId($employee->id)->whereForMonth($forMonthObject->format("Y-m-01"))->value("overtime_hours");
            $hourlyWage = ($basicPay / 30) / 8;
            $employeeVariableValues['overtimeHours'] = $overtimeHours ?? 0;
            $employeeVariableValues['hourlyWage'] = $hourlyWage;
            $payScaleBasePay = $employeeVariableValues['payScaleBasePay'] = $employee->empJob->gradeStep->starting_salary;
            $employeeGradeId = $employee->empJob->gradeStep->mas_grade_id;
            $employeeGroupId = false;
            $grossPay = $basicPay;
            $payHeadsAfterGross = [];
            $allowanceComputeResult = self::computeAllowances($basicPay, $payScaleBasePay, $grossPay, $employeeGradeId, $employeeGroupId, $paySlipId, $employee, $userId, $employeeVariableValues);
            $grossPay = $employeeVariableValues['grossPay'] = $allowanceComputeResult['grossPay'];
            $payHeadsAfterGross = array_merge($payHeadsAfterGross, $allowanceComputeResult['payHeadsAfterGross']);
            $netPay = $employeeVariableValues['netPay'] = $grossPay;
            $deductionComputeResult = self::computeDeductions($basicPay, $netPay, $payScaleBasePay, $grossPay, $employeeGradeId, $employeeGroupId, $paySlipId, $employee, $userId, $employeeVariableValues);
            $netPay = $deductionComputeResult['netPay'];
            $pf = $deductionComputeResult['pf'] ?: 0;
            $payHeadsAfterGross = array_merge($payHeadsAfterGross, $deductionComputeResult['payHeadsAfterGross']);
            $pitNetPay = $employeeVariableValues['pitNetPay'] = $grossPay - $pf;
            if ($employee->employee_id == 887) {
                // dd($deductionComputeResult, $pitNetPay, $grossPay);
            }
            foreach ($payHeadsAfterGross as $payHeadAfter) {
                if ((int) $payHeadAfter['type'] === 2) {
                    $payHead = MasPayHead::whereRaw("id = ?", [$payHeadAfter['mas_pay_head_id']])->first();
                    $calculation_method = (int) $payHead->calculation_method; //1 => "Actual",2 => "Division",3 => "Slab Wise",4 => "Group Wise",5 => "Percentage",6 => "Formula", 7 => "Employee Wise"
                    $calculated_on = (int) $payHead->calculated_on; //1 => "Basic Pay",2 => "Gross Pay",3 => "Net Pay",4 => "PIT Net Pay",5 => "Lumpsum",6 => "Pay Scale Base Pay"
                    if ($calculated_on === 4) {
                        $amountToCalculateOn = $pitNetPay;
                    }
                    if ($calculated_on === 3) {
                        $amountToCalculateOn = $netPay;
                    }
                    if ($calculated_on === 2) {
                        $amountToCalculateOn = $grossPay;
                    }
                    if ($calculated_on === 1) {
                        $amountToCalculateOn = $basicPay;
                    }
                    if ($calculated_on === 6) {
                        $amountToCalculateOn = $payScaleBasePay;
                    }
                    if ($calculated_on === 5) {
                        $payHeadAmount = $payHead->amount;
                    } else {
                        $payHeadAmount = self::calculatePayHeadAmount($employee, $employeeGradeId, $employeeGroupId, $payHead->amount, $calculation_method, $payHead->id, $amountToCalculateOn, $employeeVariableValues);
                    }

                    if ($payHeadAmount !== false) {
                        $netPay -= $payHeadAmount;
                        $employeeVariableValues['netPay'] -= $payHeadAmount;
                        PaySlipDetail::create(['pay_slip_id' => $paySlipId, 'mas_employee_id' => $employee->id, 'mas_pay_head_id' => $payHead->id, 'amount' => $payHeadAmount, 'created_by' => $userId]);
                    }
                }
            }
        }

        self::populateReportTable($payslip);

        return true;
    }

    public static function computeAllowances($basicPay, $payScaleBasePay, $grossPay, $employeeGradeId, $employeeGroupId, $paySlipId, $employee, $userId, $employeeVariableValues)
    {
        $allowances = MasPayHead::whereRaw("payhead_type = 1")->get();
        $payHeadsAfterGross = []; // Initialize to avoid undefined variable issues.

        foreach ($allowances as $allowance) {
            $calculation_method = (int) $allowance->calculation_method; // 1 => "Actual", 2 => "Division", 3 => "Slab Wise", 4 => "Group Wise", 5 => "Percentage", 6 => "Formula"
            $calculated_on = (int) $allowance->calculated_on; // 1 => "Basic Pay", 2 => "Gross Pay", 3 => "Net Pay", 4 => "PIT Net Pay", 5 => "Lumpsum", 6 => "Pay Scale Base Pay"
            $allowanceAmount = false;
            $amountToCalculateOn = 0; // Default to 0 to ensure it is always defined.

            // Determine the amount to calculate on based on `calculated_on`.
            switch ($calculated_on) {
                case 1: // Basic Pay
                    $amountToCalculateOn = $basicPay;
                    break;
                case 2: // Gross Pay
                    $amountToCalculateOn = $grossPay;
                    break;
                case 6: // Pay Scale Base Pay
                    $amountToCalculateOn = $payScaleBasePay;
                    break;
                case 5: // Lumpsum (if Actual method)
                    if ($calculation_method === 1) {
                        $allowanceAmount = $allowance->amount;
                    }
                    break;
            }

            // Calculate the allowance amount if not already set.
            if ($allowanceAmount === false) {
                if (in_array($calculated_on, [2, 3, 4])) { // GROSS PAY or NET PAY or PIT NET PAY
                    $payHeadsAfterGross[] = ['type' => 1, 'mas_pay_head_id' => $allowance->id, 'calculated_on' => $calculated_on, 'calculation_method' => $calculation_method];
                } else {
                    // Calculate the allowance amount based on other conditions.
                    $allowanceAmount = self::calculatePayHeadAmount($employee, $employeeGradeId, $employeeGroupId, $allowance->amount, $calculation_method, $allowance->id, $amountToCalculateOn, $employeeVariableValues
                    );
                }
            }

            // Add the calculated allowance amount to the gross pay and create the pay slip detail.
            if ($allowanceAmount !== false) {
                $grossPay += $allowanceAmount;
                PaySlipDetail::create(['pay_slip_id' => $paySlipId, 'mas_employee_id' => $employee->id, 'mas_pay_head_id' => $allowance->id, 'amount' => $allowanceAmount, 'created_by' => $userId]);
            }
        }

        return ['grossPay' => $grossPay, 'payHeadsAfterGross' => $payHeadsAfterGross];
    }

    public static function computeDeductions($basicPay, $netPay, $payScaleBasePay, $grossPay, $employeeGradeId, $employeeGroupId, $paySlipId, $employee, $userId, $employeeVariableValues): array
    {
        $deductions = MasPayHead::whereRaw("payhead_type = 2")->get();
        $payHeadsAfterGross = []; // Initialize to avoid undefined variable issues.
        $pf = false; // Initialize PF to avoid undefined variable issues.

        foreach ($deductions as $deduction) {
            $calculation_method = (int) $deduction->calculation_method; // 1 => "Actual", 2 => "Division", 3 => "Slab Wise", 4 => "Group Wise", 5 => "Percentage", 6 => "Formula"
            $calculated_on = (int) $deduction->calculated_on; // 1 => "Basic Pay", 2 => "Gross Pay", 3 => "Net Pay", 4 => "PIT Net Pay", 5 => "Lumpsum", 6 => "Pay Scale Base Pay"
            $deductionAmount = false;
            $amountToCalculateOn = 0; // Default to 0 to ensure it is always defined.

            // Determine the amount to calculate on based on `calculated_on`.
            switch ($calculated_on) {
                case 1: // Basic Pay
                    $amountToCalculateOn = $basicPay;
                    break;
                case 2: // Gross Pay
                    $amountToCalculateOn = $grossPay;
                    break;
                case 3: // Net Pay
                    $amountToCalculateOn = $netPay;
                    break;
                case 6: // Pay Scale Base Pay
                    $amountToCalculateOn = $payScaleBasePay;
                    break;
                case 5: // Lumpsum (if Actual method)
                    if ($calculation_method === 1) {
                        $deductionAmount = $deduction->amount;
                    }
                    break;
            }

            // Calculate the deduction amount if not already set.
            if ($deductionAmount === false) {
                if (in_array($calculated_on, [2, 3, 4])) { // GROSS PAY or NET PAY or PIT NET PAY
                    $payHeadsAfterGross[] = ['type' => 2, 'mas_pay_head_id' => $deduction->id, 'calculated_on' => $calculated_on, 'calculation_method' => $calculation_method];
                } else {
                    // Calculate the deduction amount based on other conditions.
                    $deductionAmount = self::calculatePayHeadAmount($employee, $employeeGradeId, $employeeGroupId, $deduction->amount, $calculation_method, $deduction->id, $amountToCalculateOn, $employeeVariableValues);
                }
            }

            // Deduct the calculated deduction amount from the net pay and create the pay slip detail.
            if ($deductionAmount !== false) {
                if ($deduction->code === "PF") {
                    $pf = $deductionAmount; // PROVIDENT FUND AMOUNT
                }
                $netPay -= $deductionAmount;
                PaySlipDetail::create([ 'pay_slip_id' => $paySlipId, 'mas_employee_id' => $employee->id, 'mas_pay_head_id' => $deduction->id, 'amount' => $deductionAmount, 'created_by' => $userId]);
            }
        }

        return [
            'netPay' => $netPay,
            'pf' => $pf,
            'payHeadsAfterGross' => $payHeadsAfterGross,
        ];
    }

    public static function calculatePayHeadAmount($employee, $employeeGradeId, $employeeGroupId, $amount, $calculation_method, $payHeadId, $amountToCalculateOn, $employeeVariableValues)
    {
        $formulaPossibleVariables = [
            "BASIC_PAY",
            "PIT_NET_PAY",
            "NET_PAY",
            "GROSS_PAY",
            "PAY_SCALE_BASE_PAY",
            "MONTHS_IN_SERVICE",
            "YEARS_IN_SERVICE",
            "MONTHS_SINCE_REGULARISATION",
            "YEARS_SINCE_REGULARISATION",
            "OVERTIME_HOURS",
            "HOURLY_WAGE",
            "GRADE",
            "GRADE_STEP",
        ];
        $variableValueMap = [
            "BASIC_PAY" => $employeeVariableValues['basicPay'],
            "PIT_NET_PAY" => $employeeVariableValues['pitNetPay'] ?? 0,
            "NET_PAY" => $employeeVariableValues['netPay'] ?? 0,
            "GROSS_PAY" => $employeeVariableValues['grossPay'] ?? 0,
            "PAY_SCALE_BASE_PAY" => $employeeVariableValues['payScaleBasePay'],
            "YEARS_IN_SERVICE" => $employeeVariableValues['yearsInService'],
            "MONTHS_IN_SERVICE" => $employeeVariableValues['monthsInService'],
            "YEARS_SINCE_REGULARISATION" => $employeeVariableValues['yearsSinceRegularization'],
            "MONTHS_SINCE_REGULARISATION" => $employeeVariableValues['monthsSinceRegularization'],
            "OVERTIME_HOURS" => $employeeVariableValues['overtimeHours'],
            "HOURLY_WAGE" => $employeeVariableValues['hourlyWage'],
            "GRADE" => $employeeVariableValues['grade'],
            "GRADE_STEP" => $employeeVariableValues['gradeStep'],
        ];
        $calculation_method = (int) $calculation_method;
        $payHead = MasPayHead::whereRaw("id = ?", [$payHeadId])->first();
        if ($calculation_method === 1) { //ACTUAL
            return round($amount, 0);
        }
        if ($calculation_method === 2) {
            return round(($amountToCalculateOn / $amount), 0);
        }
        if ($calculation_method === 7) {
            $employeeDeduction = LoanEMIDeduction::whereMasEmployeeId($employee->id)->whereRaw("mas_pay_head_id = ? and is_paid_off <> 1 and end_date >= ?", [$payHeadId, date("Y-m-01")])->value('amount');
            return $employeeDeduction ?? 0;
        }
        if ($calculation_method === 3) {
            $slabId = $payHead->paySlab->id;
            if (trim($payHead->paySlab) !== "") {
                $max = DB::table("mas_pay_slab_details")->whereRaw("mas_pay_slab_id = ?", [$slabId])->max("pay_to");
                $outOfRange = $amountToCalculateOn > $max;
                if ($outOfRange) {
                    $amount = self::evaluateFormula($payHead->paySlab->formula, $employee, $formulaPossibleVariables, $variableValueMap);
                } else {
                    $amount = DB::table("mas_pay_slab_details")->whereRaw("mas_pay_slab_id = ? and ? >= pay_from and ? <= pay_to", [$slabId, $amountToCalculateOn, $amountToCalculateOn])->value("amount");
                }
            } else {
                $amount = DB::table("mas_pay_slab_details")->whereRaw("mas_pay_slab_id = ? and ? >= pay_from and ? <= pay_to", [$slabId, $amountToCalculateOn, $amountToCalculateOn])->value("amount");
            }
            return round($amount, 0);
        }
        if ($calculation_method === 4) {
            $proceed = false;
            $groupId = $payHead->payGroup->id;
            $payGroupApplicableOn = $payHead->payGroup->applicable_on;

            if ((int) $payGroupApplicableOn === 2) { // Employee Grade
                $group_calculation_method = DB::table("mas_pay_group_details")->whereRaw("mas_pay_group_id = ? and mas_grade_id = ?", [$groupId, $employeeGradeId])->value("calculation_method");
                $amount = DB::table("mas_pay_group_details")->whereRaw("mas_pay_group_id = ? and mas_grade_id = ?", [$groupId, $employeeGradeId])->value("amount");
                if ($amount !== null) {
                    $proceed = true;
                }
            }
            if ((int) $payGroupApplicableOn === 1) { // Employee Category
                $employeeGroupId = $payHead->payGroup->groupDetails[0]->mas_employee_group_id;
                $employeeInGroup = $employee->employeeGroupMap()->whereRaw("mas_employee_group_id = ?", [$employeeGroupId])->count();

                if ($employeeInGroup) {
                    $proceed = true;
                }
                $amount = DB::table("mas_pay_group_details")->whereRaw("mas_pay_group_id = ? and mas_employee_group_id = ?", [$groupId, $employeeGroupId])->value("amount");
                $group_calculation_method = DB::table("mas_pay_group_details")->whereRaw("mas_pay_group_id = ? and mas_employee_group_id = ?", [$groupId, $employeeGroupId])->value("calculation_method");
            }
            if ($proceed) {
                $group_calculation_method = (int) ($group_calculation_method ?? 9);
                if ($group_calculation_method === 1) { // 1 actual
                    return round($amount, 0);
                } else if ($group_calculation_method === 2) { //2 division
                    return round(($amountToCalculateOn / $amount), 0);
                } else { // 3 percentage
                    return round((($amount / 100) * $amountToCalculateOn), 0);
                }
            }
        }
        if ($calculation_method === 5) {
            return round(($amountToCalculateOn * ($amount / 100)), 0);
        }
        if ($calculation_method === 6) {
            $amount = self::evaluateFormula($payHead->formula, $employee, $formulaPossibleVariables, $variableValueMap);
//            if($payHead->id == '9a83b740-f51c-4b97-a7d9-0ee4027f0287'){
//                if($variableValueMap['OVERTIME_HOURS']>0)
//                dd($amount,$payHead->formula, $variableValueMap);
//            }
            return round($amount, 0);
        }
        return 0;
    }

    protected function checkFormulaValidity($formula): array
    {
        $formulaLines = explode("\n", $formula);
        $formulaParsed = '';

        $formulaPossibleVariables = [
            "BASIC_PAY",
            "PIT_NET_PAY",
            "NET_PAY",
            "GROSS_PAY",
            "PAY_SCALE_BASE_PAY",
            "MONTHS_IN_SERVICE",
            "YEARS_IN_SERVICE",
            "MONTHS_SINCE_REGULARISATION",
            "YEARS_SINCE_REGULARISATION",
            "OVERTIME_HOURS",
            "HOURLY_WAGE",
            "GRADE",
            "GRADE_STEP",
        ];
        $z = 1;
        $value = 0;
        foreach ($formulaLines as $formulaLine) {
            $formulaLine = trim($formulaLine);
            foreach ($formulaPossibleVariables as $formulaPossibleVariable) {
                if (str_contains($formulaLine, "[$formulaPossibleVariable]")) {
                    $formulaLine = str_replace("[$formulaPossibleVariable]", "\$z", $formulaLine);
                }
            }
            if (str_starts_with($formulaLine, "IF")) {
                $formulaLine = str_replace("IF ", "if", $formulaLine);
                $formulaLine = str_replace("IF", "if", $formulaLine);
                $formulaParsed .= "$formulaLine: ";
            } else if (str_starts_with($formulaLine, "ELSEIF")) {
                $formulaLine = str_replace("ELSEIF ", "elseif", $formulaLine);
                $formulaLine = str_replace("ELSEIF", "elseif", $formulaLine);
                $formulaParsed .= "$formulaLine: ";
            } else if (str_starts_with($formulaLine, "ELSE")) {
                $formulaLine = str_replace("ELSE ", "else", $formulaLine);
                $formulaLine = str_replace("ELSE", "else", $formulaLine);
                $formulaParsed .= "$formulaLine :";
            } else if (str_starts_with($formulaLine, "ENDIF")) {
                $formulaLine = str_replace("ENDIF", "endif", $formulaLine);
                $formulaParsed .= "$formulaLine;";
            } else if (str_starts_with($formulaLine, "THEN")) {
                $formulaLine = str_replace("THEN ", "", $formulaLine);
                $formulaLine = str_replace("THEN", "", $formulaLine);
                $formulaParsed .= "\$value = $formulaLine; ";
            } else {
                $errorMsg = "Formula Error";
                //                $errorMsg = $formulaLine;
                return ['success' => false, 'message' => $errorMsg];
            }
        }

        $formulaParsed .= " return \$value;";

        set_error_handler(function ($_errno, $errstr) {
            // Convert notice, warning, etc. to error.
            throw new Error($errstr);
        });
        try {
            $value = eval($formulaParsed);
        } catch (\Throwable $e) {
            $errorMsg = "Formula Error";
//            $errorMsg = $e->getMessage();
            return ['success' => false, 'message' => $errorMsg];
        }
        return ['success' => true];
    }

    public static function evaluateFormula($formula, $employee, $formulaPossibleVariables, $variableValueMap)
    {
        $formulaLines = explode("\n", $formula);
        $formulaParsed = '';

        $z = 1;
        $value = 0;
        foreach ($formulaLines as $formulaLine) {
            $formulaLine = trim($formulaLine);
            foreach ($formulaPossibleVariables as $formulaPossibleVariable) {
                if ($formulaPossibleVariable == "GRADE") {
                    $formulaLine = str_replace("[$formulaPossibleVariable]", "'" . $variableValueMap[$formulaPossibleVariable] . "'", $formulaLine);
                } else if ($formulaPossibleVariable == "GRADE_STEP") {
                    $formulaLine = str_replace("[$formulaPossibleVariable]", "'" . $variableValueMap[$formulaPossibleVariable] . "'", $formulaLine);
                } else if (str_contains($formulaLine, "[$formulaPossibleVariable]")) {
                    $formulaLine = str_replace("[$formulaPossibleVariable]", $variableValueMap[$formulaPossibleVariable], $formulaLine);
                }
            }
            if (str_starts_with($formulaLine, "IF")) {
                $formulaLine = str_replace("IF ", "if", $formulaLine);
                $formulaLine = str_replace("IF", "if", $formulaLine);
                $formulaParsed .= "$formulaLine: ";
            } else if (str_starts_with($formulaLine, "ELSEIF")) {
                $formulaLine = str_replace("ELSEIF ", "elseif", $formulaLine);
                $formulaLine = str_replace("ELSEIF", "elseif", $formulaLine);
                $formulaParsed .= "$formulaLine: ";
            } else if (str_starts_with($formulaLine, "ELSE")) {
                $formulaLine = str_replace("ELSE ", "else", $formulaLine);
                $formulaLine = str_replace("ELSE", "else", $formulaLine);
                $formulaParsed .= "$formulaLine :";
            } else if (str_starts_with($formulaLine, "ENDIF")) {
                $formulaLine = str_replace("ENDIF", "endif", $formulaLine);
                $formulaParsed .= "$formulaLine;";
            } else if (str_starts_with($formulaLine, "THEN")) {
                $formulaLine = str_replace("THEN ", "", $formulaLine);
                $formulaLine = str_replace("THEN", "", $formulaLine);
                $formulaParsed .= "\$value = $formulaLine; ";
            } else {
                return 0;
            }

        }

        $formulaParsed .= " return \$value;";
        try {
            $value = eval($formulaParsed);
        } catch (\ParseError $e) {
            $value = 0;
        }
        return $value;
    }

    public static function populateReportTable($payslip)
    {
        $payHeads = MasPayHead::orderBy("Name")->get();

        $insertQuerySegment = "";
        $insertQueryColumnSegment = "";
        $parameters = [];
        $month = $payslip->for_month;
        $parameters[] = $month;
        DB::statement("DROP TABLE IF EXISTS pay_slip_detail_views");
        $createQuery = "create table pay_slip_detail_views(
            `id` CHAR(36) NOT NULL,
            `for_month` DATE NOT NULL,
            `overtime_hours` DECIMAL(5,2) NULL DEFAULT NULL,
            `mas_employee_id` char(36) NOT NULL COLLATE 'utf8mb4_unicode_ci',
            `basic_pay` INT(11) NOT NULL,";
        foreach ($payHeads as $payHead) {
            $createQuery .= " `" . str_replace(" ", "_", $payHead->name) . "` VARCHAR(100) NULL DEFAULT NULL,";
            if ($insertQuerySegment !== "") {
                $insertQuerySegment .= ",";
            }
            if ($insertQueryColumnSegment !== "") {
                $insertQueryColumnSegment .= ",";
            }
            $insertQueryColumnSegment .= str_replace(" ", "_", $payHead->name);
            $insertQuerySegment .= "(select amount from pay_slip_details b where b.pay_slip_id = ? and mas_pay_head_id = ? and b.mas_employee_id = c.id)";
            $parameters[] = $payslip->id;
            $parameters[] = $payHead->id;
        }

        $createQuery .= "`net_pay` DECIMAL (20,2) NULL DEFAULT NULL,";
        $createQuery .= "`gross_pay` DECIMAL (20,2) NULL DEFAULT NULL,";
        $createQuery .= "`created_at` TIMESTAMP NULL DEFAULT NULL,";
        $createQuery .= "`updated_at` TIMESTAMP NULL DEFAULT NULL,";
        $createQuery .= "PRIMARY KEY (`id`),INDEX `pay_slip_detail_views_mas_employee_id_index` (`mas_employee_id`),CONSTRAINT `pay_slip_detail_views_mas_employee_id_foreign` FOREIGN KEY (`mas_employee_id`) REFERENCES `mas_employees` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT)";
        DB::statement($createQuery);

        $insertQuery = "INSERT INTO pay_slip_detail_views (id, mas_employee_id, basic_pay, created_at, for_month, $insertQueryColumnSegment) SELECT UUID(), c.id, d.basic_pay, NOW(), ?, $insertQuerySegment FROM mas_employees c JOIN mas_employee_jobs d ON d.mas_employee_id = c.id";

        DB::insert($insertQuery, $parameters);
        $paySlipDetailViews = DB::table("pay_slip_detail_views")->get();
        $payHeads = MasPayHead::orderBy("Name")->get();
        foreach ($paySlipDetailViews as $paySlipDetailView) {
            $allowanceTotal = $deductionTotal = 0;
            foreach ($payHeads as $payHead) {
                $columnName = str_replace(" ", "_", $payHead->name);
                $payHeadType = (int) $payHead->payhead_type;
                if ($payHeadType === 1) {
                    $allowanceTotal += $paySlipDetailView->$columnName;
                } else {
                    $deductionTotal += $paySlipDetailView->$columnName;
                }
            }
            $grossPay = $paySlipDetailView->basic_pay + $allowanceTotal;
            $netPay = $grossPay - $deductionTotal;
            DB::table("pay_slip_detail_views")->whereRaw("id = ?", [$paySlipDetailView->id])->update(['gross_pay' => $grossPay, 'net_pay' => $netPay]);
        }
    }

    public static function generateAndMailPaySlips($payslip)
    {
        $individualPayRecords = DB::table("pay_slip_detail_views")->whereForMonth($payslip->for_month)->get();
        foreach ($individualPayRecords as $individualPayRecord) {
            $fileResult = self::paySlipFileGenerate($individualPayRecord->id);
            $paySlipFile = $fileResult['file'];
            $monthFriendly = $fileResult['month'];
            $employeeName = $fileResult['employeeName'];
            // Mail::to(["sw_engineer11.sdu@tashicell.com"])->send(new PaySlipMail($paySlipFile, $employeeName, $monthFriendly));
        }
    }

    public static function paySlipFileGenerate($paySlipDetailId): array
    {
        $paySlip = PaySlipDetailView::find($paySlipDetailId);
        $employee = $paySlip->employee;
        $allowances = MasPayHead::orderBy("Name")->wherePayheadType(1)->get();
        $deductions = MasPayHead::orderBy("Name")->wherePayheadType(2)->get();

        $totalDeductions = count($deductions);
        $firstHalf = floor($totalDeductions / 2);

        $deductions1 = $deductions->splice($firstHalf);

        $employeeName = $employee->first_name . " " . $employee->middle_name . " " . $employee->last_name;
        $employeeName = str_replace("  ", " ", $employeeName);

        $data = compact('paySlip', 'employee', 'allowances', 'deductions', 'deductions1');
        $pdf = PDF::loadView('pdf_templates.payslip', $data)->setPaper('a4', 'portrait');
        $paySlipMonth = date_format(date_create($paySlip->for_month), "Y_m");
        $friendlyMonth = date_format(date_create($paySlip->for_month), "F, Y");

        $directory = storage_path('payslips');
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        $paySlipFile = $directory . '/' . str_replace(" ", "_", $employeeName . "_(" . $employee->employee_id . ")" . "_" . $paySlipMonth) . ".pdf";
        $pdf->save($paySlipFile);
        return ['file' => $paySlipFile, 'month' => $friendlyMonth, 'employeeName' => $employeeName];
    }
}
