<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MasItemsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:asset/mas-item,view')->only('index');
        $this->middleware('permission:asset/mas-item,create')->only('store');
        $this->middleware('permission:asset/mas-item,edit')->only('update');
        $this->middleware('permission:asset/mas-item,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
        return view('asset.mas-item.index', compact('privileges'));
    }
}
