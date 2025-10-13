<?php

namespace App\Http\Controllers\MyProfile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MyTrainingController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:my-profile/my-training,view')->only('index');
        $this->middleware('permission:my-profile/my-training,create')->only('store');
        $this->middleware('permission:my-profile/my-training,edit')->only('update');
        $this->middleware('permission:my-profile/my-training,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $employee = auth()->user();
        return view('my-profile.my-training.index', compact('privileges', 'employee'));
    }

     public function create()
    {
        return view('my-profile.my-training.create');
    }
}
