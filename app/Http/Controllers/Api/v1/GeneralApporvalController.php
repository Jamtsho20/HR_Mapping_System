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
use App\Models\RequisitionApplication;
use App\Models\AssetCommissionApplication;
use App\Models\MasApprovalHead;
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
                'requisition_applications_count' => $fetchCount(RequisitionApplication::class, ['type:id,name', 'employee:id,name,username']),
                'commission_applications_count' => $fetchCount(AssetCommissionApplication::class, ['commissionType:id,name', 'employee:id,name,username']),
            ];

            return response()->json($response);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function approvedApplications(Request $request, $model)
{
    $privileges = $request->instance();
    $privileges['view'] = 1;
    $headers = MasApprovalHead::all();
    $user = auth()->user();

    $applicationModels = config('global.applications');

    $results = collect();

    // Fetch statuses from the query parameter
    $statusParam = $request->input('status'); // E.g., 'approved', 'rejected'
    $statuses = [];

    // Define status conditions based on the parameter
    switch ($statusParam) {
        case 'approved':
            $statuses = [2, 3]; // Approved statuses
            break;
        case 'rejected':
            $statuses = [-1]; // Rejected status
            break;
        default:
            return response()->json(['error' => 'Invalid status parameter'], 400);
    }

    // Helper method to apply common query logic
    $applyQuery = function ($modelClass, $user, $request) use ($statuses) {
        return $modelClass::whereHas('audit_logs', function ($query) use ($user, $modelClass, $statuses) {
            $query->where('application_type', $modelClass)
                  ->where('action_performed_by', $user->id);
        })
        ->whereIn('status', $statuses)
        ->filter($request, false)
        ->whereYear('created_at', Carbon::now()->year)
        ->orderBy('created_at')
        ->get()
       ;
    };

    foreach ($applicationModels as $key => $model) {
        $modelClass = $model['name'];
        $friendlyName = class_basename($modelClass);
        $data = $applyQuery($modelClass, $user, $request);

        if ($statusParam === 'approved') {
            // Transform the items in the collection directly
            $data->transform(function ($item) {
                $item->status = 3; // Set status to 3 for approved
                return $item;
            });
        }

        $results->put($friendlyName, $data);
    }

    return response()->json(

        $results
    );
}

}

