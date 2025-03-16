<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GoodCommissionApplication;
use App\Models\GoodReceiptApplication;
use App\Services\ApprovalService;
use App\Services\ApplicationHistoriesService;
use App\Mail\ApplicationForwardedMail;
use App\Models\AssetCommissionApplication;
use App\Models\AssetCommissionDetail;
use App\Models\RequisitionApplication;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class CommissionApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:asset/commission,view')->only('index');
        $this->middleware('permission:asset/commission,create')->only('store');
        $this->middleware('permission:asset/commission,edit')->only('update');
        $this->middleware('permission:asset/commission,delete')->only('destroy');
    }

    private $attachmentPath = 'images/asset-comm/';

    public function index(Request $request)
    {
        $privileges = $request->instance();
        $commissions = AssetCommissionApplication::filter($request)->orderByDesc('created_at')->paginate(config('global.pagination'))->withQueryString();
        return view('asset.commission.index',compact('privileges', 'commissions'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // only fixed asset can be commissioned
        $faItems = RequisitionApplication::with(['details.grnItem'])->where('type_id', FIXED_ASSET)
            ->where('is_received', 1)
            ->get();

        $empDetails = empDetails(auth()->user()->id);
        return view('asset.commission.create',compact('empDetails', 'faItems'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // $attachment = "";

        if ($request->hasFile('file')) {
            $file = $request->file('file');

            $attachment = uploadImageToDirectory($file, $this->attachmentPath);
        }

        // dd($attachment);
        $conditionFields = approvalHeadConditionFields(COMMISSION_APPVL_HEAD, $request); // fetching condition field for particular aprroval head
        $approvalService = new ApprovalService();
        $approverByHierarchy = $approvalService->getApproverByHierarchy(COMMISSION_TYPE, \App\Models\MasCommissionTypes::class, $conditionFields ?? []);
        // $reqType = MasRequisitionType::where('id', $request->type_id)->first();
        $lastTransaction = AssetCommissionApplication::latest('id')->first();

        $transactionNo = generateTransactionNumber1(COMMISSION_TYPE, $lastTransaction, 'transaction_no');

        try {
            DB::beginTransaction();
            $commissionApplication = AssetCommissionApplication::create([
                'transaction_no' => $transactionNo,
                'transaction_date' => $request->commission_date,
                'requisition_detail_id' => $request->grn,
                'transaction_date' => $request->commission_date,
                'file' => $attachment ?? null,
                'status' => $approverByHierarchy['application_status'],
            ]);

            if ($request->has('details')) {
                foreach ($request->details as $detail) {
                    $commissionApplication->details()->create([
                        'received_serial_id' => $detail['asset_no'],
                        'date_placed_in_service' => $detail['date_placed_in_service'],
                        'dzongkhag_id' => $detail['dzongkhag'],
                        'office_id' => $detail['office'],
                        'site_id' => $detail['site'],
                        'remark' => $detail['remark'],
                    ]);
                }
            }

            // Create a history record
            $historyService = new ApplicationHistoriesService();
            $historyService->saveHistory($commissionApplication->histories(), $approverByHierarchy, $request->remarks);

            // Fetch the approver dynamically using ApprovalService and sent email to notify approver accordingly
            DB::commit();
            if(isset($approverByHierarchy['approver_details'])){
                $emailContent = 'has submitted a commission request and is awaiting your approval.';
                $emailSubject = 'Commission Application';
                Mail::to([$approverByHierarchy['approver_details']['user_with_approving_role']->email])->send(new ApplicationForwardedMail(auth()->user()->id, $approverByHierarchy['approver_details']['user_with_approving_role']->email, $emailContent, $emailSubject));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('msg_error', $e->getMessage());
            // return back()->withInput()->with('msg_error', GENERAL_ERR_MSG);
        }

        return redirect('asset/commission')->with('msg_success', 'Asset commissioned successfully!');

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
}
