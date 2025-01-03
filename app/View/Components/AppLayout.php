<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;
https://www.fujitsu.com/global/products/computing/pc/
class AppLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.app');
    }
}
