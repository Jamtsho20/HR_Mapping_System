<?php

namespace App\Http\Controllers\Leave;

use App\Http\Controllers\Controller;
use App\Models\ApplicationHistory;
use App\Models\LeaveApplication;
use App\Services\ApprovalListService;
use App\Services\ApprovalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaveApprovalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('permission:leave/approval,view')->only('index', 'show');
        $this->middleware('permission:leave/approval,create')->only('store');
        $this->middleware('permission:leave/approval,edit')->only('update', 'bulkApprovalRejection');
        $this->middleware('permission:leave/approval,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $user = auth()->user();
        // $historyData = ApplicationHistory::whereHas('application', function ($query) {
        //     $query->where('application_type', 'App\Models\LeaveApplication'); // Assuming you store this class in 'application_type' column
        // })->where('approver_emp_id', $user->id)
        //   ->get();
        $leaves = LeaveApplication::whereHas('histories', function ($query) use ($user) {
            $query->where('approver_emp_id', $user->id)
                  ->where('application_type', 'App\Models\LeaveApplication');
        })->orderBy('created_at')->paginate(config('global.pagination'))->withQueryString();
        return view('leave.approval.index', compact('privileges', 'leaves'));
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

    public function bulkApprovalRejection(Request $request) {
        $action = $request->action;
        $itemIds = $request->item_ids;
        $status = ($action === 'approve') ? 2 : -1;

        DB::beginTransaction();
        try{
            foreach($itemIds as $id){
                $leaveApplication = LeaveApplication::findOrFail($id);
                if($action == 'approve'){
                    $approvalService = new ApprovalService();
                    $applicationForwardedTo = $approvalService->applicationForwardedTo($request->item_ids, \App\Models\LeaveApplication::class);
                    $applicationHistory = $leaveApplication->histories->where('application_type', 'App\Models\LeaveApplication')->where('application_id', $id)->first();
                    if($applicationHistory){
                        $applicationHistory->update([
                            // 'level_id' => $applicationForwardedTo->level_id,
                            // 'approver_role_id' => $applicationForwardedTo->level_id,
                            // 'approver_emp_id' => $applicationForwardedTo->level_id,
                            'status' => $status,
                            'remarks' => $request->input('remarks', ''),
                            'action_performed_by' => auth()->id(),
                        ]);
                    }
                }else{

                }
            }

        }catch(\Exception $e){
            DB::rollBack();
            \Log::error('Bulk approval/rejection error: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred during bulk operation.'], 500);
        }
        // if($request->action !== 'reject'){
        //     $approvalService = new ApprovalService();
        //     $applicationForwardedTo = $approvalService->applicationForwardedTo($request->item_ids);
        //     $leaveApplication = new LeaveApplication();
        // }else{
        //     try{
        //         // foreach($request->){

        //         // }
        //     }catch(\Exception $e) {
        //         DB::rollBack();
        //         \Log::error('Bulk approval/rejection error: ' . $e->getMessage());
        //         return response()->json(['message' => 'An error occurred during bulk operation.'], 500);
        //     }
        // }
        // dd($request->all());
    }
}
