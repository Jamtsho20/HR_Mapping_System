<?php

namespace App\Http\Controllers\Leave;

use App\Http\Controllers\Controller;
use App\Models\MasGrade;
use App\Models\MasLeavePolicy;
use App\Models\MasLeaveType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeavePolicyController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:leave/leave-policy,view')->only('index');
        $this->middleware('permission:leave/leave-policy,create')->only('create');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $leaves = MasLeaveType::get();
        return view('leave.leave-policy.index', compact('privileges', 'leaves'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $leaves = MasLeaveType::get();
        $grades=MasGrade::get();

   
        return view('leave.leave-policy.create', compact('leaves',  'grades'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try{
            $this->saveLeavePolicy($request->leavePolicy);
            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
        }

        return redirect('')->with('msg_success', 'leave ');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    private function saveLeavePolicy($leavePolicy){
        $leavePolicy = new MasLeavePolicy();
        // $leavePolicy->mas-leave_typ_id = 
    }
}
