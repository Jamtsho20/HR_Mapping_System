<?php

use App\Http\Controllers\AjaxRequestController;
use App\Http\Controllers\Api\Advance\AdvanceLoanApplicationApiController;
use App\Http\Controllers\Api\Anniversary\AnniversaryController;
use App\Http\Controllers\Api\Expense\DSAClaimApplicationController;
use App\Http\Controllers\Api\Expense\ExpenseApplicationController;
use App\Http\Controllers\Api\Expense\ExpenseApprovalController;
use App\Http\Controllers\Api\Expense\TransferClaimApplicationController;
use App\Http\Controllers\Api\HolidayListController;
use App\Http\Controllers\Api\Attendance\AttendanceApiController;
use App\Http\Controllers\Api\Leave\LeaveApplicationController;
use App\Http\Controllers\Api\Leave\LeaveEncashmentApprovalController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\SAP\ApiController;
use App\Http\Controllers\Api\SOMs\ApiController as SomsApiComtroller;
use App\Http\Controllers\Api\v1\Advance\AdvanceLoanGadgetEmiController;
use App\Http\Controllers\Api\v1\Asset\CommissionApplicationApiController;
use App\Http\Controllers\Api\v1\Asset\RequisitionApplicationApiController;
use App\Http\Controllers\Api\v1\Asset\MyAssetApiController;
use App\Http\Controllers\Api\v1\GeneralApporvalController;
use App\Http\Controllers\Api\v1\TravelAuthorization\TravelAuthorizationApplicationController;
use App\Http\Controllers\Api\v1\UserController;
use App\Http\Controllers\ApprovalController;
use Illuminate\Support\Facades\Route;




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
    Route::post('sap/login', [LoginController::class, 'sapLogin']);
    Route::post('forgot-password', [LoginController::class, 'handleForgotPassword']);


    Route::get('/get-soms-token', [SomsApiComtroller::class, 'startSession']);

    //other app related route
    // Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    //     return $request->user();
    // });

    Route::namespace('Api\Advance')->middleware('auth:sanctum')->group(function () {
        Route::put('change-password', [LoginController::class, 'handleChangePassword']);
        Route::resource('advance-applications', AdvanceLoanApplicationApiController::class);
        //generate advance no based on selection of advance type
        Route::get('generate-advancenumber/{id}', [AjaxRequestController::class, 'getAdvanceNumber']);
        Route::resource('advance_approval', 'AdvanceLoanApprovalController');
    });

    Route::namespace('Api\v1')->middleware('auth:sanctum')->group(function () {
        Route::resource('approval_count', 'GeneralApporvalController');
        Route::resource('my_team', 'TeamApiController');
        Route::post('/profile-pic', [UserController::class, 'updateProfilePic']);
    });

    Route::namespace('Api\v1\Asset')->middleware('auth:sanctum')->group(function () {
        Route::resource('requisition', 'RequisitionApplicationApiController');
        Route::get('requisition/get_item/{id}', [AjaxRequestController::class, 'getItemByGrnId']);
        Route::get('requisition_approval', [RequisitionApplicationApiController::class, 'indexRequisitionApproval']);
        Route::get('receive/{id}', [RequisitionApplicationApiController::class, 'receive']);
        Route::post('receive', [AjaxRequestController::class, 'receive']);

        Route::get('get_sites/{id}', [AjaxRequestController::class, 'getSitesByDzongkhagId']);
        Route::get('/get-stock/{itemCode}', [ApiController::class, 'getStock']);
        Route::get('/get-asset/{id}', [CommissionApplicationApiController::class, 'getAssetNoByGrnId']);
        Route::get('/my-asset', [MyAssetApiController::class, 'getAsset']);
        Route::resource('commission', 'CommissionApplicationApiController');
        Route::get('commission_approval', [CommissionApplicationApiController::class, 'indexCommissionApproval']);
        Route::get('getGrnDetails/{grnNo}', [AjaxRequestController::class, 'getGrnDetailByGrnNo']);
    });

    Route::namespace('Api\Expense')->middleware('auth:sanctum')->group(function () {
        Route::resource('expense', 'ExpenseApplicationController');
        Route::get('expense_number/{id}', [ExpenseApplicationController::class, 'fetchExpenseNumber']);

        //Transfer Claim
        Route::resource('transfer_claim', 'TransferClaimApplicationController');
        Route::get('transfer_claim_number', [TransferClaimApplicationController::class, 'getTransferClaimNumber']);

        //DSA caim
        Route::resource('dsa_claim', 'DSAClaimApplicationController');
        Route::get('dsa_claim_advance/{id}', [ajaxRequestController::class, 'getDsaAdvancebyTravelAuth']);
        Route::get('dsa_claim_number', [DSAClaimApplicationController::class, 'getDsaClaimNumber']);

        //approval
        Route::resource('expense_approval', 'ExpenseApprovalController');
        Route::get('expense_dsa/{id}', [ExpenseApprovalController::class, 'showDsa']);
        Route::get('expense_dsa', [ExpenseApprovalController::class, 'indexDsa']);
        Route::get('expense_transfer_claim', [ExpenseApprovalController::class, 'indexTransfer']);
        Route::get('expense_transfer_claim/{id}', [ExpenseApprovalController::class, 'showTransferClaim']);
        Route::post('approval/bulk', [ApprovalController::class, 'approveReject']);
        Route::get('approval/{aaa}', [GeneralApporvalController::class, 'approvedApplications']);
        // Route::resource('approval', 'ExpenseApprovalController')->except('create', 'show', 'edit');
    });

    Route::namespace('Api\v1\TravelAuthorization')->middleware('auth:sanctum')->group(function () {
        Route::resource('travel_authorization', 'TravelAuthorizationApplicationController');
        Route::resource('travel_authorization_approval', 'TravelAuthorizationApprovalController');
        Route::get('travel_authorization_number/{id}', [TravelAuthorizationApplicationController::class, 'fetchTravelAuthorizationNumber']);
    });

    Route::namespace('Api\Delegation')->middleware('auth:sanctum')->group(function () {
        Route::resource('delegations', 'DelegationApiController')->except(['show']);
    });


    Route::namespace('Api\v1\Advance')->prefix('advance-loan')->group(function () {
        Route::get('gadget-emi/employees/', [AdvanceLoanGadgetEmiController::class, 'getEmployees']);
        Route::get('gadget-emi/{id}', [AdvanceLoanGadgetEmiController::class, 'index']);
        Route::get('gadget-emi/details/{id}', [AdvanceLoanGadgetEmiController::class, 'getDetailsByAdvance'])
            ->where('id', '.*');
    });

    Route::namespace('Api\Anniversary')->prefix('anniversary')->group(function () {
        Route::get('employees/', [AnniversaryController::class, 'getEmployees']);
        Route::get('employee/{id}', [AnniversaryController::class, 'getEmployeeById']); // No need for where() here
    });

    Route::get('test', function () {
        return response()->json(['message' => 'API is working']);
    });

    Route::namespace('Api\Advance')->middleware('auth:sanctum')->group(function () {
        Route::resource('advance_loan', 'AdvanceLoanApplicationApiController');
        Route::get('advance_loan_number/{id}', [AjaxRequestController::class, 'getAdvanceNumber']);
    });


    Route::namespace('Api\Leave')->middleware('auth:sanctum')->group(function () {
        Route::resource('leave', 'LeaveApplicationController');
        Route::get('leave_balance', [LeaveApplicationController::class, 'leaveBalance']);
        Route::resource('leave_encashment', 'LeaveEncashmentApplicationController');
        Route::get('leave_balance_chart/{current_year}', [LeaveApplicationController::class, 'getLeaveData']);
        Route::get('getleavebalancebyleavetype/{id}', [AjaxRequestController::class, 'getLeaveBalance']);
        Route::get('getnoofdaysbydate', [AjaxRequestController::class, 'getNoOfDays']);
        Route::get('validateleavecombination', [AjaxRequestController::class, 'validateLeaveCombinations']);

        //approval
        Route::resource('leave_encashment_approval', 'LeaveEncashmentApprovalController');
        Route::post('leave_encashment_approval/bulk', [LeaveEncashmentApprovalController::class, 'bulkApprovalRejection']);
        Route::resource('leave_approval', 'LeaveApprovalController');
    });

    // Route::namespace('Api\Attendance')->middleware('auth:sanctum')->group(function () {
    //     Route::resource('attendance', 'AttendanceApiController')->except(['update', 'store']);
    //     Route::put('/attendance/entry', 'AttendanceApiController@attendanceEntry');
    // });

    Route::middleware('auth:sanctum')->group(function () {
        Route::resource('attendance', AttendanceApiController::class)->except(['update', 'store']);
        // Route::get('/attendance/monthly', [AttendanceApiController::class, 'getEmpAttendance']);
        Route::put('/attendance/entry', [AttendanceApiController::class, 'attendanceEntry']);
    });

    // incoming data from SAP ERP to save store and item as SAP team will be pushing data
    Route::namespace('Api\SAP')->middleware('auth:sanctum')->group(function () {
        Route::post('save-stores', [ApiController::class, 'saveStore']);
        Route::post('save-items', [ApiController::class, 'saveItem']);
        Route::post('save-grn-items', [ApiController::class, 'saveGrnItemMapping']);
        Route::post('save-goods-issued', [ApiController::class, 'saveGoodsIssued']);
        Route::post('save-goods-issued-consumable', [ApiController::class, 'saveGoodIssueConsumable']);
        Route::post('save-site', [ApiController::class, 'saveSite']);
    });
    Route::namespace('Api')->middleware('auth:sanctum')->group(function () {
        Route::resource('holidays', 'HolidayListController');
        Route::get('notifications', [HolidayListController::class, 'notification']);
    });
    // Route::middleware('auth:sanctum')->group(function () {
    //     Route::get('advance-applications', [AdvanceLoanApplicationApiController::class, 'index']);
    //     Route::get('advance-applications/{id}', [AdvanceLoanApplicationApiController::class, 'show']);
    //     Route::post('advance-applications', [AdvanceLoanApplicationApiController::class, 'store']);
    //     Route::put('advance-applications/{id}', [AdvanceLoanApplicationApiController::class, 'update']);
    //     Route::delete('advance-applications/{id}', [AdvanceLoanApplicationApiController::class, 'destroy']);
    // });

});
