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
use App\Models\DsaClaimApplication;
use App\Models\TransferClaimApplication;

class GeneralApporvalController extends Controller
{    use JsonResponseTrait;

    public function index(Request $request)
    {
        try {
            $user = auth()->user();

            // Common fetch function for counts
            $fetchCount = function ($modelClass, $relationships = [], $extraConditions = []) use ($request, $user) {
                return $modelClass::with($relationships)
                    ->whereHas('histories', function ($query) use ($user, $modelClass) {
                        $query->where('approver_emp_id', $user->id)
                            ->where('application_type', $modelClass); // Use class name directly
                    })
                    ->whereNotIn('status', [-1, 3])
                    ->filter($request, false)
                    ->when(!empty($extraConditions), function ($query) use ($extraConditions) {
                        $query->where($extraConditions);
                    })
                    ->count();
            };

            // Fetch counts for each application type
            $response = [
                'leave_applications_count' => $fetchCount(LeaveApplication::class, ['employee:id,name,username', 'leaveType:id,name']),
                'leave_encashment_applications_count' => $fetchCount(LeaveEncashmentApplication::class, ['employee:id,name,username'], [['created_at', '>=', Carbon::now()->startOfYear()]]),
                'advance_applications_count' => $fetchCount(AdvanceApplication::class, ['advanceType:id,name', 'employee:id,name,username', 'advance_approved_by:id,name']),
                'travel_authorization_applications_count' => $fetchCount(TravelAuthorizationApplication::class, ['employee:id,name,username', 'travelType:id,name']),
                'expense_applications_count' => $fetchCount(ExpenseApplication::class, ['type:id,name', 'employee:id,name,username']),
                'dsa_claim_applications_count' => $fetchCount(DsaClaimApplication::class, ['employee:id,name,username']),
                'transfer_claim_applications_count' => $fetchCount(TransferClaimApplication::class, ['type:id,name', 'employee:id,name,username']),
            ];

            return response()->json($response);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}

