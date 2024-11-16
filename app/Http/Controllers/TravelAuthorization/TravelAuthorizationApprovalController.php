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
        
        
        $leaves = LeaveApplication::whereHas('histories', function ($query) use ($user) {
                                        $query->where('approver_emp_id', $user->id)
                                            ->where('application_type', \App\Models\LeaveApplication::class);
                                    })
                                    ->whereNotIn('status', [-1, 3])
                                    ->filter($request, false) //sent onesOenRecord parameter as flase as it need to fetch all despites of authenticated user
                                    ->orderBy('created_at')
                                    ->paginate(config('global.pagination'))
                                    ->withQueryString();

        return view('leave.approval.index', compact('privileges', 'leaves'));

        $privileges = $request->instance();
        $user = auth()->user();

        // $historyData = ApplicationHistory::whereHas('application', function ($query) {
        //     $query->where('application_type', 'App\Models\LeaveApplication'); // Assuming you store this class in 'application_type' column
        // })->where('approver_emp_id', $user->id)
        //   ->get();
        
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
