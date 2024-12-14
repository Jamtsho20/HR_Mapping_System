<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GoodCommissionApplication;

class CommissionApprovalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:asset/commission-approval,view')->only('index');
        $this->middleware('permission:asset/commission-approval,create')->only('store');
        $this->middleware('permission:asset/commission-approval,edit')->only('update');
        $this->middleware('permission:asset/commission-approval,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $user = auth()->user();
        $goods_commissions = GoodCommissionApplication::whereHas('histories', function ($query) use ($user) {
            $query->where('approver_emp_id', $user->id)
                ->where('application_type', 'App\Models\GoodCommissionApplication');
        })->whereNotIn('status', [-1, 3]) // Exclude rejected and canceled applications

            ->with('employee:id,name,username')
            ->orderBy('created_at')
            ->paginate(config('global.pagination'))
            ->withQueryString();

        return view('asset.commission-approval.index',compact('privileges', 'goods_commissions'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
