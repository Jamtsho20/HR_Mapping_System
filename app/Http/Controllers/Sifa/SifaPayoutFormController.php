<?php

namespace App\Http\Controllers\Sifa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SifaPayoutFormController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:sifa/sifa-payout,view')->only('index');
        $this->middleware('permission:sifa/sifa-payout,create')->only('store');
        $this->middleware('permission:sifa/sifa-payout,edit')->only('update');
        $this->middleware('permission:sifa/sifa-payout,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
      
        return view('sifa.sifa-payout.index',compact('privileges'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('sifa.sifa-payout.create');

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
