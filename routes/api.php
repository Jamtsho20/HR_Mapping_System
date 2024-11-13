<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\Expense\ExpenseApplicationController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::middleware('api.access.log')->group(function () {
    Route::post('login', [LoginController::class, 'login']);
    Route::post('forgot-password', [LoginController::class, 'handleForgotPassword']);
    
    //other app related route
    // Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    //     return $request->user();
    // });

    Route::middleware('auth:sanctum')->group(function () {
        Route::put('change-password', [LoginController::class, 'handleChangePassword']);
    });
});

Route::namespace('Api\Expense')->prefix('expense')->middleware('auth:sanctum')->group(function () {
    // Route::resource('apply-expense', 'ExpenseApplicationController');
    Route::get('expense/apply-expense', [ExpenseApplicationController::class, 'index']);
    Route::get('expense/apply-expense/{id}', [ExpenseApplicationController::class, 'show']);
    Route::put('expense/apply-expense/{id}', [ExpenseApplicationController::class, 'update']);
    Route::post('expense/apply-expense', [ExpenseApplicationController::class, 'store']);
    Route::delete('expense/apply-expense/{id}', [ExpenseApplicationController::class, 'destroy']);
});
