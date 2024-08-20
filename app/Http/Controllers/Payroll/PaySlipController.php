<?php

namespace App\Http\Controllers\Payroll;

use App\Models\PaySlip;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaySlipController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:payroll/pay-slips,view')->only('index');
        $this->middleware('permission:payroll/pay-slips,create')->only('store');
        $this->middleware('permission:payroll/pay-slips,edit')->only('update');
        $this->middleware('permission:payroll/pay-slips,delete')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $paySlips = PaySlip::filter($request)->orderBy('for_month')->paginate(30);
        return view('payroll.pay-slips.index', compact('paySlips', 'privileges'));
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
