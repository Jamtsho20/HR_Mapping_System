<?php

namespace App\Http\Controllers\MyProfile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MyPaySlipController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:my-profile/my-payslip,view')->only('index');
        $this->middleware('permission:my-profile/my-payslip,create')->only('store');
        $this->middleware('permission:my-profile/my-payslip,edit')->only('update');
        $this->middleware('permission:my-profile/my-payslip,delete')->only('destroy');
    }
}
