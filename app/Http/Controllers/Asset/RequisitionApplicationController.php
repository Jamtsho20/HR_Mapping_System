<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use App\Mail\ApplicationForwardedMail;
use App\Models\GrnItemMapping;
use App\Models\MasRequisitionType;
use App\Models\RequisitionApplication;
use App\Models\RequisitionDetail;
use App\Services\ApprovalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Services\ApplicationHistoriesService;
use App\Models\MasSite;
use App\Models\MasDzongkhag;

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
        // 'requisition_no' => 'required|unique:requisition_applications,requisition_no',
        'type_id' => 'required',
        'requisition_date' => 'required',
        'need_by_date' => 'required',
        'details.*.grn_no' => 'required',
        'details.*.item_description' => 'required',
        'details.*.uom' => 'required',
        'details.*.store' => 'required',
        // 'details.*.stock_status' => 'required',
        'details.*.quantity_required' => 'required',
        'details.*.dzongkhag' => 'required',
        // 'details.*.office_name' => 'required',
        'details.*.site_name' => 'required',
     ];

     protected $messages = [
        'details.*.grn_no.required' => 'The GRN is required for each detail item.',
        'details.*.item_description.required' => 'The item description is required for each detail item.',
        'details.*.uom.required' => 'The unit of measure is required for each detail item.',
        'details.*.store.required' => 'The store is required for each detail item.',
        // 'details.*.stock_status.required' => 'The stock status is required for each detail item.',
        'details.*.quantity_required.required' => 'The quantity is required for each detail item.',
        'details.*.dzongkhag.required' => 'The dzongkhag is required for each detail item.',
        // 'details.*.office_name.required' => 'The office name is required for each detail item.',
        'details.*.site_name.required' => 'The site name is required for each detail item.',
    ];

     public function index(Request $request)
     {
         $privileges = $request->instance();
         $reqTypes = MasRequisitionType::get(['id', 'name']);
         $requisitions = RequisitionApplication::filter($request)->orderBy('created_at')->paginate(config('global.pagination'))->withQueryString();
         return view('asset.requisition-apply.index', compact('privileges', 'requisitions', 'reqTypes'));
     }

     /**
      * Show the form for creating a new resource.
      */
     public function create()
     {
        $reqTypes = MasRequisitionType::get();
        $grnNos = GrnItemMapping::with('store:id,name')->whereStatus(1)->get();
        $dzongkhags = MasDzongkhag::all();
        $sites = MasSite::with('dzongkhag')->get();
        return view('asset.requisition-apply.create', compact('reqTypes', 'grnNos', 'sites', 'dzongkhags'));
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
        $approverByHierarchy = $approvalService->getApproverByHierarchy($request->type_id, \App\Models\MasRequisitionType::class, $conditionFields ?? []);

        $reqType = MasRequisitionType::where('id', $request->type_id)->first();
        $lastTransaction = RequisitionApplication::latest('id')->first();
        $reqNumber = generateTransactionNumber1($reqType, $lastTransaction, 'requisition_no');


        try {
            DB::beginTransaction();
            $requisition->requisition_no = $reqNumber;
            $requisition->type_id = $request->type_id;
            $requisition->requisition_date = $request->requisition_date;
            $requisition->need_by_date = $request->need_by_date;
            $requisition->total_quantity_required = $request->total_quantity_required;
            //$requisition->item_category = $request->item_category;
            $requisition->status = $approverByHierarchy['application_status'];
            $requisition->save();

            if($request->details){
                $this->saveDetails($request->details, $requisition->id);
            }

            // Create a corresponding history record for advance
            // Create a history record
            $historyService = new ApplicationHistoriesService();
            $historyService->saveHistory($requisition->histories(), $approverByHierarchy, $request->remarks);


            DB::commit();

            if (isset($approverByHierarchy['approver_details'])) {
                $emailContent = 'has submitted a requisition request and is awaiting your approval for requisition no ' . $request->requisition_no;
                $emailSubject = 'Requisition Application';
                Mail::to([$approverByHierarchy['approver_details']['user_with_approving_role']->email])->send(new ApplicationForwardedMail(auth()->user()->id, $approverByHierarchy['approver_details']['user_with_approving_role']->email, $emailContent, $emailSubject));
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('msg_error', $e->getMessage());
            // return back()->withInput()->with('msg_error', GENERAL_ERR_MSG);
        }
        return redirect('asset/requisition')->with('msg_success', 'Requisition has been applied successfully!');
     }

     /**
      * Display the specified resource.
      */
     public function show(string $id)
     {
        $requisition = RequisitionApplication::with('histories')->find($id);
        return view('asset.requisition-apply.show', compact('requisition'));
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


     private function saveDetails ($details, $requisitionId) {
        // Track existing IDs to avoid deleting records that are updated
        $existingIds = [];
        $grn_item_mapping = GrnItemMapping::where('status', 1)->get();
        foreach ($details as $detail) {
            
            $grnData = json_decode($detail['grn_no'], true);
            $grn_item_mapping = GrnItemMapping::where('id', $grnData['id'])->first();
            if ($grn_item_mapping) {
                // Ensure stock doesn't go negative
                $newStock = max(0, $grn_item_mapping->current_stock - $detail['quantity_required']);
                $change = $grn_item_mapping->changed_quantity + $detail['quantity_required'];
                // Update stock in database
                $grn_item_mapping->update(['current_stock' => $newStock, 'changed_quantity' => $change]);
            }

            // Check if the detail has an 'id' (indicating an existing record)
            if (isset($detail['id']) && !empty($detail['id'])) {
                // Update the existing record
                $existingDetail = RequisitionDetail::find($detail['id']);
                if ($existingDetail) {
                    $existingDetail->update([
                        'grn_item_mapping_id' => $grnData['id'],
                        'grn_no' => $grnData['grn_no'],
                        'item_description' => $detail['item_description'],
                        'uom' => $detail['uom'],
                        'store_id' => $detail['store'],
                        'quantity_required' => $detail['quantity_required'],
                        'dzongkhag_id' => $detail['dzongkhag'],
                        'site_id' => $detail['site_name'],
                        'remark' => $detail['remark'],
                    ]);

                    $existingIds[] = $existingDetail->id; // Track updated record IDs
                }
            } else {
                // Insert new record
                $newDetail = RequisitionDetail::create([
                    'grn_item_mapping_id' => $grnData['id'],
                    'requisition_id' => $requisitionId,
                    'grn_no' => $grnData['grn_no'],
                    'item_description' => $detail['item_description'],
                    'uom' => $detail['uom'],
                    'store_id' => $detail['store'],
                    'stock_status' => $detail['stock_status'],
                    'quantity_required' => $detail['quantity_required'],
                    'dzongkhag_id' => $detail['dzongkhag'],
                    'site_id' => $detail['site_name'],
                    'remark' => $detail['remark'],
                ]);

                if ($newDetail) {
                    $existingIds[] = $newDetail->id; // Track newly inserted record IDs
                }
            }
        }

        // Optionally delete records not in the current request
        RequisitionDetail::where('requisition_id', $requisitionId)
            ->whereNotIn('id', $existingIds)
            ->delete();
     }
}
