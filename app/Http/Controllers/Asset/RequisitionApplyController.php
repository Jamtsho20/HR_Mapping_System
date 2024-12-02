<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use App\Models\MasDepartment;
use App\Models\MasDzongkhag;
use App\Models\MasStore;
use Illuminate\Http\Request;

class RequisitionApplyController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function __construct()
     {
         $this->middleware('permission:asset/requisition-apply,view')->only('index');
    
     }
     public function index(Request $request)
     {
         $privileges = $request->instance();
         $user=auth()->user();
         $dzongkhags=MasDzongkhag::get();
         $stores=MasStore::get();
      
      
       
       
         return view('asset.requisition-apply.index', compact('privileges','user','dzongkhags','stores'));
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
