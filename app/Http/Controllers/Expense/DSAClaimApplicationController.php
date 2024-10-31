<?php

namespace App\Http\Controllers\Expense;

use App\Http\Controllers\Controller;
use App\Models\MasExpenseType;
use App\Models\User;
use Illuminate\Http\Request;

class DSAClaimApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('permission:expense/dsa-claim-settlement,view')->only('index');
        $this->middleware('permission:expense/dsa-claim-settlement,create')->only('store');
        $this->middleware('permission:expense/dsa-claim-settlement,edit')->only('update');
        $this->middleware('permission:expense/dsa-claim-settlement,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
               
        return view('expense.dsa-claim.index', compact( 'privileges'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //common function to generate combination of loggedInUser employeeId and username
        $empIdName = LoggedInUserEmpIdName(); 
        //get dsa advance which has been approved for settlement
        // $dsaAdvance = 
        return view('expense.dsa-claim.create', compact('empIdName'));

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
