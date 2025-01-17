<?php

namespace App\Http\Controllers\Api\Expense;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExpenseApplication;
use App\Models\MasExpenseType;
use App\Services\ApprovalService;
use App\Traits\JsonResponseTrait;
use App\Models\DsaClaimApplication;
use App\Models\TransferClaimApplication;
use Carbon\Carbon;
use App\Models\ApplicationHistory;

class ExpenseApprovalController extends Controller
{
    use JsonResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index(Request $request)
    {

        try {
            $currentUser = auth()->user();
            $employeeDetails = LoggedInUserEmpIdName();

            $statusParam = $request->input('status'); // E.g., 'pending', 'approved', 'rejected'
            $statuses = [];
            $applicationType = \App\Models\ExpenseApplication::class; // Default application type
            $tab = null;

            // Define conditions based on the status parameter
            switch ($statusParam) {
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
            $expenseApplications = ExpenseApplication::with('type:id,name')
                ->with([
                   'employee:id,name,username,contact_number',
                    'employee.empjob' => function ($query) {
                        $query->select('mas_employee_id', 'mas_department_id', 'mas_section_id', 'mas_designation_id');
                    },
                    'employee.empjob.designation:id,name',
                    'employee.empjob.department:id,name',
                    'employee.empjob.section:id,name',
                    'histories:id,application_id,action_performed_by',
                ])->with('vehicle.vehicleType:id,name')
                ->when($tab === 'history', function ($query) use ($currentUser, $applicationType, $statuses) {
                    $query->whereHas('histories', function ($query) use ($currentUser, $applicationType) {
                        $query->where('approver_emp_id', $currentUser->id)
                              ->where('application_type', $applicationType);
                    });
                })
                ->when($tab === 'audit_logs', function ($query) use ($currentUser, $applicationType, $statuses) {
                    $query->whereHas('audit_logs', function ($query) use ($currentUser, $applicationType, $statuses) {
                        $query->where('application_type', $applicationType)
                              ->where('action_performed_by', $currentUser->id);
                    })
                    ->whereYear('created_at', Carbon::now()->year); // Add condition for audit_logs
                })
                ->whereIn('status', $statuses) // Filter based on statuses
                ->filter($request, false)
                ->orderBy('created_at')
                ->get();


                $mappedModel = ExpenseApplication::class;
            $expenseApplications = $expenseApplications->map(function ($expense) use ($mappedModel) {
                $expense->rejectRemarks = ApplicationHistory::where('application_type', $mappedModel)
                    ->where('application_id', $expense->id)
                    ->value('remarks');
                return $expense;
            });
            return response()->json([
                'success' => true,
                'message' => 'Expense applications retrieved successfully!',
                'data' => $expenseApplications,
            ]);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 404);
        }

    }

