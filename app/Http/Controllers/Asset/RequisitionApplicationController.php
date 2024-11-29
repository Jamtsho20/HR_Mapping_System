<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use App\Models\MasRequisitionType;
use App\Models\RequisitionApplication;
use App\Services\ApprovalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequisitionApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function __construct()
     {
        $this->middleware('permission:asset/requisition,view')->only('index', 'show');
        $this->middleware('permission:asset/requisition,create')->only('store');
        $this->middleware('permission:asset/requisition,edit')->only('update');
        $this->middleware('permission:asset/requisition,delete')->only('destroy');
    
     }

     protected $rules = [
        'requisition_no' => 'required',
        'requisition_type' => 'required',
        'requisition_date' => 'required',
        'need_by_date' => 'required',
        'item_category' => 'required',
        'details.*.purchase_order_no' => 'required',
        'details.*.item_description' => 'required',
        'details.*.uom' => 'required',
        'details.*.store' => 'required',
        'details.*.quantity' => 'required',
        'details.*.dzongkhag' => 'required',
        'details.*.site_name' => 'required',
     ];

     protected $messages = [

     ];

     public function index(Request $request)
     {
         $privileges = $request->instance();
         return view('asset.requisition-apply.index', compact('privileges'));
     }
 
     /**
      * Show the form for creating a new resource.
      */
     public function create()
     {
        $reqTypes = MasRequisitionType::get();
         return view('asset.requisition-apply.create', compact('reqTypes'));
     }
 
     /**
      * Store a newly created resource in storage.
      */
     public function store(Request $request)
     {
        $requisition = new RequisitionApplication();
        $this->validate($request, $this->rules, $this->messages);
        $conditionFields = approvalHeadConditionFields(REQUISITION_APPVL_HEAD, $request); // fetching condition field for particular aprroval head
        $approvalService = new ApprovalService();
        $approverByHierarchy = $approvalService->getApproverByHierarchy($request->requisition_type, \App\Models\MasRequisitionType::class, $conditionFields ?? []);

        try {
            DB::beginTransaction();
            $requisition->requisition_no = $request->requisition_no;
            $requisition->requisition_type_id = $request->requisition_type;
            $requisition->requisition_date = $request->requisition_date;
            $requisition->need_by_date = $request->need_by_date;
            $requisition->item_category = $request->item_category;
           
            $requisition->status = $approverByHierarchy['application_status'];
            if($request->details){
                $this->saveDetails();
            }
            
            $requisition->save();

            // Create a corresponding history record for advance
            // Create a history record
            $requisition->histories()->create([
                'approval_option' => $approverByHierarchy['approval_option'],
                'hierarchy_id' => $approverByHierarchy['hierarchy_id'] ?? null,
                'level_id' => $approverByHierarchy['next_level']->id ?? null,
                'approver_role_id' => $approverByHierarchy['approver_details']['approver_role_id'],
                'approver_emp_id' => $approverByHierarchy['approver_details']['user_with_approving_role']->id,
                'level_sequence' => $approverByHierarchy['next_level']->sequence ?? null,
                'status' => $approverByHierarchy['application_status'],
                'remarks' => $request->remarks,
                'action_performed_by' => loggedInUser(),
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('msg_error', $e->getMessage());
            // return back()->withInput()->with('msg_error', GENERAL_ERR_MSG);
        }
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

     private function saveDetails () {
        
     }
}
