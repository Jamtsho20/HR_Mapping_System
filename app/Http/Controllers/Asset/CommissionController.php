<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GoodCommissionApplication;
use App\Models\GoodReceiptApplication;
use App\Models\User;
use App\Models\MasDepartment;
use App\Models\MasCommissionTypes;
use App\Services\ApprovalService;
use App\Services\ApplicationHistoriesService;
use App\Mail\ApplicationForwardedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class CommissionController extends Controller
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

    private $attachmentPath = 'images/commissions/';

    public function index(Request $request)
    {
        $privileges = $request->instance();
        $goods_commissions = GoodCommissionApplication::where('created_by', auth()->user()->id)->get();

        return view('asset.commission.index',compact('privileges', 'goods_commissions'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $receipts = GoodReceiptApplication::where('status',0)->get();
        $user = User::where('id', auth()->user()->id)->with('empJob')->first();
        $department = MasDepartment::where('id', $user->empJob->mas_department_id)->first('name');
        $types = MasCommissionTypes::all();
        return view('asset.commission.create',compact('receipts', 'user', 'department', 'types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // $attachment = "";

        // if ($request->hasFile('file')) {
        //     $file = $request->file('file');

        //     $attachment = uploadImageToDirectory($file, $this->attachmentPath);
        // }
        // dd($attachment);
        $conditionFields = approvalHeadConditionFields(COMMISSION_APPVL_HEAD, $request); // fetching condition field for particular aprroval head
        $approvalService = new ApprovalService();
        $approverByHierarchy = $approvalService->getApproverByHierarchy($request->type_id, \App\Models\MasCommissionTypes::class, $conditionFields ?? []);
        $receipt_no = GoodReceiptApplication::where('id', $request->grn)->first();

        try {
            DB::beginTransaction();
            $commissionApplication = GoodCommissionApplication::create([
                'commission_no' => $request->commission_no,
                'receipt_no' => $request->grn,
                'commission_date' => $request->commission_date,
                'file' => $attachment ?? null,
                'status' => $approverByHierarchy['application_status'],
            ]);

            if ($request->has('details')) {
                foreach ($request->details as $detail) {
                    if (isset($detail['is_active'])){
                    $commissionApplication->details()->create([
                        'purchase_order_no' => $detail['purchase_order_no'],
                        'asset_no' => $detail['asset_no'] ?? null,
                        'item_description' => $detail['item_description'],
                        'uom' => $detail['uom'],
                        'dzongkhag' => $detail['dzongkhag'],
                        'site_name' => $detail['site_name'],
                        'quantity' => $detail['quantity'],
                        'remark' => $detail['remark'],
                        'status' => 0

                    ]);
                }
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
