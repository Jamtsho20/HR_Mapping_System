<?php

namespace App\Http\Controllers\Api\v1\TravelAuthorization;

use App\Http\Controllers\Controller;
use App\Models\ApplicationHistory;
use App\Models\TravelAuthorizationApplication;
use Illuminate\Http\Request;
use App\Traits\JsonResponseTrait;
use Carbon\Carbon;

class TravelAuthorizationApprovalController extends Controller
{
    use JsonResponseTrait;
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index(Request $request)
    {
        try {
            $user = auth()->user();
            $statuses = [];
            $applicationType = \App\Models\TravelAuthorizationApplication::class; // Default application type
            $tab = null;

            // Define conditions for filtering based on status
            switch ($request->input('status')) {
                case 'pending':
                    $statuses = [1, 2]; // Pending statuses
                    $tab = 'history';
                    break;
                case 'approved':
                    $statuses = [2, 3]; // Approved statuses
                    $tab = 'audit_logs';
                    break;
                case 'rejected':
                    $statuses = [-1]; // Rejected status
                    $tab = 'audit_logs'; // Adjust tab if needed
                    break;
                default:
                    return response()->json(['error' => 'Invalid status parameter'], 400);
            }

            // Build the query dynamically
            $travelAuthorizations = TravelAuthorizationApplication::with([
                'employee:id,name,username,contact_number',
                    'employee.empjob' => function ($query) {
                        $query->select('mas_employee_id', 'mas_department_id', 'mas_section_id', 'mas_designation_id');
                    },
                    'employee.empjob.designation:id,name',
                'employee.empjob.department:id,name',
                'employee.empjob.section:id,name',
                'histories:id,application_id,action_performed_by',  // Load necessary fields from the approval history
                'travelType:id,name',  // Include travel type
            ])
            ->when($tab === 'history', function ($query) use ($user, $applicationType) {
                $query->whereHas('histories', function ($query) use ($user, $applicationType) {
                    $query->where('approver_emp_id', $user->id)
                          ->where('application_type', $applicationType);
                });
            })
            ->when($tab === 'audit_logs', function ($query) use ($user, $applicationType, $statuses) {
                $query->whereHas('audit_logs', function ($query) use ($user, $applicationType, $statuses) {
                    $query->where('application_type', $applicationType)
                          ->where('action_performed_by', $user->id);
                })
                ->whereYear('created_at', Carbon::now()->year); // Apply the condition inside the callback
            })

            ->whereIn('status', $statuses)   // Filter by the statuses
            ->orderBy('created_at')  // Order by created date
            ->get();

            return response()->json([
                'success' => true,
                'message' => 'Travel authorization applications fetched successfully',
                'data' => $travelAuthorizations,
            ]);
        }
        catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }

    }

    public function show($id){
        try {
            $travelAuthorization = TravelAuthorizationApplication::findOrfail($id);
            $empDetails = empDetails($travelAuthorization->created_by);
            return response()->json(['travelAuthorization' => $travelAuthorization, 'empDetails' => $empDetails]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 404);
        }
    }
}
