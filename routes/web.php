<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TravelAuthorization\TravelAuthorizationApplicationController;
use App\Models\PaySlip;
use App\Http\Controllers\Profile\ProfileController;
use App\Services\PayrollService;

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
Route::redirect('/', '/login', 301);

Route::get('/test', function () {
        return   PayrollService::checkFormulaValidity(
        "IF (['EMPLOYMENT_TYPE'] == 'Regular')
        THEN ([BASIC_PAY] * 0.15)
        ELSEIF (['EMPLOYMENT_TYPE'] == 'Contract')
        THEN ([BASIC_PAY] * 0.15)
        ELSEIF (['EMPLOYMENT_TYPE'] == 'Consolidate' OR ['EMPLOYMENT_TYPE'] == 'Support Contract')
        THEN ([BASIC_PAY] * 0.05)
        ELSE
        THEN 0
        ENDIF"
            );
});

Route::get('login-as-employee/{id}','Auth\AuthenticatedSessionController@loginAs')->name('login-as-employee');

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
        Route::resource('approving-authorities', 'ApprovingAuthorityController')->except('show');
        Route::resource('condition-fields', 'ConditionFieldController');

        // Approval Conditions
        Route::post('approvalrulesaddcondition', 'ApprovalRuleController@addCondition')->name('approval-rule-conditions.store');
        Route::get('approvalrulesaddcondition/{id}/edit', 'ApprovalRuleController@getEditCondition')->name('approval-rule-conditions.edit');
        Route::patch('approvalrulesaddcondition/{id}', 'ApprovalRuleController@updateCondition')->name('approval-rule-conditions.update');
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
        Route::resource('approval', 'ExpenseApprovalController')->except('create', 'show', 'edit');
        Route::resource('dsa-claim-settlement', 'DSAClaimApplicationController');
        Route::resource('dsa-approval', 'DSAApprovalController')->except('create', 'show', 'edit');
        Route::resource('transfer-claim', 'TransferClaimApplicationController');
        Route::resource('transfer-claim-approval', 'TransferClaimApprovalController')->except('create', 'show', 'edit');
        Route::resource('expense-fuel', 'ExpenseFuelController');
        Route::resource('fuel-approval', 'FuelApprovalController')->except('create', 'show', 'edit');
        Route::resource('requisition-apply', 'RequisitionApplyController')->except('create', 'show', 'edit');
        Route::resource('requisition-history', 'RequisitionHistoryController')->except('create', 'show', 'edit');
        Route::resource('requisition-approval', 'RequisitionApprovalController')->except('create', 'show', 'edit');
    });

    // LEAVE
    Route::namespace('Leave')->prefix('leave')->group(function () {
        Route::resource('leave-policy', 'LeavePolicyController');
        Route::resource('leave-apply', 'LeaveApplicationController');
        Route::resource('cancellation', 'CancellationController')->except('create', 'show', 'edit');
        Route::resource('leave-history', 'LeaveHistoryListController')->except('create', 'show', 'edit');
        Route::resource('approval', 'LeaveApprovalController')->except('create', 'show', 'edit');
        Route::resource('encashment-approval', 'EncashmentApprovalController')->except('create', 'show', 'edit');
        Route::get('leave-encashment', 'LeaveApplicationController@leaveEncashment')->name('leave.leave-encashment');
        Route::get('leave-balance', 'LeaveApplicationController@leaveBalance')->name('leave.leave-balance');
        // Custom route for bulk approval/rejection
        Route::post('approval/bulk', 'LeaveApprovalController@bulkApprovalRejection')->name('leave.bulk-approval-rejection');
    });

    // DELEGATION APPROVAL
    Route::namespace('DelegationApproval')->prefix('delegation-approval')->group(function () {
        Route::resource('leave-delegation-approval', 'LeaveDelegationApprovalController')->except('create', 'show', 'edit');
        Route::resource('exp-delegation-approval', 'ExpDelegationApprovalController')->except('create', 'show', 'edit');
        Route::resource('fuel-delegation-approval', 'FuelDelegationApprovalController')->except('create', 'show', 'edit');
        Route::resource('dsa-delegation-approval', 'DSADelegationApprovalController')->except('create', 'show', 'edit');
        Route::resource('transfer-delegation-approval', 'TransferDelegationApprovalController')->except('create', 'show', 'edit');
        Route::resource('adv-loan-delegation', 'AdvLoanDelegationController')->except('create', 'show', 'edit');
        Route::resource('approval', 'ApprovalController')->except('create', 'show', 'edit');
    });

    // ADVANCE/LOAN
    Route::namespace('Advance')->prefix('advance-loan')->group(function () {
        Route::resource('types', 'AdvanceTypesController');
        Route::resource('apply', 'AdvanceLoanApplicationController');
        Route::resource('advance-loan-approval', 'AdvanceLoanApprovalController')->except('create', 'show', 'edit');
    });

    // TRAVEL_AUTHORIZATION
    Route::namespace('TravelAuthorization')->prefix('travel-authorization')->group(function (){
        Route::resource('apply-travel-authorization', 'TravelAuthorizationApplicationController');
        Route::resource('travel-authorization-approval', 'TravelAuthorizationApprovalController');

    });

    //SIFAREG
    Route::namespace('Sifa')->prefix('sifa')->group(function () {
        Route::resource('sifa-registration', 'SifaRegistrationController');
    });

    // Eployee
    Route::namespace('Employee')->prefix('employee')->group(function () {
        Route::resource('employee-lists', 'EmployeeController');
    });

    // LTC
    Route::namespace('LTC')->prefix('ltc')->group(function () {
        Route::resource('ltc', 'LTCController');
        Route::patch('ltc-toggle-status', 'LTCController@toggleStatus')->name('ltc.toggles-status');
        Route::patch('ltc-update-remarks', 'LTCController@updateRemarks')->name('ltc.update-remarks');
        Route::get('ltc-finalize/{id}', 'LTCController@finalizeLtc')->name('ltc.finalize');
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
    });

    //AssetsReport
    Route::namespace('Asset')->prefix('asset')->group(function () {
        Route::resource('mas-store', 'SubStoreMasterController');
        Route::resource('requisition-apply', 'RequisitionApplyController')->except('create', 'show', 'edit');
        Route::resource('requisition-history', 'RequisitionHistoryController')->except('create', 'show', 'edit');
        Route::resource('requisition-approval', 'RequisitionApprovalController')->except('create', 'show', 'edit');
        Route::resource('goods-issue', 'GoodsIssueController')->except('create', 'show', 'edit');
        Route::resource('goods-issue-history', 'GoodsIssueHistoryController')->except('create', 'show', 'edit');
        Route::resource('goods-receipt', 'GoodsReceiptController')->except('create', 'show', 'edit');
        Route::resource('goods-receipt-history', 'GoodsReceiptHistoryController')->except('create', 'show', 'edit');
        Route::resource('commission', 'CommissionController')->except('create', 'show', 'edit');
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
    Route::namespace('Payroll')->prefix('payroll')->group(function () {
        Route::resource('other-pay-changes', 'OtherPayChangeController');
        Route::resource('loan-emi-deductions', 'LoanEMIDeductionController');
        Route::resource('annual-increment', 'AnnualIncrementController');
        Route::resource('pay-slips', 'PaySlipController');

        Route::get('process-pay-slips/{id}', 'PaySlipController@processPaySlip')->name('pay-slips.process');
        Route::get('verify-pay-slips/{id}', 'PaySlipController@verifyPaySlip')->name('pay-slips.verify');
        Route::get('approve-pay-slips/{id}', 'PaySlipController@approvePaySlip')->name('pay-slips.approve');
        Route::get('mail-pay-slips/{id}', 'PaySlipController@mailPaySlip')->name('pay-slips.mail');
        Route::any('add-pay-slip-detail/{id}', 'PaySlipController@addPaySlipDetail')->name('pay-slip-detail.add');

        Route::patch('annual-increment-toggle-status', 'AnnualIncrementController@toggleStatus')->name('annual-increment.toggles-status');
        Route::patch('annual-increment-update-remarks', 'AnnualIncrementController@updateRemarks')->name('annual-increment.update-remarks');
        Route::get('annual-increment-finalize/{id}', 'AnnualIncrementController@finalizeAnnualIncrement')->name('annual-increment.finalize');

        Route::get('calculate-new-basic-pay', 'OtherPayChangeController@calculateNewBasicPay')->name('new-basic-pay.calculate');
        Route::any('add-other-pay-change-detail/{id}', 'OtherPayChangeController@addPayChangeDetail')->name('other-pay-change-detail.add');
        Route::patch('other-pay-changes-toggle-status', 'OtherPayChangeController@toggleStatus')->name('other-pay-changes.toggles-status');
        Route::patch('other-pay-changes-update-remarks', 'OtherPayChangeController@updateRemarks')->name('other-pay-changes.update-remarks');
        Route::get('other-pay-changes-finalize/{id}', 'OtherPayChangeController@finalizePayChange')->name('other-pay-changes.finalize');
    });

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
    Route::get('getemployeebyapprovingauthority/{id}', 'AjaxRequestController@getEmployeeSelect');
    Route::get('getapprovalheadtypesbyapprovalhead/{id}', 'AjaxRequestController@getApprovalHeadTypes');
    Route::get('getapprovalruleconditionfieldsbyhead/{id}', 'AjaxRequestController@getApprovalRuleConditionFields');
    Route::get('getapprovalruleconditionfieldbyid/{id}', 'AjaxRequestController@getApprovalRuleConditionField');
    Route::get('getemployees', 'AjaxRequestController@getEmployees');
    Route::get('getsystemhierarchylevelsbyhierarchyid/{id}', 'AjaxRequestController@getSystemHierarchyLevels');
    Route::get('getadvancenobyadvancetype/{id}', 'AjaxRequestController@getAdvanceNumber');
    Route::get('getmaxexpenseamountbyexpensetype/{id}', 'AjaxRequestController@getExpenseAmount');
    Route::get('getdsaadvancedetailsbyadvanceid/{id}', 'AjaxRequestController@getAdvanceDetail');
});
