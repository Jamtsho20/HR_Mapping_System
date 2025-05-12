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

use App\Http\Controllers\ApprovalController;

Route::prefix('approval')->group(function () {
    // Route::get('applications', [ApprovalController::class, 'index'])->name('approval.index');
    Route::get('applications', [ApprovalController::class, 'index'])->name('approval.index');
    Route::get('applications/{id}/edit', [ApprovalController::class, 'edit'])->name('approval.edit');
    Route::put('applications/{id}', [ApprovalController::class, 'update'])->name('approval.update');
    Route::get('approved-applications', [ApprovalController::class, 'approvedApplications'])->name('approval.approved');
    Route::get('approved-applications/{id}', [ApprovalController::class, 'show'])->name('approved-applications.detail');
    Route::post('approverejectbulk', [ApprovalController::class, 'approveReject'])->name('approverejectbulk');
    Route::get('applications/{id}', [ApprovalController::class, 'show'])->name('approval.detail');
    Route::get('rejected-applications', [ApprovalController::class, 'approvedApplications'])->name('approval.rejected');
    Route::get('rejected-applications/{id}', [ApprovalController::class, 'show'])->name('rejected-applications.detail');

});
