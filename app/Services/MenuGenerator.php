<?php

namespace App\Services;

use App\Models\SystemMenu;

class MenuGenerator
{
    private $user;
    private $userRoles;

    public function __construct()
    {
        $this->user = auth()->user();
        $this->userRoles = $this->user->roles();
    }

    private function userRolesCastedToArray()
    {
        //pull users role_id's and convert it into array.
        return $this->userRoles->pluck('role_id')->toArray();
    }

    /**
     * links / pages accessible by the role
     *
     * @return \Illuminate\Http\Response
     */

    public function menuAccessibleByRole()
    {
        $userRoles = $this->userRolesCastedToArray();

        // Delegated roles (common function in helpers.php)
        $delegatedRole = delegatedRole(auth()->user()->id);

        // Merge and unique
        $allRoles = array_unique(array_merge($userRoles, $delegatedRole));

        $menus = SystemMenu::with(['systemSubMenus' => function ($query) use ($allRoles) {
            $query->whereIn('id', function ($q) use ($allRoles){
                $q->select('system_sub_menu_id')->from('role_permissions')->where('view', 1)->whereIn('role_id', $allRoles);
            })->orderBy('display_order');

            $query->where('visible', 1);
        }])
        ->orderBy('display_order')->get()
        ->filter(function ($menu) {
            return $menu->systemSubMenus->isNotEmpty();
        });
        // dd($menus);
        return $menus;
    }

    /**
     * return menus in array format
     *
     * @return \Illuminate\Http\Response
     */
    public function menuCastedToArray()
    {
        return $this->menuAccessibleByRole()->toArray();
    }

}
