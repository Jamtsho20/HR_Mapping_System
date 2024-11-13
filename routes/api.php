<?php

use App\Http\Controllers\AjaxRequestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\DummyApi;
use App\Http\Controllers\Api\Advance\AdvanceLoanApplicationApiController;


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
        Route::resource('advance-applications', AdvanceLoanApplicationApiController::class);
        //generate advance no based on selection of advance type
        Route::get('generate-advancenumber/{id}', [AjaxRequestController::class,'getAdvanceNumber']);
    });
});
// Route::middleware('auth:sanctum')->group(function () {
//     Route::get('advance-applications', [AdvanceLoanApplicationApiController::class, 'index']);
//     Route::get('advance-applications/{id}', [AdvanceLoanApplicationApiController::class, 'show']);
//     Route::post('advance-applications', [AdvanceLoanApplicationApiController::class, 'store']);
//     Route::put('advance-applications/{id}', [AdvanceLoanApplicationApiController::class, 'update']);
//     Route::delete('advance-applications/{id}', [AdvanceLoanApplicationApiController::class, 'destroy']);
// });
