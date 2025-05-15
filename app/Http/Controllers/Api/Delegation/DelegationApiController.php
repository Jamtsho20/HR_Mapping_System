<?php

namespace App\Http\Controllers\Api\Delegation;

use \Validator;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Delegation\DelegationController;
use App\Models\Delegation;
use App\Models\User;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SystemMenu;
use Illuminate\Support\Facades\Auth;

class DelegationApiController extends Controller
{
    use JsonResponseTrait;
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    protected $rules = [
        'delegations.*.role' => 'required',
        'delegations.*.delegatee' => 'required',
        'delegations.*.start_date' => 'required|date',
        'delegations.*.end_date' => 'required|date|after_or_equal:start_date',
    ];

    protected $messages = [
        'delegations.*.role' => 'Role field is required.',
        'delegations.*.delegatee' => 'Delegatee field is required.',
        'delegations.*.start_date' => 'Start date field is required.',
        'delegations.*.end_date' => 'End date field is required.',
        'delegations.*.end_date.after_or_equal' => 'End date must be greater than or equal to start date.',
    ];

    public function index(Request $request)
    {
        try {
            $privileges = $request->instance();
            $delegations = Delegation::with(['delegator:id,name,username', 'role:id,name,description', 'delegatee:id,name,username'])->filter($request)->get();
            // dd(gettype($delegations[0]->delegator));
            return $this->successResponse($delegations);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
    public function create()
    {
        try {
            $employees = User::select(['id', 'name', 'username'])->get();

            $delegatorRoles = $this->delegatorRoles()->select(['id', 'name', 'description'])->values();
            return $this->successResponse(['employees' => $employees, 'delegatorRoles' => $delegatorRoles]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
    public function store(Request $request)
    {
        try {
            // Validate incoming request
            $validator = Validator::make($request->all(), $this->rules, $this->messages);
            if ($validator->fails()) {
                return $this->validationErrorResponse($validator->errors());
            }

            // Start database transaction
            DB::beginTransaction();

            try {
                // Loop through delegation data
                if ($request->has('delegations')) {
                    $delegations = [];
                    foreach ($request->delegations as $key => $value) {
                        $delegations[] = [
                            'delegator_id' => auth()->user()->id,
                            'role_id' => $value['role'],
                            'delegatee_id' => $value['delegatee'],
                            'start_date' => $value['start_date'],
                            'end_date' => $value['end_date'],
                            'remark' => $value['remark'],
                            'status' => $value['status'],
                            'created_by' => auth()->user()->id
                        ];
                    }

                    // Insert delegations and update roles
                    foreach ($delegations as $delegation) {
                        $newDelegation = Delegation::create($delegation);
                        $this->updateEmpRoles($delegation['delegator_id'], $delegation['role_id'], $delegation['status'] == 1 ? 1 : 0);
                    }
                }

                // Commit the transaction
                DB::commit();
                $user = Auth::user();
                $roleIds = $user->roles->pluck('id');
                $menus = $this->menuAccessibleByRole($roleIds, $user->id);

                // Send success response
                return $this->successResponse(['delegations' => $newDelegation, 'menus' => $menus], 'Delegation(s) have been created successfully.', 201);
            } catch (\Exception $e) {
                // Rollback in case of error
                DB::rollBack();

                // Log the error
                \Log::error('Delegation Store Error: ' . $e->getMessage());

                // Return error response
                return $this->errorResponse('Failed to store delegation(s). Please try again.', 500);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            return $this->errorResponse('Validation failed. Please check the data and try again.', 422, $e->errors());
        }
    }

    public function edit($id)
    {
        try {
            // Find the delegation by ID
            $delegation = Delegation::findOrFail($id);

            // Fetch all employees
            $employees = User::all();

            // Fetch the delegator roles
            $delegatorRoles = $this->delegatorRoles();

            // Prepare the response data
            $responseData = [
                'delegation' => $delegation,
                'delegatorRoles' => $delegatorRoles->pluck('name'), // Assuming you're using role names
                'employees' => $employees
            ];

            // Return the response
            return $this->successResponse($responseData, 'Delegation details retrieved successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse('Error retrieving delegation details: ' . $e->getMessage(), 500);
        }
    }
    public function update(Request $request, $id)
    {
        $rules = [
            'role' => 'required',
            'delegatee' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ];

        $messages = [
            'end_date.after_or_equal' => 'End date must be greater than or equal to start date.',
        ];

        $validator = \Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        DB::beginTransaction();
        try {
            $delegation = Delegation::findOrFail($id);

            $delegation->delegator_id = auth()->id();
            $delegation->role_id = $request->role;
            $delegation->delegatee_id = $request->delegatee;
            $delegation->start_date = $request->start_date;
            $delegation->end_date = $request->end_date;
            $delegation->remark = $request->remark;
            $delegation->status = $request->status;
            $delegation->updated_by = auth()->id();
            $delegation->save();

            $user = Auth::user();
            $roleIds = $user->roles->pluck('id');
            $menus = $this->menuAccessibleByRole($roleIds, $user->id);

            DB::commit();

            $this->updateEmpRoles($delegation->delegator_id, $delegation->role_id, $delegation->status == 1 ? 1 : 0);

            return $this->successResponse(['delegation' => $delegation, 'menus' => $menus], 'Delegation has been updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Delegation Update Error: ' . $e->getMessage());

            return $this->errorResponse('Failed to update delegation. Please try again.' . $e->getMessage(), 500);
        }
    }

    private function delegatorRoles()
    {
        $delegatorRoles = \Auth::user()->roles->filter(function ($role) {
            return strtolower($role->name) !== 'employee';
        });

        return $delegatorRoles;
    }
    //update mas_employee_roles table to indicate that particular role has delegation
    private function updateEmpRoles($delegator, $delegatorRole, $hasDelegation)
    {
        DB::table('mas_employee_roles')
            ->where('mas_employee_id', $delegator)
            ->where('role_id', $delegatorRole)
            ->update(['has_delegation' => $hasDelegation]);
    }
    private function menuAccessibleByRole($role, $userId)
    {
        $userRoles = $role->toArray();
        // Delegated roles (common function in helpers.php)
        $delegatedRole = delegatedRole($userId);
        // Merge and unique role that is original role and delegated role
        $allRoles = array_unique(array_merge($userRoles, $delegatedRole));

        $menus = SystemMenu::select('id', 'name', 'display_order')->with(['systemSubMenus' => function ($query) use ($allRoles) {
            $query->select('system_sub_menus.id', 'system_sub_menus.system_menu_id', 'system_sub_menus.name', 'system_sub_menus.route')
                ->join('role_permissions', 'system_sub_menus.id', '=', 'role_permissions.system_sub_menu_id') // Join role_permissions
                ->whereIn('role_permissions.role_id', $allRoles) // Check if the user has one of the roles
                ->where('role_permissions.view', 1) // Optional: Filter by view permission
                ->where('system_sub_menus.visible', 1) // Ensure the submenu is visible
                ->orderBy('system_sub_menus.display_order')
                ->addSelect([
                    'view' => 'role_permissions.view',  // Select the "view" permission
                    'edit' => 'role_permissions.edit',  // Select the "edit" permission
                    'create' => 'role_permissions.create', // Select the "create" permission
                    'delete' => 'role_permissions.delete'
                ]);
        }])
            ->orderBy('display_order')->get()
            ->filter(function ($menu) {
                return $menu->systemSubMenus->isNotEmpty();
            });


        return $menus->values();
    }
}
