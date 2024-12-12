<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Traits\JsonResponseTrait;
use App\Models\LeaveApplication;
use App\Models\LeaveEncashmentApplication;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\AdvanceApplication;
use App\Models\TravelAuthorizationApplication;
use App\Models\ExpenseApplication;
use App\Models\DSAClaimApplication;
use App\Models\TransferClaimApplication;

class GeneralApporvalController extends Controller
{
    use JsonResponseTrait;

    public function index(Request $request)
{
    try{
        $user = auth()->user();

    // Fetch Leave Applications
    $leaveCount = LeaveApplication::with('employee:id,name,username', 'leaveType:id,name')
        ->whereHas('histories', function ($query) use ($user) {
            $query->where('approver_emp_id', $user->id)
                ->where('application_type', \App\Models\LeaveApplication::class);
        })
        ->whereNotIn('status', [-1, 3])
        ->filter($request, false) // Ensure proper filtering
        ->count();

    // Fetch Earned Leave Applications
    $earnedLeaveCount = LeaveEncashmentApplication::with('employee:id,name,username')
        ->whereHas('histories', function ($query) use ($user) {
            $query->where('approver_emp_id', $user->id)
                ->where('application_type', \App\Models\LeaveEncashmentApplication::class);
        })
        ->whereYear('created_at', Carbon::now()->year)
        ->whereNotIn('status', [-1, 3])
        ->count();

    // Fetch Advance Applications
    $advanceCount = AdvanceApplication::with('advanceType:id,name', 'employee:id,name,username', 'advance_approved_by:id,name')
        ->whereHas('histories', function ($query) use ($user) {
            $query->where('approver_emp_id', $user->id)
                ->where('application_type', \App\Models\AdvanceApplication::class);
        })
        ->whereNotIn('status', [-1, 3])
        ->filter($request, false)
        ->count();

    // Fetch Travel Authorization Applications
    $travelAuthorizationCount = TravelAuthorizationApplication::with('employee:id,name,username', 'travelType:id,name')
        ->whereHas('histories', function ($query) use ($user) {
            $query->where('approver_emp_id', $user->id)
                ->where('application_type', \App\Models\TravelAuthorizationApplication::class);
        })
        ->whereNotIn('status', [-1, 3])
        ->filter($request, false)
        ->count();

    // Fetch Expense Applications
    $expenseCount = ExpenseApplication::with('expenseType:id,name')->with('employee:id,name,username')->whereHas('histories', function ($query) use ($user) {
        $query->where('approver_emp_id', $user->id)
            ->where('application_type', \App\Models\ExpenseApplication::class);
    })
        ->whereNotIn('status', [-1, 3])
        ->filter($request, false)
        ->count();

    // Fetch DSA Claim Applications
    $dsaClaimCount = DSAClaimApplication::with('employee:id,name,username')->whereHas('histories', function ($query) use ($user) {
        $query->where('approver_emp_id', $user->id)
            ->where('application_type', \App\Models\DSAClaimApplication::class);
    })
        ->whereNotIn('status', [-1, 3])
        ->filter($request, false)
        ->count();

    // Fetch Transfer Claim
    $transferCount = TransferClaimApplication::with('type:id,name')->with('employee:id,name,username')->whereHas('histories', function ($query) use ($user) {
        $query->where('approver_emp_id', $user->id)
            ->where('application_type', \App\Models\TransferClaimApplication::class);
    })
        ->whereNotIn('status', [-1, 3])
        ->filter($request, false)
        ->count();
    // Prepare JSON response
    $response = [
        'leave_applications_count' => $leaveCount,
        'leave_encashment_applications_count' => $earnedLeaveCount,
        'advance_applications_count' => $advanceCount,
        'travel_authorization_applications_count' => $travelAuthorizationCount,
        'expense_applications_count' => $expenseCount,
        'dsa_claim_applications_count' => $dsaClaimCount,
        'transfer_claim_applications_count' => $transferCount,
    ];

    return response()->json($response);
}catch(\Exception $e){
    return $this->errorResponse($e->getMessage(), 500);
}

}
}
