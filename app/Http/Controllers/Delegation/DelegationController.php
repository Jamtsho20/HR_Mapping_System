<?php
namespace App\Http\Controllers\Delegation;

use App\Http\Controllers\Controller;
use App\Models\Delegation;
use App\Services\DelegationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DelegationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('permission:delegation/delegations,view')->only('index');
        $this->middleware('permission:delegation/delegations,create')->only('store');
        $this->middleware('permission:delegation/delegations,edit')->only('update');
        $this->middleware('permission:delegation/delegations,delete')->only('destroy');
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
        return view('delegation.index', compact('privileges', 'delegations'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $delegationService = new DelegationService();
        $delegatorRoles = $this->delegatorRoles();
        $roleNames = $delegatorRoles->pluck('id')->toArray(); // Convert to array of names
        $roleId = null;
        // Define constants or replace them with actual values
        $priorityRoles = [
            MANAGING_DIRECTOR,
            IMMEDIATE_HEAD,
            DEPARTMENT_HEAD
        ];

        foreach ($priorityRoles as $priorityRole) {
            if (in_array($priorityRole, $roleNames)) {
                $roleId = $priorityRole;
                break;
            }
        }

        $employees = $delegationService->getDeleagteeList($roleId);
        
        return view('delegation.create',compact('delegatorRoles', 'employees'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->rules, $this->messages);

        DB::beginTransaction();
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
                    $this->updateEmpRoles($delegation['delegator_id'], $delegation['role_id'], $delegation['status'] == 1 ? 1 : 0);
                }
            }
            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
            \Log::error('Delegation Insert Error:' . $e->getMessage());
            return back()->withInput()->with('msg_error', 'Failed to save delegations. Please try again.');
        }

        return redirect('delegation/delegations')->with('msg_success', 'Delegation have been created successfully.');
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
        $delegationService = new DelegationService();
        $delegation = Delegation::findOrFail($id);
        $employees = $delegationService->getDeleagteeList($delegation->role_id);
        $delegatorRoles = $this->delegatorRoles();
        return view('delegation.edit', compact('delegation', 'delegatorRoles', 'employees'));
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
        DB::beginTransaction();
        try{
            $delegation = Delegation::findOrFail($id);
            $delegation->delegator_id = auth()->user()->id;
            $delegation->role_id = $request->role;
            $delegation->delegatee_id = $request->delegatee;
            $delegation->start_date = $request->start_date;
            $delegation->end_date = $request->end_date;
            $delegation->remark = $request->remark;
            $delegation->status = $request->status;
            $delegation->save();
            DB::commit();
            $this->updateEmpRoles($delegation->delegator_id, $delegation->role_id, $delegation['status'] == 1 ? 1 : 0);
        }catch(\Exception $e){
            DB::rollBack();
            \Log::error('Delegation Update Error:' . $e->getMessage());
            return back()->withInput()->with('msg_error', 'Failed to update delegation. Please try again.');
        }

        return redirect('delegation/delegations')->with('msg_success', 'Delegation has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $hasDelegation = 0;
        try{
            $delegation = Delegation::findOrFail($id);
            $delegation->delete();
            $this->updateEmpRoles($delegation->delegator_id, $delegation->role_id, $hasDelegation);
            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
            return back()->withInput()->with('msg_error', 'Failed to delete delegation. Please try again.');
        }
        return redirect('delegation/delegations')->with('msg_success', 'Delegation has been deleted successfully.');
    }

    private function delegatorRoles(){
        $delegatorRoles = \Auth::user()->roles->filter(function ($role) { 
            return strtolower($role->name) !== 'employee';
        });

        return $delegatorRoles;
    }

    //update mas_employee_roles table to indicate that particular role has delegation
    private function updateEmpRoles($delegator, $delegatorRole, $hasDelegation){
        DB::table('mas_employee_roles')
            ->where('mas_employee_id', $delegator)
            ->where('role_id', $delegatorRole)
            ->update(['has_delegation' => $hasDelegation]);
    }
}
