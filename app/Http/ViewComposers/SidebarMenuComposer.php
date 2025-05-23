<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Services\MenuGenerator;

class SidebarMenuComposer
{
    private $menus;

    public function __construct(MenuGenerator $menu)
    {
        $this->menus = $menu->menuAccessibleByRole();
    }

    public function compose(View $view)
    {
        $view->with('menus', $this->menus);
    }
}