    public function indexDsa(Request $request)
    {
        try {
            $currentUser = auth()->user();
            $employeeDetails = LoggedInUserEmpIdName();

            $statusParam = $request->input('status'); // E.g., 'pending', 'approved', 'rejected'
            $statuses = [];
            $applicationType = \App\Models\DSAClaimApplication::class; // Default application type
            $tab = null;

            // Define conditions based on the status parameter
            switch ($statusParam) {
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
            $dsaclaims = DSAClaimApplication::with([
                    'employee:id,name,username,contact_number',
                    'employee.empjob' => function ($query) {
                        $query->select('mas_employee_id', 'mas_department_id', 'mas_section_id', 'mas_designation_id');
                    },
                    'employee.empjob.designation:id,name',
                    'employee.empjob.department:id,name',
                    'employee.empjob.section:id,name',
                    'travel:id,travel_authorization_no',
                    'dsaadvance:id,advance_no',
                    'histories:id,application_id,action_performed_by',
            ])
                ->when($tab === 'history', function ($query) use ($currentUser, $applicationType) {
                    $query->whereHas('histories', function ($query) use ($currentUser, $applicationType) {
                        $query->where('approver_emp_id', $currentUser->id)
                              ->where('application_type', $applicationType);
                    });
                })
                ->when($tab === 'audit_logs', function ($query) use ($currentUser, $applicationType, $statuses) {
                    $query->whereHas('audit_logs', function ($query) use ($currentUser, $applicationType, $statuses) {
                        $query->where('application_type', $applicationType)
                              ->where('action_performed_by', $currentUser->id);
                    })
                    ->whereYear('created_at', Carbon::now()->year); // Add condition for audit_logs
                })
                ->whereIn('status', $statuses) // Filter based on statuses
                ->filter($request, false)
                ->orderBy('created_at')
                ->get();


                $mappedModel = DSAClaimApplication::class;
                $dsaclaims = $dsaclaims->map(function ($dsaclaim) use ($mappedModel) {
                    $dsaclaim->rejectRemarks = ApplicationHistory::where('application_type', $mappedModel)
                        ->where('application_id', $dsaclaim->id)
                        ->value('remarks');
                    return $dsaclaim;
                });
            return response()->json([
                'success' => true,
                'message' => 'DSA claim applications retrieved successfully!',
                'data' => $dsaclaims,
            ]);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 404);
        }

}


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function indexTransfer(Request $request)
    {
        try {
            $currentUser = auth()->user();
            $employeeDetails = LoggedInUserEmpIdName();

            $statusParam = $request->input('status'); // E.g., 'pending', 'approved', 'rejected'
            $statuses = [];
            $applicationType = 'App\Models\TransferClaimApplication'; // Default application type
            $tab = null;

            // Define conditions based on the status parameter
            switch ($statusParam) {
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
            $transferClaims = TransferClaimApplication::with('type:id,name')
                ->with([
                    'employee:id,name,username,contact_number',
                    'employee.empjob' => function ($query) {
                        $query->select('mas_employee_id', 'mas_department_id', 'mas_section_id', 'mas_designation_id');
                    },
                    'employee.empjob.designation:id,name',
                    'employee.empjob.department:id,name',
                    'employee.empjob.section:id,name',
                    'histories:id,application_id,action_performed_by',
                ])
                ->when($tab === 'history', function ($query) use ($currentUser, $applicationType) {
                    $query->whereHas('histories', function ($query) use ($currentUser, $applicationType) {
                        $query->where('approver_emp_id', $currentUser->id)
                              ->where('application_type', $applicationType);
                    });
                })
                ->when($tab === 'audit_logs', function ($query) use ($currentUser, $applicationType, $statuses) {
                    $query->whereHas('audit_logs', function ($query) use ($currentUser, $applicationType, $statuses) {
                        $query->where('application_type', $applicationType)
                              ->where('action_performed_by', $currentUser->id);
                    })
                    ->whereYear('created_at', Carbon::now()->year); // Add condition for audit_logs
                })
                ->whereIn('status', $statuses) // Filter based on statuses
                ->filter($request, false)
                ->orderBy('created_at')
                ->get();


                $mappedModel = TransferClaimApplication::class;
                $transferClaims = $transferClaims->map(function ($transferClaim) use ($mappedModel) {
                    $transferClaim->rejectRemarks = ApplicationHistory::where('application_type', $mappedModel)
                        ->where('application_id', $transferClaim->id)
                        ->value('remarks');
                    return $transferClaim;
                });

            return response()->json([
                'success' => true,
                'message' => 'Transfer claim applications retrieved successfully!',
                'data' => $transferClaims,
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 404);
        }

}
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            $expense = ExpenseApplication::findOrfail($id);

            return $this->successResponse($expense, 'Expense application retrieved successfully');
        }catch(\Exception $e){
            return $this->errorResponse($e->getMessage(), 404);
        }


    }

    public function showDsa($id)
    {
        try{
            $dsa = DsaClaimApplication::with('dsaClaimDetails')->findOrfail($id);
            return $this->successResponse($dsa, 'DSA claim application retrieved successfully');
       }catch(\Exception $e){
            return $this->errorResponse($e->getMessage(), 404);
        }
    }

    public function showTransferClaim($id)
    {
       try{
         $transfer = TransferClaimApplication::findOrfail($id);
         return $this->successResponse($transfer, 'Transfer claim application retrieved successfully');
        }catch(\Exception $e){
            return $this->errorResponse($e->getMessage(), 404);
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
