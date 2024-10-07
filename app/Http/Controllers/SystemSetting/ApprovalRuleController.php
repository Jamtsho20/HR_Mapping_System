<?php

namespace App\Http\Controllers\SystemSetting;

use App\Http\Controllers\Controller;
use App\Models\MasApprovalHead;
use App\Models\User;
use Illuminate\Http\Request;

class ApprovalRuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:system-setting/approval-rules,view')->only('index');
        $this->middleware('permission:system-setting/approval-rules,create')->only('store');
        $this->middleware('permission:system-setting/approval-rules,edit')->only('update');
        $this->middleware('permission:system-setting/approval-rules,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $heads = MasApprovalHead::get();

        return view('system-settings.approval-rule.index', compact('privileges','heads'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = User::get();
        $heads = MasApprovalHead::get();

        return view('system-settings.approval-rule.create', compact('employees','heads'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
}
