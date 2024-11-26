<?php

namespace App\Http\Controllers\Sifa;

use App\Http\Controllers\Controller;
use App\Models\SifaRegistration;
use App\Models\User;
use Illuminate\Http\Request;

class SifaRegisteredUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:sifa/sifa-registered-user,view')->only('index','show');
        $this->middleware('permission:sifa/sifa-registered-user,create')->only('store');
        $this->middleware('permission:sifa/sifa-registered-user,edit')->only('update');
        $this->middleware('permission:sifa/sifa-registered-user,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
         // You can filter by employee ID if provided in the request
         $sifaRegistrations = SifaRegistration::with([
            'employee.empJob.designation', // Load employee's designation via empJob
            'employee.empJob.department'  // Load employee's department via empJob
        ])->get();
        //d($sifaRegistrations);
        return view('sifa.sifa-registered-user.index', compact('privileges','sifaRegistrations'));
    }
    public function show($id, Request $request)
    {
        $user = auth()->user();
        $sifaRegistrations = SifaRegistration::with([
            'employee.empJob.designation', // Load employee's designation via empJob
            'employee.empJob.department'  // Load employee's department via empJob
        ])->get();

        return view('sifa.sifa-approval.show', compact('sifaRegistrations'));
    }
}
