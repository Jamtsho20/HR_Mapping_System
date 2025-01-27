<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GoodReceiptApplication;
use App\Models\GoodIssueApplication;
use Illuminate\Support\Facades\DB;
use App\Models\GoodReceiptApplicationDetail;
use App\Models\User;
use App\Models\MasDepartment;

class GoodsReceiptController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:asset/goods-receipt,view')->only('index');
        $this->middleware('permission:asset/goods-receipt,create')->only('store');
        $this->middleware('permission:asset/goods-receipt,edit')->only('update');
        $this->middleware('permission:asset/goods-receipt,delete')->only('destroy');
    }

    protected $rules = [
        'receipt_no' => 'required',
        'receipt_date' => 'required',
        'issue_no' => 'required',


     ];

     protected $messages = [
        'receipt_no' => 'Receipt No is required',
        'receipt_date' => 'Receipt Date is required',
        'issue_no' => 'Issue No is required'
    ];
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $goods_receipts = GoodReceiptApplication::where('created_by', auth()->user()->id)->get();
        return view('asset.goods-receipt.index',compact('privileges', 'goods_receipts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       $goods_issued = GoodIssueApplication::where('status',0)->get();
       $user = User::where('id', auth()->user()->id)->with('empJob')->first();
       $department = MasDepartment::where('id', $user->empJob->mas_department_id)->first('name');
       return view('asset.goods-receipt.create',compact('goods_issued', 'department'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   $request->validate($this->rules, $this->messages);
        $goodReceipt = new GoodReceiptApplication();
        $goodIssue = GoodIssueApplication::where('issue_no', $request->issue_no)->first();

        if ($goodIssue) {
            $goodIssue->status = 1;
            $goodIssue->save();
        }

        try {
            DB::beginTransaction();
            $goodReceipt->issue_id = $request->issue_no;
            $goodReceipt->receipt_no = $request->receipt_no;
            $goodReceipt->receipt_date = $request->receipt_date;
            $goodReceipt->issue_id = $request->issue_no;
            $goodReceipt->status = 0;
            $goodReceipt->save();

            if($request->details){
                $this->saveDetails($request->details, $goodReceipt->id);
            }
            DB::commit();
            return redirect()->route('goods-receipt.index')->with('msg_success', 'Good Receipt created successfully');
        } catch (\Exception $e) {
            return back()->withInput()->with('msg_error', $e->getMessage());
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

    private function saveDetails ($details, $requisitionId) {
        // Track existing IDs to avoid deleting records that are updated

        $existingIds = [];

        foreach ($details as $detail) {
            // Check if the detail has an 'id' (indicating an existing record)
            if (isset($detail['id']) && !empty($detail['id'])) {
                // Update the existing record
                $existingDetail = GoodReceiptApplicationDetail::find($detail['id']);
                if ($existingDetail) {
                    $existingDetail->update([
                        'purchase_order_no' => $detail['purchase_order_no'],
                        'item_description' => $detail['item_description'],
                        'uom' => $detail['uom'],
                        'store' => $detail['store'],
                        'stock_status' => $detail['stock_status'],
                        'receipt_quantity' => $detail['receipt_quantity'],
                        'balance' => $detail['receipt_quantity'],
                        'dzongkhag' => $detail['dzongkhag'],
                        'site_name' => $detail['site_name'],
                        'remark' => $detail['remark'],
                    ]);

                    $existingIds[] = $existingDetail->id; // Track updated record IDs
                }
            } else {
                // Insert new record
                $newDetail = GoodReceiptApplicationDetail::create([
                    'good_receipt_id' => $requisitionId,
                    'purchase_order_no' => $detail['purchase_order_no'],
                    'item_description' => $detail['item_description'],
                    'uom' => $detail['uom'],
                    'store' => $detail['store'],
                    'stock_status' => $detail['stock_status'],
                    'receipt_quantity' => $detail['receipt_quantity'],
                    'dzongkhag' => $detail['dzongkhag'],
                    'site_name' => $detail['site_name'],
                    'remark' => $detail['remark'],
                    'balance' => $detail['receipt_quantity'],
                    'status' => 0
                ]);

                if ($newDetail) {
                    $existingIds[] = $newDetail->id; // Track newly inserted record IDs
                }
            }
        }

        // Optionally delete records not in the current request
        GoodReceiptApplicationDetail::where('good_receipt_id', $requisitionId)
            ->whereNotIn('id', $existingIds)
            ->delete();
     }
}
