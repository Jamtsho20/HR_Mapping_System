<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\MasAdvanceLoan;
use Illuminate\Http\Request;

class AdvanceLoanController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:master/advance-loans,view')->only('index');
        $this->middleware('permission:master/advance-loans,create')->only('store');
        $this->middleware('permission:master/advance-loans,edit')->only('update');
        $this->middleware('permission:master/advance-loans,delete')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $advances = MasAdvanceLoan::filter($request)->orderBy('name')->paginate(30);
        $privileges = $request->instance();
        return view('masters.advance-loan.index',compact('privileges','advances'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('masters.advance-loan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $advance = new MasAdvanceLoan();
        $advance->name = $request->name;
        $advance->save();

        return redirect('master/advance-loans')->with('msg_success', 'Advance/Loan created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(MasAdvanceLoan $masAdvanceLoan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $advance = MasAdvanceLoan::findOrFail($id);
        return view('masters.advance-loan.edit', compact('advance'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
          
            'name' => 'required',
        ]);

        $advance = MasAdvanceLoan::findOrFail($id);   
        $advance->name = $request->name;
        $advance->save();

        return redirect('master/advance-loans')->with('msg_success', 'Advance/Loan updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            MasAdvanceLoan::findOrFail($id)->delete();

            return back()->with('msg_success', 'Advance/Loan has been deleted');
        } catch (\Exception $e) {
            return back()->with('msg_error', 'Advance/Loan cannot be delete as it has been used by other module. For further information contact system admin.');
        }
    }
}
