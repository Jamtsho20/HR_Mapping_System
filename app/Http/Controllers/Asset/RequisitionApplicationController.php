<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use App\Mail\ApplicationForwardedMail;
use App\Models\MasGrnItem;
use App\Models\MasGrnItemDetail;
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
use App\Models\MasItem;
use App\Models\MasStore;
use App\Models\AssetUnitOfMeasurement;

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
         $requisitions = RequisitionApplication::with('details.grnItem')->filter($request)->orderBy('created_at')->paginate(config('global.pagination'))->withQueryString();
         return view('asset.requisition-apply.index', compact('privileges', 'requisitions', 'reqTypes'));
     }

     public function receive(string $id)
     {
        $requisition = RequisitionApplication::with('histories', 'details.serials')->find($id);
        if($requisition->type_id == 1){
            return view('asset.requisition-apply.receive', compact('requisition'));
        }else{
            return view('asset.requisition-apply.receive-consumable', compact('requisition'));
        }
     }
     /**
      * Show the form for creating a new resource.
      */
     public function create()
     {
        $reqTypes = MasRequisitionType::where('status', 1)->orderBy('id', 'desc')->get();
        $grnNos = MasGrnItem::with(['detail.store:id,name', 'detail.item:id,item_description,uom,is_fixed_asset', 'detail'])->whereStatus(1)->get();
        $items = MasItem::where('is_fixed_asset', 0)->get();
        $stores = MasStore::where('status', 1)->get();
        $dzongkhags = MasDzongkhag::all();
        $sites = MasSite::with('dzongkhag')->get();

        return view('asset.requisition-apply.create', compact('reqTypes', 'grnNos', 'sites', 'dzongkhags', 'items', 'stores'));
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

         $transactionNo = generateTransactionNumber1($reqType, $lastTransaction, 'transaction_no');


         try {
             DB::beginTransaction();
             $requisition->transaction_no = $transactionNo;
             $requisition->type_id = $request->type_id;
             $requisition->transaction_date = $request->requisition_date;
            $requisition->need_by_date = $request->need_by_date;

            $requisition->status = $approverByHierarchy['application_status'];
            $requisition->save();

            if($request->details){
                $this->saveDetails($request->details, $requisition->id, $request->type_id);
            }

            $historyService = new ApplicationHistoriesService();
            $historyService->saveHistory($requisition->histories(), $approverByHierarchy, $request->remarks);


            DB::commit();

            if (isset($approverByHierarchy['approver_details'])) {
                $emailContent = 'has submitted a requisition request and is awaiting your approval for requisition no ' . $request->requisition_no;
                $emailSubject = 'Requisition Application';

                try{
                    Mail::to([$approverByHierarchy['approver_details']['user_with_approving_role']->email])->send(new ApplicationForwardedMail(auth()->user()->id, $approverByHierarchy['approver_details']['user_with_approving_role']->id, $emailContent, $emailSubject));
                }catch(\Exception $e){
                    \Log::error('Error sending mail for requisition application: ' . $e->getMessage());
                }
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
        $requisition = RequisitionApplication::with([
                'histories',
                'details.serials' => function ($query) {
                    $query->where('is_received', 1);
                }
            ])->find($id);
        $approvalDetail = getApplicationLogs(\App\Models\RequisitionApplication::class, $requisition->id);
        return view('asset.requisition-apply.show', compact('requisition', 'approvalDetail'));
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


     public function inventory(Request $request)
     {
         $grns = MasGrnItem::with('detail')->filter($request)->orderBy('created_at')->paginate(config('global.pagination'))->withQueryString();
         return view('asset.requisition-apply.inventory', compact('grns'));
     }


     private function saveDetails($details, $requisitionId, $typeId)
     {
        
         $existingIds = [];
         foreach ($details as $detail) {

             $grn_item = null;
             $grn_item_id = null;
             $grn_item_detail_id = null;
             $newStock = null;
             $uomId = AssetUnitofMeasurement::where('name', $detail['uom'])->firstOrFail()->id;
             if($uomId == null){
                 return back()->withInput()->with('msg_error', 'Selected uom not found, contact admin.');
                }

                // Handle GRN-based requisition (type_id == 1)
                if (!empty($detail['grn_no'])) {
                    $grnData = $detail['grn_no'];

                    if ($grnData) {
                        $grn = MasGrnItem::where('grn_no', $grnData)->first();

                        if (!$grn) {
                            return back()->withInput()->with('msg_error', 'GRN Number not found in grnData');
                        }
                        $grn_item = MasGrnItemDetail::where('grn_id', $grn->id)
                        ->where('store_id', $detail['store'])
                        ->where('item_id', $detail['item_description'])
                        ->first();
                      
                        $conversionFlag = isset($detail['conversion']) && strtolower($detail['conversion']) !== 'false';


                        if ($grn_item) {
                            $newStock=0;
                            if($conversionFlag == false){
                                $newStock = max(0, $grn_item->quantity - $detail['quantity_required']);
                                $grn_item->update(['quantity' => $newStock]);
                            }
                            $grn_item_id = $grn_item->grn_id;
                         $grn_item_detail_id = $grn_item->id;
                     }
                 }
             }

             $data = [
                 'requisition_id' => $requisitionId,
                 'status' => 1,
                 'requested_quantity' => $detail['quantity_required'],
                 'dzongkhag_id' => $detail['dzongkhag'],
                 'site_id' => $detail['site_name'],
                 'uom' => $uomId,
                 'remark' => $detail['remark'],
             ];

             if ($typeId == 1) {
                 $data['grn_item_id'] = $grn_item_id;
                 $data['grn_item_detail_id'] = $grn_item_detail_id;
                $data['current_stock'] = $newStock;

             } else {
                 $data['item_id'] = $detail['item_description'];
                 $data['store_id'] = $detail['store'];
                 $data['current_stock'] = $detail['stock_status'] - $detail['quantity_required'];
             }


             if (!empty($detail['id'])) {
                 $existingDetail = RequisitionDetail::find($detail['id']);
                 if ($existingDetail) {
                     $existingDetail->update($data);
                     $existingIds[] = $existingDetail->id;
                    }
                } else {
                    $newDetail = RequisitionDetail::create($data);
                    if ($newDetail) {
                        $existingIds[] = $newDetail->id;
                    }
                }
            }

         // Cleanup: delete details that are no longer present
         RequisitionDetail::where('requisition_id', $requisitionId)
             ->whereNotIn('id', $existingIds)
             ->delete();
     }


}
