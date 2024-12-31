<?php

use App\Http\Controllers\Api\SAP\ApiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Employee\EmployeeController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Reports\AdvanceLoanReportController;
use App\Http\Controllers\Reports\CashReportController;
use App\Http\Controllers\Reports\ChequeReportController;
use App\Http\Controllers\Reports\DSASettlementReportController;
use App\Http\Controllers\Reports\EmployeeReportController;
use App\Http\Controllers\Reports\ExpenseAndAdvanceReportController;
use App\Http\Controllers\Reports\GISReportController;
use App\Http\Controllers\Reports\LeaveAvailedReportController;
use App\Http\Controllers\Reports\LeaveBalanceReportController;
use App\Http\Controllers\Reports\LoanReportController;
use App\Http\Controllers\Reports\LTCController;
use App\Http\Controllers\Reports\PayComparisionReportController;
use App\Http\Controllers\Reports\PFReportController;
use App\Http\Controllers\Reports\SalaryReportController;
use App\Http\Controllers\Reports\SamsungDeductionReportController;
use App\Http\Controllers\Reports\SIFAContributionController;
use App\Http\Controllers\Reports\TaxScheduleReportController;
use App\Http\Controllers\Reports\TransferClaimReportController;
use App\Http\Controllers\Sifa\SifaRegistrationController;
use App\Http\Controllers\TravelAuthorization\TravelAuthorizationApplicationController;
use App\Models\ExpenseApplication;
use App\Models\PaySlip;
use App\Services\PayrollService;
use Illuminate\Support\Facades\Route;
use App\Mail\SendCredentialsMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;



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

require __DIR__ . '/auth.php';
require __DIR__ . '/payroll.php';
Route::redirect('/', '/login', 301);

Route::get('/updateemppas', function () {
    // Process employees in chunks
    \DB::table('mas_employees')
        ->where('id', '<>', 1)
        ->where('id', '<>', 2)
        // ->where('id', 3)


        ->orderBy('id')
        ->chunk(100, function ($employees) {
            foreach ($employees as $employee) {
                if ($employee->dob && $employee->employee_id) {
                    // Generate plain password
                    $plainPassword = bcrypt(date('Ymd', strtotime($employee->dob)) . $employee->employee_id);

                    try {
                        \DB::table('mas_employees')
                            ->where('id', $employee->id)
                            ->update(['password' => $plainPassword]);
                    } catch (\Exception $e) {
                        \Log::info("Failed to send email to {$employee->username} -> {$employee->email}: {$e->getMessage()}");
                    }
                }
            }
        });

    return "Passwords updated successfully!";
});

Route::get('/sentpasemail', function () {
    User::where('id', '<>', 1)
        ->where('id', '<>', 2)
        ->orderBy('id')
        ->chunk(100, function ($employees) {
            foreach ($employees as $employee) {
                if ($employee->dob && $employee->employee_id && !$employee->registered_email_sent) {
                    try {
                        // Queue email for sending
                        Mail::to($employee->email)->send(new SendCredentialsMail($employee, date('Ymd', strtotime($employee->dob)) .
                            $employee->employee_id));

                        // Mark as email sent
                        $employee->registered_email_sent = 1;
                        $employee->save(); // Now this will work because $employee is an Eloquent model.
                    } catch (\Exception $e) {
                        \Log::info("Failed to send email to {$employee->email}: {$e->getMessage()}");
                    }
                }
            }
        });

    return "Email sent successfully!";
});

Route::get('/debug', function () {
    $sap = new ApiController();
    $pay = new PayrollService();


    $payslip = PaySlip::first();
    return $pay->generateAndMailPaySlip($payslip, 2);

    dd($pay->checkFormulaValidity(
        "IF ([SIFA_MEMBER] == 1)
IF ([GRADE] == 'E0')
THEN (400)
ENDIF
IF ([GRADE] == 'P')
THEN (325)
ENDIF
IF ([GRADE] == 'S')
THEN (125)
ENDIF
IF ([GRADE] == 'T1')
THEN (225)
ENDIF
IF ([GRADE] == 'T2')
THEN (225)
ENDIF
IF ([GRADE] == 'GSSG')
THEN (125)
ENDIF
IF ([GRADE] == 'T')
THEN (225)
ENDIF
ELSE
THEN (0)
ENDIF"
    ));
});

