<?php

namespace App\Http\Controllers\TravelAuthorization;
use App\Http\Controllers\Controller;
use App\Models\TravelAuthorization;
use Illuminate\Http\Request;

class TravelAuthorizationApprovalController extends Controller
{
         
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('permission:travel-authorization/travel-authorization-approval,view')->only('index');
        $this->middleware('permission:travel-authorization/travel-authorization-approval,create')->only('store');
        $this->middleware('permission:travel-authorization/travel-authorization-approval,edit')->only('update');
        $this->middleware('permission:travel-authorization/travel-authorization-approval,delete')->only('destroy');
    }


    public function index(Request $request)
    {
        $privileges = $request->instance();
        $travelAuthorizations = TravelAuthorization::with('employee')->filter($request)->orderBy('created_at')->paginate(config('global.pagination'))
        ->withQueryString();
        return view('travel-authorizations.approval.index', compact( 'privileges', 'travelAuthorizations'));
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
