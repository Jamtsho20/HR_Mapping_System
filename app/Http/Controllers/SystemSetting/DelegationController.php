<?php

namespace App\Http\Controllers\SystemSetting;

use App\Http\Controllers\Controller;
use App\Models\Delegation;
use App\Models\User;
use Illuminate\Http\Request;

class DelegationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('permission:system-setting/delegations,view')->only('index');
        $this->middleware('permission:system-setting/delegations,create')->only('store');
        $this->middleware('permission:system-setting/delegations,edit')->only('update');
        $this->middleware('permission:system-setting/delegations,delete')->only('destroy');
    }

    protected $rules = [
        'delegations.*.role' => 'required',
        'delegations.*.delegatee' => 'required',
        'delegations.*.start_date' => 'required|date',
        'delegations.*.end_date' => 'required|date|after_or_equal:start_date',
    ];

    protected $messages = [
        'delegations.*.role' => 'Role field is required.',
        'delegations.*.delegatee' => 'Delegatee field is required.',
        'delegations.*.start_date' => 'Start date field is required.',
        'delegations.*.end_date' => 'End date field is required.',
        'delegations.*.end_date.after_or_equal' => 'End date must be greater than or equal to start date.',
    ];

    public function index(Request $request)
    {
        $privileges = $request->instance();
        $delegations = Delegation::with(['delegator', 'role', 'delegatee'])->filter($request)->paginate(config('global.pagination'))->withQueryString();
        // dd(gettype($delegations[0]->delegator));
        return view('system-settings.delegation.index', compact('privileges', 'delegations'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employees = User::all();

        $delegatorRoles = $this->delegatorRoles();

        return view('system-settings.delegation.create',compact('employees', 'delegatorRoles'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        dd($request->all());
        $this->validate($request, $this->rules, $this->messages);

        \DB::beginTransaction();
        try{
            if($request->has('delegations')){
                $delegations = [];
                foreach ($request->delegations as $key => $value) {
                    $delegations[] = [
                        'delegator_id' => auth()->user()->id,
                        'role_id' => $value['role'],
                        'delegatee_id' => $value['delegatee'],
                        'start_date' => $value['start_date'],
                        'end_date' => $value['end_date'],
                        'remark' => $value['remark'],
                        'status' => $value['status'],
                        'created_by' => auth()->user()->id
                    ];
                }
        
                foreach ($delegations as $delegation) {
                    Delegation::create($delegation);
                }
            }
            \DB::commit();
        }catch(\Exception $e){
            \Log::error('Delegation Insert Error:' . $e->getMessage());
            return back()->withInput()->with('msg_error', 'Failed to save delegations. Please try again.');
        }

        return redirect('system-setting/delegations')->with('msg_success', 'Delegation have been created successfully.');
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
        $delegation = Delegation::findOrFail($id);
        $employees = User::all();
        $delegatorRoles = $this->delegatorRoles();
        return view('system-settings.delegation.edit', compact('delegation', 'delegatorRoles', 'employees'));
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
        $rules = [
            'role' => 'required',
            'delegatee' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ];

        $messages = [
            'end_date.after_or_equal' => 'End date must be greater than or equal to start date.',
        ];

        $this->validate($request, $rules, $messages);
        
        $delegation = Delegation::findOrFail($id);
        $delegation->delegator_id = auth()->user()->id;
        $delegation->role_id = $request->role;
        $delegation->delegatee_id = $request->delegatee;
        $delegation->start_date = $request->start_date;
        $delegation->end_date = $request->end_date;
        $delegation->remark = $request->remark;
        $delegation->status = $request->status;
        $delegation->save();

        return redirect('system-setting/delegations')->with('msg_success', 'Delegation has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delegation = Delegation::findOrFail($id);
        $delegation->delete();
        return redirect('system-setting/delegations')->with('msg_success', 'Delegation has been deleted successfully.');
    }

    private function delegatorRoles(){
        $delegatorRoles = \Auth::user()->roles->filter(function ($role) { 
            return strtolower($role->name) !== 'employee';
        });

        return $delegatorRoles;
    }
}