Route::get('login-as-employee/{id}', 'Auth\AuthenticatedSessionController@loginAs')->name('login-as-employee');

Route::middleware('auth')->group(function () {
    Route::get('dashboard', 'DashboardController@index')->name('dashboard');

    Route::get('profile', 'HomeController@getProfile');
    Route::get('change-password', 'HomeController@getChangePassword')->name('change-password');
    Route::post('change-password', 'HomeController@postChangePassword');

    // SYSTEM SETTINGS
    Route::namespace('SystemSetting')->prefix('system-setting')->group(function () {
        Route::resource('modules', 'ModuleController')->except('show');
        Route::resource('roles', 'RoleController');
        Route::post('users/change-status', 'UserController@changeUserStatus');
        Route::resource('users', 'UserController');
        Route::resource('hierarchies', 'HierarchyController')->except('show');
        Route::resource('delegations', 'DelegationController')->except('show');
        Route::resource('notifications', 'NotificationController')->except('create', 'show', 'edit');
        Route::resource('approval-rules', 'ApprovalRuleController');
        Route::resource('approval-head', 'ApprovalHeadController');
        Route::resource('approving-authorities', 'ApprovingAuthorityController')->except('show');
        Route::resource('condition-fields', 'ConditionFieldController');


        // Approval Conditions
        Route::post('approvalrulesaddcondition', 'ApprovalRuleController@addCondition')->name('approval-rule-conditions.store');
        Route::get('approvalrulesaddcondition/{id}/edit', 'ApprovalRuleController@getEditCondition')->name('approval-rule-conditions.edit');
        Route::put('approvalrulesaddcondition/{id}', 'ApprovalRuleController@updateCondition')->name('approval-rule-conditions.update');
    });

    // MASTERS
    Route::namespace('Master')->prefix('master')->group(function () {
        Route::resource('employment-types', 'EmploymentTypeController');
        Route::resource('departments', 'DepartmentController');
        Route::resource('designations', 'DesignationController');
        Route::resource('dzongkhags', 'DzongkhagController');
        Route::resource('gewogs', 'GewogController');
        Route::resource('leave-types', 'LeaveTypeController');
        Route::resource('nationalities', 'NationalityController');
        Route::resource('section', 'SectionController');
        Route::resource('qualifications', 'QualificationController');
        Route::resource('resignation-types', 'ResignationTypeController');
        Route::resource('villages', 'VillageController');
        Route::resource('grade-steps', 'GradeStepController')->except('show');
        Route::resource('regions', 'RegionController')->except('show');
        Route::resource('region-location', 'RegionLocationController')->except(['show', 'edit']);
        Route::resource('expense-types', 'ExpenseTypeController');
        Route::resource('advance-loans', 'AdvanceLoanController');
        Route::resource('offices', 'OfficeController');
        Route::resource('vehicles', 'VehicleController');
        Route::resource('budget-code', 'BudgetCodeController');
        Route::resource('loan-types', 'MasLoanTypeController');
    });

    // WORK STRUCTURE
    Route::namespace('WorkStructure')->prefix('work-structure')->group(function () {
        Route::resource('holiday-lists', 'HolidayListController')->except('create', 'show', 'edit');
        Route::resource('business-unit', 'BusinessUnitController')->except('create', 'show', 'edit');
        Route::resource('geography', 'GeographyController')->except('create', 'show', 'edit');
    });

    // ATTENDANCE
    Route::namespace('Attendance')->prefix('attendance')->group(function () {
        Route::resource('attendance-entry', 'AttendanceEntryController')->except('create', 'show', 'edit');
        Route::resource('attendance-register', 'AttendanceRegisterController')->except('create', 'show', 'edit');
        Route::resource('attendance-summary', 'AttendanceSummaryController')->except('create', 'show', 'edit');
    });

    //EXPENSE
    Route::namespace('Expense')->prefix('expense')->group(function () {
        Route::resource('apply-expense', 'ExpenseApplicationController');
        Route::resource('expense-policy', 'ExpensePolicyController');
        Route::resource('approval', 'ExpenseApprovalController')->except('create', 'edit');
        Route::resource('dsa-claim-settlement', 'DSAClaimApplicationController');
        Route::resource('dsa-approval', 'DSAApprovalController')->except('create', 'edit');
        Route::resource('transfer-claim', 'TransferClaimApplicationController');
        // Route::resource('transfer-claim-approval', 'TransferClaimApprovalController')->except('create', 'edit');
        // Route::resource('expense-fuel', 'ExpenseFuelController');
        // Route::resource('fuel-approval', 'FuelApprovalController')->except('create', 'show', 'edit');
        // Route::resource('requisition-apply', 'RequisitionApplyController')->except('create', 'show', 'edit');
        // Route::resource('requisition-history', 'RequisitionHistoryController')->except('create', 'show', 'edit');
        // Route::resource('requisition-approval', 'RequisitionApprovalController')->except('create', 'show', 'edit');
    });
    // LEAVE
    Route::namespace('Leave')->prefix('leave')->group(function () {
        Route::resource('leave-policy', 'LeavePolicyController');
        Route::resource('leave-apply', 'LeaveApplicationController');
        Route::resource('cancellation', 'CancellationController')->except('create', 'show', 'edit');
        Route::resource('leave-history', 'LeaveHistoryListController')->except('create', 'show', 'edit');
        // Route::resource('approval', 'LeaveApprovalController');
        Route::resource('encashment-approval', 'EncashmentApprovalController')->except('create', 'show', 'edit');
        Route::resource('leave-encashment', 'LeaveEncashmentApplicationController')->except('show', 'edit')
            ->names([
                'create' => 'leave.leave-encashment',
                'store' => 'leave.leave-encashment.store',
            ]);

        Route::get('leave-balance', 'LeaveApplicationController@leaveBalance')->name('leave.leave-balance');
        Route::get('encashment-history', 'LeaveEncashmentApplicationController@index')->name('leave.encashment-history');
        // Custom route for bulk approval/rejection
        // Route::post('approval/bulk', 'LeaveApprovalController@bulkApprovalRejection')->name('leave.bulk-approval-rejection');
        // Route::post('encashment-approval/bulk', 'EncashmentApprovalController@bulkApprovalRejection')->name('encashment.bulk-approval-rejection');

        Route::get('/send-encashment-notifications', [DashboardController::class, 'sendEncashmentNotification']);
    });

    // DELEGATION APPROVAL
    Route::namespace('DelegationApproval')->prefix('delegation-approval')->group(function () {
        Route::resource('leave-delegation-approval', 'LeaveDelegationApprovalController')->except('create', 'show', 'edit');
        Route::resource('exp-delegation-approval', 'ExpDelegationApprovalController')->except('create', 'show', 'edit');
        Route::resource('fuel-delegation-approval', 'FuelDelegationApprovalController')->except('create', 'show', 'edit');
        Route::resource('dsa-delegation-approval', 'DSADelegationApprovalController')->except('create', 'show', 'edit');
        Route::resource('transfer-delegation-approval', 'TransferDelegationApprovalController')->except('create', 'show', 'edit');
        Route::resource('adv-loan-delegation', 'AdvLoanDelegationController')->except('create', 'show', 'edit');
        // Route::resource('approval', 'ApprovalController')->except('create', 'show', 'edit');
    });

    // ADVANCE/LOAN
    Route::namespace('Advance')->prefix('advance-loan')->group(function () {
        Route::resource('types', 'AdvanceTypesController');
        Route::resource('apply', 'AdvanceLoanApplicationController');
        // Route::resource('advance-loan-approval', 'AdvanceLoanApprovalController')->except('create');
        // Route::post('approval/bulk', 'AdvanceLoanApprovalController@bulkApprovalRejection')->name('advance.bulk-approval-rejection');
    });

    // TRAVEL_AUTHORIZATION
    Route::namespace('TravelAuthorization')->prefix('travel-authorization')->group(function () {
        Route::resource('apply-travel-authorization', 'TravelAuthorizationApplicationController');
        Route::resource('travel-authorization-approval', 'TravelAuthorizationApprovalController');
        Route::post('approval/bulk', 'TravelAuthorizationApprovalController@bulkApprovalRejection')->name('travel-authorization.bulk-approval-rejection');
    });

    //SIFAREG
    Route::namespace('Sifa')->prefix('sifa')->group(function () {
        Route::resource('sifa-registration', 'SifaRegistrationController');
        Route::resource('sifa-approval', 'SifaApprovalController');
        Route::resource('sifa-registered-user', 'SifaRegisteredUserController');
        Route::post('approval/bulk', 'SifaApprovalController@bulkApprovalRejection')->name('sifa.bulk-approval-rejection');
    });

    // Eployee
    Route::namespace('Employee')->prefix('employee')->group(function () {
        Route::resource('employee-lists', 'EmployeeController');
        Route::get('/employee/details', [EmployeeController::class, 'showEmployeeDetails'])->name('employee.details');
        Route::get('regularize-employee', [EmployeeController::class, 'showRegularizeDetails'])->name('employee.regularize');
        Route::patch('regularize-toggle-status', 'EmployeeController@toggleStatus')->name('employee-regularize.toggles-status');
        Route::patch('generate-regular-ao', 'EmployeeController@generateRegularAO')->name('employee.generate-regular-ao');
    });

    //reports
    Route::namespace('Reports')->prefix('report')->group(function () {
        Route::resource('ltc-report', 'LTCController')->except('create', 'show', 'edit');
        Route::resource('leave-availed-report', 'LeaveAvailedReportController')->except('create', 'show', 'edit');
        Route::resource('leave-balance-report', 'LeaveBalanceReportController')->except('create', 'show', 'edit');
        Route::resource('vehicle-fuel-report', 'VehicleFuelReportController')->except('create', 'show', 'edit');
        Route::resource('advance-loan-report', 'AdvanceLoanReportController')->except('create', 'show', 'edit');
        Route::resource('expense-and-advance-report', 'ExpenseAndAdvanceReportController')->except('create', 'show', 'edit');
        Route::resource('leave-encashment-report', 'LeaveEncashmentReportController')->except('create', 'show', 'edit');
        Route::resource('salary-report', 'SalaryReportController')->except('create', 'show', 'edit');
        Route::resource('loan-report', 'LoanReportController')->except('create', 'show', 'edit');
        Route::resource('cheque-report', 'ChequeReportController')->except('create', 'show', 'edit');
        Route::resource('cash-report', 'CashReportController')->except('create', 'show', 'edit');
        Route::resource('gis-report', 'GISReportController')->except('create', 'show', 'edit');
        Route::resource('pf-report', 'PFReportController')->except('create', 'show', 'edit');
        Route::resource('sifa-contribution', 'SIFAContributionController')->except('create', 'show', 'edit');
        Route::resource('salary-saving-scheme', 'SalarySavingSchemeController')->except('create', 'show', 'edit');
        Route::resource('employee-report', 'EmployeeReportController')->except('create', 'show', 'edit');
        Route::resource('transfer-claim-report', 'TransferClaimReportController')->except('create', 'show', 'edit');
        Route::resource('dsa-settlement-report', 'DSASettlementReportController')->except('create', 'show', 'edit');
        Route::resource('samsung-deduction-report', 'SamsungDeductionReportController')->except('create', 'show', 'edit');
        Route::resource('pay-comparision-report', 'PayComparisionReportController')->except('create', 'show', 'edit');
        Route::resource('tax-schedule-report', 'TaxScheduleReportController')->except('create', 'show', 'edit');
        Route::resource('eteeru-remittance-report', 'eTeeruRemittanceReportController')->except('create', 'show', 'edit');
    });

    //reportexport routes
    Route::get('/export-salary-report', [SalaryReportController::class, 'exportSalary'])->name('salary-report-pdf.export');
    Route::get('/export-salary-excel-report', [SalaryReportController::class, 'exportSalaryExcel'])->name('salary-report-excel.export');
    Route::get('/export-sifa-report', [SIFAContributionController::class, 'exportSifa'])->name('sifa-report-pdf.export');
    Route::get('/export-sifa-excel-report', [SIFAContributionController::class, 'exportSifaExcel'])->name('sifa-report-excel.export');
    Route::get('/export-gis-report', [GISReportController::class, 'exportGIS'])->name('gis-report-pdf.export');
    Route::get('/export-gis-excel-report', [GISReportController::class, 'exportGISExcel'])->name('gis-report-excel.export');
    Route::get('/export-pf-report', [PFReportController::class, 'exportPF'])->name('pf-report-pdf.export');
    Route::get('/export-pf-excel-report', [PFReportController::class, 'exportPFExcel'])->name('pf-report-excel.export');
    Route::get('/export-loan-report', [LoanReportController::class, 'exportLoan'])->name('loan-report-pdf.export');
    Route::get('/export-loan-excel-report', [LoanReportController::class, 'exportLoanExcel'])->name('loan-report-excel.export');
    Route::get('/export-samsung-deduction-report', [SamsungDeductionReportController::class, 'exportSamsungDeduction'])->name('samsung-deduction-report-pdf.export');
    Route::get('/export-samsung-deduction-excel-report', [SamsungDeductionReportController::class, 'exportSamsungDeductionExcel'])->name('samsung-deduction-report-excel.export');
    Route::get('/export-cheque-report', [ChequeReportController::class, 'exportCheque'])->name('cheque-report-pdf.export');
    Route::get('/export-cheque-excel-report', [ChequeReportController::class, 'exportChequeExcel'])->name('cheque-report-excel.export');
    Route::get('/export-cash-report', [CashReportController::class, 'exportCash'])->name('cash-report-pdf.export');
    Route::get('/export-cash-excel-report', [CashReportController::class, 'exportCashExcel'])->name('cash-report-excel.export');
    Route::get('/export-leave-availed-report', [LeaveAvailedReportController::class, 'exportLeaveAvailed'])->name('leave-availed-pdf.export');
    Route::get('/export-leave-availed-excel-report', [LeaveAvailedReportController::class, 'exportLeaveAvailedExcel'])->name('leave-availed-excel.export');
    Route::get('/export-leave-balance-report', [LeaveBalanceReportController::class, 'exportLeaveBalance'])->name('leave-balance-pdf.export');
    Route::get('/export-leave-balance-excel-report', [LeaveBalanceReportController::class, 'exportLeaveBalanceExcel'])->name('leave-balance-excel.export');
    Route::get('/export-ltc-report', [LTCController::class, 'exportLTC'])->name('ltc-pdf.export');
    Route::get('/export-ltc-excel-report', [LTCController::class, 'exportLTCExcel'])->name('ltc.export');
    Route::get('/export-advance-loan-report', [AdvanceLoanReportController::class, 'exportAdvanceLoan'])->name('advance-loan-pdf.export');
    Route::get('/export-advance-loan-excel-report', [AdvanceLoanReportController::class, 'exportAdvanceLoanExcel'])->name('advance-loan.export');
    Route::get('/export-employee-report', [EmployeeReportController::class, 'exportEmployee'])->name('employee-pdf.export');
    Route::get('/export-employee-excel-report', [EmployeeReportController::class, 'exportEmployeeExcel'])->name('employee-excel.export');
    Route::get('/export-expense-report', [ExpenseAndAdvanceReportController::class, 'exportExpense'])->name('expense-pdf.export');
    Route::get('/export-expense-excel-report', [ExpenseAndAdvanceReportController::class, 'exportExpenseExcel'])->name('expense-excel.export');
    Route::get('/export-transfer-claim-report', [TransferClaimReportController::class, 'exportTransferClaim'])->name('transfer-claim-pdf.export');
    Route::get('/export-transfer-claim-excel-report', [TransferClaimReportController::class, 'exportTransferClaimExcel'])->name('transfer-claim-excel.export');
    Route::get('/export-dsa-settlement-report', [DSASettlementReportController::class, 'exportDSASettlement'])->name('dsa-settlement-pdf.export');
    Route::get('/export-dsa-settlement-excel-report', [DSASettlementReportController::class, 'exportDSASettlementExcel'])->name('dsa-settlement-excel.export');
    Route::get('/pay-comparision-report', [PayComparisionReportController::class, 'exportPayComparision'])->name('pay-comparision-report-pdf.export');
    // Route::get('/pay-comparision-excel-report', [PayComparisionReportController::class, 'exportPayComparision'])->name('pay-comparision-report-excel.export');
    Route::get('/tax-schedule-report', [TaxScheduleReportController::class, 'exportTaxSchedule'])->name('tax-schedule-report-pdf.export');
    Route::get('/tax-schedule-excel-report', [TaxScheduleReportController::class, 'exportTaxScheduleExcel'])->name('tax-schedule-report-excel.export');

    //printer
    Route::get('/print-leave-availed-report', [LeaveAvailedReportController::class, 'printLeave'])->name('leave-availed-report-print');
    Route::get('/print-leave-balance-report', [LeaveBalanceReportController::class, 'printLeaveBalance'])->name('leave-balance-report-print');
    Route::get('/print-salary-report', [SalaryReportController::class, 'printSalary'])->name('salary-report-print');
    Route::get('/print-sifa-report', [SIFAContributionController::class, 'printSifa'])->name('sifa-report-print');
    Route::get('/print-gis-report', [GISReportController::class, 'printGIS'])->name('gis-report-print');
    Route::get('/print-pf-report', [PFReportController::class, 'printPF'])->name('pf-report-print');
    Route::get('/print-loan-report', [LoanReportController::class, 'printLoan'])->name('loan-report-print');
    Route::get('/print-samsung-deduction-report', [SamsungDeductionReportController::class, 'printSamsungDeduction'])->name('samsung-deduction-report-print');
    Route::get('/print-cheque-report', [ChequeReportController::class, 'printCheque'])->name('cheque-report-print');
    Route::get('/print-cash-report', [CashReportController::class, 'printCash'])->name('cash-report-print');
    Route::get('/print-ltc-report', [LTCController::class, 'printLTC'])->name('ltc-print');
    Route::get('/print-advance-loan-report', [AdvanceLoanReportController::class, 'printAdvanceLoan'])->name('advance-loan-print');
    Route::get('/print-employee-report', [EmployeeReportController::class, 'printEmployee'])->name('employee-report-print');
    Route::get('/print-expense-report', [ExpenseAndAdvanceReportController::class, 'printExpense'])->name('expense-report-print');
    Route::get('/print-transfer-claim-report', [TransferClaimReportController::class, 'printTransferClaim'])->name('transfer-claim-print');
    Route::get('/print-dsa-settlement-report', [DSASettlementReportController::class, 'printDSASettlement'])->name('dsa-settlement-print');
    Route::get('/print-tax-schedle-report', [TaxScheduleReportController::class, 'printTaxSchedule'])->name('tax-schedule-report-print');
    Route::get('/print-pay-comparision-report', [PayComparisionReportController::class, 'printPayComparision'])->name('pay-comparision-report-print');



    //AssetsReport
    Route::namespace('Asset')->prefix('asset')->group(function () {
        Route::resource('mas-store', 'SubStoreMasterController');
        Route::resource('requisition-apply', 'RequisitionApplicationController')->except('create', 'show', 'edit');
        Route::resource('requisition', 'RequisitionApplicationController');
        Route::resource('requisition-history', 'RequisitionHistoryController')->except('create', 'show', 'edit');
        Route::resource('requisition-approval', 'RequisitionApprovalController')->except('create', 'delete');
        // Route::post('approval/bulk', 'AjaxRequestController@bulkApprovalRejection')->name('requisition.bulk-approval-rejection');

        Route::resource('goods-issue', 'GoodsIssueController');
        Route::resource('goods-issue-history', 'GoodsIssueHistoryController')->except('create', 'show', 'edit');
        Route::resource('goods-receipt', 'GoodsReceiptController')->except('show', 'edit');
        Route::resource('goods-receipt-history', 'GoodsReceiptHistoryController')->except('create', 'show', 'edit');
        Route::resource('commission', 'CommissionController')->except('show', 'edit');
        Route::resource('commission-history', 'CommissionHistoryController')->except('create', 'show', 'edit');
        Route::resource('commission-approval', 'CommissionApprovalController')->except('create', 'show', 'edit');
        Route::resource('asset-transfer', 'AssetTransferController')->except('create', 'show', 'edit');
        Route::resource('transfer-history', 'TransferHistoryController')->except('create', 'show', 'edit');
        Route::resource('transfer-approval', 'TransferApprovalController')->except('create', 'show', 'edit');
        Route::resource('fixed-asset-return', 'FixedAssetReturnController')->except('create', 'show', 'edit');
        Route::resource('fixed-asset-return-history', 'FixedAssetReturnHistoryController')->except('create', 'show', 'edit');
        Route::resource('fixed-asset-return-approval', 'FixedAssetReturnApprovalController')->except('create', 'show', 'edit');
        //Route::resource('', 'Controller')->except('create', 'show', 'edit');
    });

    //PayMaster
    Route::namespace('PayMaster')->prefix('paymaster')->group(function () {
        Route::resource('account-heads', 'AccountHeadsController');
        Route::resource('pay-groups', 'PayGroupsController');
        Route::resource('pay-heads', 'PayHeadsController');
        Route::resource('pay-slabs', 'PaySlabsController');
        Route::resource('pay-slab-details', 'PaySlabsDetailsController');
        Route::resource('pay-group-details', 'PayGroupDetailsController');
    });

    //Payroll
    require __DIR__ . '/payroll.php';
    require __DIR__ . '/approval.php';

    //EmployeeCategory
    Route::namespace('EmployeeGroup')->prefix('employee-group')->group(function () {
        Route::resource('employee-create', 'EmployeeGroupController');
    });

    //ProfileController
    Route::namespace('Profile')->prefix('user-profile')->group(function () {
        Route::resource('user-profile', 'ProfileController');
        Route::put('/user-profile/{id}/update-image', 'ProfileController@updateImage')->name('user-profile.updateImage');
    });


    /* route related to ajax */
    Route::get('getgewogbydzongkhag/{id}', 'AjaxRequestController@getGewog');
    Route::get('getvillagebygewog/{id}', 'AjaxRequestController@getVillage');
    Route::get('getsectionbydepartment/{id}', 'AjaxRequestController@getSection');
    Route::get('getgradestepbygrade/{id}', 'AjaxRequestController@getGradeStep');
    Route::get('getpayslabdetail/{id}', 'AjaxRequestController@getPaySlabDetail');
    Route::get('getpaygroupdetail/{id}', 'AjaxRequestController@getPayGroupDetail');
    Route::get('getregionlocation/{id}', 'AjaxRequestController@getRegionLocation');
    Route::get('getpayscalebygradestep/{id}', 'AjaxRequestController@getPayScale');
    Route::get('getleavebalancebyleavetype/{id}', 'AjaxRequestController@getLeaveBalance');
    Route::get('getnoofdaysbydate', 'AjaxRequestController@getNoOfDays');
    Route::get('validateleavecombination', 'AjaxRequestController@validateLeaveCombinations');
    Route::get('getemployeebyapprovingauthority/{id}', 'AjaxRequestController@getEmployeeSelect');
    Route::get('getapprovalheadtypesbyapprovalhead/{id}', 'AjaxRequestController@getApprovalHeadTypes');
    Route::get('getapprovalruleconditionfieldsbyhead/{id}', 'AjaxRequestController@getApprovalRuleConditionFields');
    Route::get('getapprovalruleconditionfieldbyid/{id}', 'AjaxRequestController@getApprovalRuleConditionField');
    Route::get('getemployees', 'AjaxRequestController@getEmployees');
    Route::get('getsystemhierarchylevelsbyhierarchyid/{id}', 'AjaxRequestController@getSystemHierarchyLevels');
    Route::get('getadvancenobyadvancetype/{id}', 'AjaxRequestController@getAdvanceNumber');
    Route::get('getexpensenobyexpensetype/{id}', 'AjaxRequestController@getExpenseNumber');
    Route::get('getmaxexpenseamountbyexpensetype/{id}', 'AjaxRequestController@getExpenseAmount');

    // Route::post('approverejectbulk', 'AjaxRequestController@bulkApprovalRejection')->name('approverejectbulk');
    Route::get('getemployeebyid/{id}', 'AjaxRequestController@getEmployeeById');
    Route::get('gettravelauthorizationbytravelauthorizationid/{id}', 'AjaxRequestController@getTravelAuthorizationDetails');
    Route::get('getdsaadvancebytravelauth/{id}', 'AjaxRequestController@getDsaAdvancebyTravelAuth');
    Route::get('getdsaadvancedetails/{id}', 'AjaxRequestController@getDsaAdvanceDetails');
    Route::get('gettravelbyid/{id}', 'AjaxRequestController@getTravelNumber');
    Route::get('getrequisitionnobyrequisitiontype/{id}', 'AjaxRequestController@getRequisitionNumber');
    Route::get('getissuenobyissuetype/{id}', 'AjaxRequestController@getIssueNumber');
    Route::get('getreceiptnobyreceipttype/{id}', 'AjaxRequestController@getReceiptNumber');
    Route::get('getrequisitiondetailsbyrequisitionid/{id}', 'AjaxRequestController@getRequisitionDetails');
    Route::get('getdetailsbyissue/{issue_no}', 'AjaxRequestController@getDetailsByIssue')->name('get.details.by.issue');
    Route::get('getcommissionnobycommissiontype/{id}', 'AjaxRequestController@getCommissionNumber');
    Route::get('getdetailsbyreceipt/{receipt_no}', 'AjaxRequestController@getDetailsByReceipt')->name('get.details.by.receipt');
    Route::get('getvehicledetailtypebyid/{id}', 'AjaxRequestController@getVehicleDetailTypeById');
});
