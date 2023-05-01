<?php

namespace App\Http\Middleware;

use App\Models\SystemSubMenu;
use App\Models\RolePermission;
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
        if (!$route && !$verb) {
            abort(403);
        }

        $userRoles = auth()->user()->roles()->pluck('role_id')->toArray();
        $menuId = SystemSubMenu::where('route', $route)->pluck('id')->first();
        $accessibility = RolePermission::where('system_sub_menu_id', $menuId)->whereIn('role_id', $userRoles)->select('view', 'create', 'edit', 'delete')->first();

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
