<?php

namespace App\Http\Middleware;

use App\Models\SystemSubMenu;
use App\Models\RolePermission;
use App\Services\DelegationService;
use Closure;

class CheckAccessibility
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  $route
     * @param  $verb
     * @return mixed
     */
    public function handle($request, Closure $next, $route, $verb)
    {
        $delegationService = new DelegationService();

        if (!$route && !$verb) {
            abort(403);
        }
        // $today = now()->toDateString();
        // $userId = auth()->user()->id;
        $userRoles = auth()->user()->roles()->pluck('role_id')->toArray();

        // Delegated roles (common function in DeleagtionService.php)
        $delegatedRole = $delegationService->delegatedRole(auth()->user()->id);
        
        // Merge and unique
        $allRoles = array_unique(array_merge($userRoles, $delegatedRole));

        $menuId = SystemSubMenu::where('route', $route)->pluck('id')->first();
        $accessibility = RolePermission::where('system_sub_menu_id', $menuId)->whereIn('role_id', $allRoles)->select('view', 'create', 'edit', 'delete')->first();
        // $accessibility = RolePermission::where('system_sub_menu_id', $menuId)->whereIn('role_id', $userRoles)->select('view', 'create', 'edit', 'delete')->first();

        if (!$accessibility) {
            abort(404);
        }

        //Pushing the roles access levels into the request instance. Can be globally accessed the action from the request instance
        $request->merge( array (
            "view" => $accessibility['view'],
            "create" => $accessibility['create'],
            'edit' => $accessibility['edit'],
            'delete' => $accessibility['delete']
        ));

        //forward the request only if the role is granted access.
        if ($accessibility[$verb] == 1) {
            return $next($request);
        }

        abort(403);
    }
}
