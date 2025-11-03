<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Services\MenuGenerator;
use App\Models\TrainingEvaluation;

class SidebarMenuComposer
{
    private $menus;

    public function __construct(MenuGenerator $menu)
    {
        $this->menus = $menu->menuAccessibleByRole();
    }

    public function compose(View $view)
    {
        $user = auth()->user();

        // Roles that should always see the Training menu
        $alwaysSeeTrainingRoles = ['Administrator', 'administrator', 'Human Resource', 'HR Manager'];

        // Check if the user has any assigned training evaluations
        $hasAssignedTrainings = TrainingEvaluation::whereHas('assignedEmployees', function ($q) use ($user) {
            $q->where('mas_employees.id', $user->id);
        })->exists();

        // Filter menus: hide Training menu if user has no assigned trainings

        $filteredMenus = $this->menus->filter(function ($menu) use ($user, $hasAssignedTrainings, $alwaysSeeTrainingRoles) {
            if ($menu->name === 'Training') {
                // Check if user has any role in $alwaysSeeTrainingRoles
                $userRoleNames = $user->roles->pluck('name')->toArray();
                if (count(array_intersect($userRoleNames, $alwaysSeeTrainingRoles)) > 0) {
                    return true; // always show for admin roles
                }

                // Otherwise, only show if user has assigned trainings
                return $hasAssignedTrainings;
            }
            return true; // keep other menus
        });



        $view->with('menus', $filteredMenus);
    }
}
