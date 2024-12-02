<?php

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

use App\Http\Controllers\Payroll\LTCController;

 Route::namespace('Payroll')->prefix('payroll')->group(function () {
    Route::resource('other-pay-changes', 'OtherPayChangeController');
    Route::resource('loan-emi-deductions', 'LoanEMIDeductionController');
    Route::resource('annual-increment', 'AnnualIncrementController');
    Route::resource('pay-slips', 'PaySlipController');
    Route::resource('ltc', 'LTCController');

    Route::get('process-pay-slips/{id}', 'PaySlipController@processPaySlip')->name('pay-slips.process');
    Route::get('verify-pay-slips/{id}', 'PaySlipController@verifyPaySlip')->name('pay-slips.verify');
    Route::get('approve-pay-slips/{id}', 'PaySlipController@approvePaySlip')->name('pay-slips.approve');
    Route::get('mail-pay-slips/{id}', 'PaySlipController@mailPaySlip')->name('pay-slips.mail');
    Route::any('add-pay-slip-detail/{id}', 'PaySlipController@addPaySlipDetail')->name('pay-slip-detail.add');
    Route::any('update-pay-slip-detail/{payslipId}/{id}', 'PaySlipController@updatePaySlipDetail')->name('pay-slip-detail.update');

    Route::patch('annual-increment-toggle-status', 'AnnualIncrementController@toggleStatus')->name('annual-increment.toggles-status');
    Route::patch('annual-increment-update-remarks', 'AnnualIncrementController@updateRemarks')->name('annual-increment.update-remarks');
    Route::get('annual-increment-finalize/{id}', 'AnnualIncrementController@finalizeAnnualIncrement')->name('annual-increment.finalize');

    Route::get('calculate-new-basic-pay', 'OtherPayChangeController@calculateNewBasicPay')->name('new-basic-pay.calculate');
    Route::any('add-other-pay-change-detail/{id}', 'OtherPayChangeController@addPayChangeDetail')->name('other-pay-change-detail.add');
    Route::patch('other-pay-changes-toggle-status', 'OtherPayChangeController@toggleStatus')->name('other-pay-changes.toggles-status');
    Route::patch('other-pay-changes-update-remarks', 'OtherPayChangeController@updateRemarks')->name('other-pay-changes.update-remarks');
    Route::get('other-pay-changes-finalize/{id}', 'OtherPayChangeController@finalizePayChange')->name('other-pay-changes.finalize');

    Route::post('ltc-finalize', [LTCController::class, 'finalizeLtc'])->name('ltc.finalize');
    Route::post('ltc-toggles-status', [LTCController::class, 'toggleStatus'])->name('ltc.toggles-status');
    Route::post('ltc-toggles-status', [LTCController::class, 'toggleStatus'])->name('ltc.toggles-status');
    Route::post('ltc-update-remarks', [LTCController::class, 'toggleStatus'])->name('ltc.update-remarks');
});
