<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\PaySlip;
use App\Models\MasPayHead;
use App\Models\PaySlipDetail;
use Illuminate\Bus\Queueable;
use App\Models\MasEmployeeJob;
use App\Models\EmployeeOvertime;
use App\Models\LoanEMIDeduction;
use App\Models\SifaRegistration;
use App\Services\PayrollService;
use App\Models\EmployeeAttendance;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Queue\SerializesModels;
use App\Models\EmployeeAttendanceDetail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;


class ProcessPaySlipJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $payslip;
    public $status;

    /**
     * Create a new job instance.
     *
     * @param  \App\Models\PaySlip  $payslip
     * @return void
     */
    public function __construct(PaySlip $payslip, $status)
    {
        $this->payslip = $payslip;
        $this->status = $status;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(PayrollService $payrollService)
    {
        $payslip = $this->payslip;
        try {
            $paySlipId = $payslip->id;

            PaySlipDetail::whereRaw("pay_slip_id = ?", [$paySlipId])->delete();
            $employees = User::active()->completed()->whereNotIn('id', [1, 2])->get();
            $userId = 185;
            $attendance = EmployeeAttendance::whereForMonth(date('m-Y'))->first();

            foreach ($employees as $employee) {
                $durationOfService = $employee->durationOfService();
                $employeeJob = MasEmployeeJob::whereMasEmployeeId($employee->id)->first();
                $sifaMember = SifaRegistration::whereMasEmployeeId($employee->id)->whereIsRegistered(1)->whereStatus(SIFA_APPROVED)->first();

                $employeeVariableValues = [];
                $employeeVariableValues['grade'] = $employee->empJob->grade->name;
                $employeeVariableValues['gradeStep'] = $employee->empJob->gradeStep->name;
                $employeeVariableValues['yearsInService'] = $durationOfService['yearsOfService'];
                $employeeVariableValues['monthsInService'] = $durationOfService['monthsOfService'];
                $employeeVariableValues['yearsSinceRegularization'] = $durationOfService['years'];
                $employeeVariableValues['monthsSinceRegularization'] = $durationOfService['months'];
                $employeeVariableValues['employmentType'] = $employeeJob->empType->id;
                $employeeVariableValues['sifaMember'] = $sifaMember ? 1 : 0;

                // Attendance for Basic Pay Calculation
                $employeeAttendance = EmployeeAttendanceDetail::whereEmployeeId($employee->id)->whereAttendanceId($attendance->id)->first();
                $basicPay = $employee->empJob->basic_pay;
                if ($attendance && $employeeAttendance && !is_null($employeeAttendance->working_days) && $employeeAttendance->working_days > 0 && !is_null($employeeAttendance->physical_days) && $employeeAttendance->physical_days > 0) {
                    $workingDays = $employeeAttendance->working_days;
                    $physicalDays = $employeeAttendance->physical_days;

                    $basicPay = round(($basicPay / $workingDays) * $physicalDays, 0);
                }
                $employeeVariableValues['basicPay'] = $basicPay;

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
                $allowanceComputeResult = PayrollService::computeAllowances($basicPay, $payScaleBasePay, $grossPay, $employeeGradeId, $employeeGroupId, $paySlipId, $employee, $userId, $employeeVariableValues);
                $grossPay = $employeeVariableValues['grossPay'] = $allowanceComputeResult['grossPay'];
                $payHeadsAfterGross = array_merge($payHeadsAfterGross, $allowanceComputeResult['payHeadsAfterGross']);
                $netPay = $employeeVariableValues['netPay'] = $grossPay;
                $deductionComputeResult = PayrollService::computeDeductions($basicPay, $netPay, $payScaleBasePay, $grossPay, $employeeGradeId, $employeeGroupId, $paySlipId, $employee, $userId, $employeeVariableValues);
                $netPay = $deductionComputeResult['netPay'];
                $pf = $deductionComputeResult['pf'] ?: 0;
                $gis = $deductionComputeResult['gis'] ?: 0;
                $payHeadsAfterGross = array_merge($payHeadsAfterGross, $deductionComputeResult['payHeadsAfterGross']);
                $pitNetPay = $employeeVariableValues['pitNetPay'] = $grossPay - ($pf + $gis);

                foreach ($payHeadsAfterGross as $payHeadAfter) {
                    if ((int) $payHeadAfter['type'] === 2) {
                        $payHead = MasPayHead::whereRaw("id = ?", [$payHeadAfter['mas_pay_head_id']])->first();
                        $calculation_method = (int) $payHead->calculation_method;
                        $calculated_on = (int) $payHead->calculated_on;

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
                            $payHeadAmount = PayrollService::calculatePayHeadAmount($employee, $employeeGradeId, $employeeGroupId, $payHead->amount, $calculation_method, $payHead->id, $amountToCalculateOn, $employeeVariableValues);
                        }

                        if ($payHeadAmount !== false) {
                            $netPay -= $payHeadAmount;
                            $employeeVariableValues['netPay'] -= $payHeadAmount;
                            PaySlipDetail::create([
                                'pay_slip_id' => $paySlipId,
                                'mas_employee_id' => $employee->id,
                                'mas_pay_head_id' => $payHead->id,
                                'amount' => $payHeadAmount,
                                'created_by' => $userId
                            ]);
                        }
                    }
                }
            }

            PayrollService::populateReportTable($payslip);

            $payrollService->updateStatus($payslip, $this->status);
        } catch (\Exception $e) {
            Log::error('Error processing payslip: ' . $e->getMessage());

            $payrollService->updateStatus($payslip, -1);
        }
    }
}
