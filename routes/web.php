<?php

use Illuminate\Support\Facades\Route;

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

require __DIR__.'/auth.php';
Route::redirect('/', '/login', 301);

Route::middleware('auth')->group(function () {
    Route::get('dashboard', 'DashboardController@index')->name('dashboard');

	Route::get('profile', 'HomeController@getProfile');
    Route::get('change-password', 'HomeController@getChangePassword');
    Route::post('change-password', 'HomeController@postChangePassword');

    // SYSTEM SETTINGS
    Route::namespace('SystemSetting')->prefix('system-setting')->group(function() {
        Route::resource('modules', 'ModuleController')->except('show');
        Route::resource('roles', 'RoleController');
        Route::post('users/change-status', 'UserController@changeUserStatus');
        Route::resource('users', 'UserController');
        Route::resource('hierarchies', 'HierarchyController')->except('create', 'show', 'edit');
        Route::resource('delegations', 'DelegationController')->except('create', 'show', 'edit');
        Route::resource('notifications', 'NotificationController')->except('create', 'show', 'edit');
    });

    // MASTERS
    Route::namespace('Master')->prefix('master')->group(function() {
        Route::resource('employment-types', 'EmploymentTypeController')->except('create', 'show', 'edit');
        Route::resource('departments', 'DepartmentController')->except('create', 'show', 'edit');
        Route::resource('designations', 'DesignationController')->except('create', 'show', 'edit');
        Route::resource('dzongkhags', 'DzongkhagController')->except('create', 'show', 'edit');
        Route::resource('gewogs', 'GewogController')->except('create', 'show', 'edit');
        Route::resource('leave-types', 'LeaveTypeController')->except('create', 'show', 'edit');
        Route::resource('nationalities', 'NationalityController')->except('create', 'show', 'edit');
        Route::resource('section', 'SectionController')->except('create', 'show', 'edit');
        Route::resource('qualifications', 'QualificationController')->except('create', 'show', 'edit');
        Route::resource('resignation-types', 'ResignationTypeController')->except('create', 'show', 'edit');
        Route::resource('villages', 'VillageController')->except('create', 'show', 'edit');
        Route::resource('grade-steps', 'GradeStepController')->except('show');
        Route::resource('regions', 'RegionController')->except('create', 'show', 'edit');
        Route::resource('expense-types', 'ExpenseTypeController')->except('create', 'show', 'edit');
    });

     // WORK STRUCTURE
     Route::namespace('WorkStructure')->prefix('work-structure')->group(function() {
        Route::resource('holiday-lists', 'HolidayListController')->except('create', 'show', 'edit');
        Route::resource('business-unit', 'BusinessUnitController')->except('create', 'show', 'edit');
        Route::resource('geography', 'GeographyController')->except('create', 'show', 'edit');
    });

    // ATTENDANCE
    Route::namespace('Attendance')->prefix('attendance')->group(function() {
        Route::resource('attendance-entry', 'AttendanceEntryController')->except('create', 'show', 'edit');
        Route::resource('attendance-register', 'AttendanceRegisterController')->except('create', 'show', 'edit');
        Route::resource('attendance-summary', 'AttendanceSummaryController')->except('create', 'show', 'edit');

    });

     //EXPENSE
     Route::namespace('Expense')->prefix('expense')->group(function() {
        Route::resource('apply', 'ExpenseApplyController')->except('create', 'show', 'edit');
        Route::resource('approval', 'ExpenseApprovalController')->except('create', 'show', 'edit');
        Route::resource('dsa-claim-settlement', 'DSAClaimController')->except('create', 'show', 'edit');
        Route::resource('dsa-approval', 'DSAApprovalController')->except('create', 'show', 'edit');
        Route::resource('transfer-claim', 'TransferClaimController')->except('create', 'show', 'edit');
        Route::resource('transfer-claim-approval', 'TransferClaimApprovalController')->except('create', 'show', 'edit');
        Route::resource('expense-fuel', 'ExpenseFuelController')->except('create', 'show', 'edit');
        Route::resource('fuel-approval', 'FuelApprovalController')->except('create', 'show', 'edit');
    });

    // LEAVE
    Route::namespace('Leave')->prefix('leave')->group(function() {
        Route::resource('leave-history', 'LeaveController')->except('create', 'show', 'edit');
        Route::resource('cancellation', 'CancellationController')->except('create', 'show', 'edit');
        Route::resource('history', 'LeaveHistoryListController')->except('create', 'show', 'edit');
        Route::resource('approval', 'LeaveApprovalController')->except('create', 'show', 'edit');
        Route::resource('encashment-approval', 'EncashmentApprovalController')->except('create', 'show', 'edit');
    });

    // DELEGATION APPROVAL
      Route::namespace('DelegationApproval')->prefix('delegation-approval')->group(function() {
        Route::resource('leave-delegation-approval', 'LeaveDelegationApprovalController')->except('create', 'show', 'edit');
        Route::resource('exp-delegation-approval', 'ExpDelegationApprovalController')->except('create', 'show', 'edit');
        Route::resource('fuel-delegation-approval', 'FuelDelegationApprovalController')->except('create', 'show', 'edit');
        Route::resource('dsa-delegation-approval', 'DSADelegationApprovalController')->except('create', 'show', 'edit');
        Route::resource('transfer-delegation-approval', 'TransferDelegationApprovalController')->except('create', 'show', 'edit');
        Route::resource('adv-loan-delegation', 'AdvLoanDelegationController')->except('create', 'show', 'edit');
        Route::resource('approval', 'ApprovalController')->except('create', 'show', 'edit');
    });

    // ADVANCE/LOAN
       Route::namespace('Advance')->prefix('advance-loan')->group(function() {
        Route::resource('apply', 'AdvanceLoanApplyController')->except('create', 'show', 'edit');
        Route::resource('approval', 'AdvanceLoanApprovalController')->except('create', 'show', 'edit');
    });

    Route::get('getgewogbydzongkhag/{id}', 'Master\VillageController@getGewog');
});