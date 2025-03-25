<?php

namespace App\Http\Controllers\WorkStructure;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BusinessUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('permission:work-structure/business-unit,view')->only('index');
        $this->middleware('permission:work-structure/business-unit,create')->only('store');
        $this->middleware('permission:work-structure/business-unit,edit')->only('update');
        $this->middleware('permission:work-structure/business-unit,delete')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $privileges = $request->instance();

        return view('work-structure.business-unit.index', compact('privileges'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }
    // public function updateLogo(Request $request)
    // {
    //     $request->validate([
    //         'logo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
    //     ]);

    //     if ($request->hasFile('logo')) {
    //         $file = $request->file('logo');
    //         $filename = 'logo.png'; // Override old logo
    //         $file->storeAs('public', $filename); // Save in storage/app/public/

    //         return back()->with('success', 'Logo updated successfully!');
    //     }

    //     return back()->with('error', 'Please select a valid image file.');
    // }
    public function updateLogo(Request $request)
    {
        $request->validate([
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
    
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoName = 'logo3.png'; // Keeping the same name to avoid changing file references
            $logoPath = public_path('assets/images/brand/' . $logoName);
    
            // Move the uploaded file and replace the existing logo
            $logo->move(public_path('assets/images/brand/'), $logoName);
        }
    
        return back()->with('success', 'Logo updated successfully!');
    }
    
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
