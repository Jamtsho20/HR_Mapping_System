<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MasAttendanceFeature;
use App\Models\MasOfficeTiming;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\SystemMenu;
use Carbon\Carbon;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);
       
        try {
            $user = User::with([
                'empJob.department:id,name,mas_employee_id',      // Only load the department name
                'empJob.department.departmentHead:id,name',
                'empJob.section:id,name',         // Only load the section name
                'empJob.designation:id,name',     // Only load the designation name
                'empJob.grade:id,name',           // Only load the grade name
                'empJob.gradeStep:id,name',       // Only load the grade step name
                'empJob.empType:id,name',         // Only load the employment type name
                'empJob.supervisor:id,name,username', // Only load the supervisor's name
                // 'empJob.office:id,name,latitude,longitude,raidus',           // Only load the office name
                'empJob.office:id,name',           // Only load the office name
                // 'empJob.office:id,name',           // Only load the office name
                'roles:id,name',
                // 'employeeInShifts.departmentShift:id,start_time,end_time',
            ])->where('email', $request->username)
                ->orWhere('username', $request->username)
                ->first();

            $roleIds = $user->roles->pluck('id'); // Returns a collection of IDs
            // $attendanceFeatures = MasAttendanceFeature::whereStatus(1)->get(['id', 'name', 'is_mandatory']);
            
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            if(!$user->is_active){
                return response()->json(['message' => 'User is inactive'], 404);
            }

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'message' => 'Invalid username or password.'
                ], 401);
            }
            // $officeTiming = getEffectiveOfficeTiming($user);

            $menus = $this->menuAccessibleByRole($roleIds, $user->id);
            $token = $user->createToken($request->username)->plainTextToken;

            return response()->json([
                'message' => 'Authenticated',
                'user' => $user,
                'menus' => $menus,
                // 'attendance_features' => $attendanceFeatures,
                // 'office_timings' => $officeTiming,
                'token' => $token,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                // 'message' => 'Something went wrong. Try again later',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function sapLogin(Request $request) //login for SAP user
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
            // 'sap_client_id' => 'required', // Optional: Additional identifier for SAP ERP
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken($request->username)->plainTextToken;
        
        return response()->json([
            'message' => 'Authenticated',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ]);
    }


    //sent password reset lint
    public function handleForgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => __('passwords.sent')], 200)
            : response()->json(['message' => __('passwords.user')], 400);
    }

    //change password
    public function handleChangePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required|same:new_password'
        ]);

        try {
            if (Hash::check($request->input('current_password'), $request->user()->password)) {
                $request->user()->update([
                    'password' => bcrypt($request->input('new_password'))
                ]);
                return response()->json([
                    'message' => 'Password has been updated successfully.',
                ], 200);
            }
            return response()->json([
                'message' => 'Your old password did not match our records.',
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong.',
            ], 500);
        }
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
