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
    Route::get('applications', [ApprovalController::class, 'index'])->name('approval.index');
    Route::post('approverejectbulk', [ApprovalController::class, 'approveReject'])->name('approverejectbulk');
});
