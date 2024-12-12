<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GoodCommissionApplication;
use App\Models\GoodReceiptApplication;

class CommissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:asset/commission,view')->only('index');
        $this->middleware('permission:asset/commission,create')->only('store');
        $this->middleware('permission:asset/commission,edit')->only('update');
        $this->middleware('permission:asset/commission,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $goods_commissions = GoodCommissionApplication::all();

        return view('asset.commission.index',compact('privileges', 'goods_commissions'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $receipts = GoodReceiptApplication::where('status',0)->get();
        return view('asset.commission.create',compact('receipts'));
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
