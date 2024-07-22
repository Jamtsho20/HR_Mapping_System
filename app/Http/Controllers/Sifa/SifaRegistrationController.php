<?php

namespace App\Http\Controllers\Sifa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SifaRegistration;
use Illuminate\Support\Facades\Storage;

class SifaRegistrationController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:sifa/sifa-registration,view')->only('index');
        $this->middleware('permission:sifa/sifa-registration,create')->only('store');
        $this->middleware('permission:sifa/sifa-registration,edit')->only('update');
        $this->middleware('permission:sifa/sifa-registration,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
      
        return view('sifa.sifa-registration.index',compact('privileges'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('sifa.sifa-registration.create');

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
