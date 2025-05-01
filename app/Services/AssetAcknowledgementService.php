<?php

namespace App\Services;

use App\Models\AssetTransferApplication;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Api\SAP\ApiController;
use Illuminate\Support\Facades\DB;
use App\Traits\JsonResponseTrait;

class AssetAcknowledgementService
{
    use JsonResponseTrait;
    protected $sap;

    public function __construct(ApiController $sap)
    {
        $this->sap = $sap;
    }

    public function acknowledge($id, $type)
    {
        try{
        if ($type === 'assettransfer') {
           $this->acknowledgeAssetTransfer($id);
        } elseif ($type === 'return') {
            $this->acknowledgeAssetReturn($id);
        } else {
            throw new \Exception("Unknown acknowledgement type: $type");
        }
        }catch(\Exception $e){
            throw new \Exception("Acknowledgement failed: " . $e->getMessage(), 500);
        }
    }

    protected function acknowledgeAssetTransfer($id)
    {
        try{
            DB::beginTransaction();
        $assetTransfer = AssetTransferApplication::with('details.receivedSerial.requisitionDetail.grnItemDetail.item')->findOrFail($id);
        $assetTransfer->received_acknowledged = 1;
        $assetTransfer->save();

        $items = [];

        foreach ($assetTransfer->details as $detail) {
            $itemCode = $detail->receivedSerial->requisitionDetail->grnItemDetail->item->item_no ?? null;
            $serialNumber = $detail->receivedSerial->asset_serial_no ?? null;
            $formattedItemCode = $itemCode . '-' . $serialNumber;
            if ($formattedItemCode) {
                $items[] = $formattedItemCode;
            };

        }
        $joinedItems = implode(',', $items); // Join with comma


        $postData = [
            'asset_post_type' => 'transfer',
            'items' => $joinedItems,
            'transfer_id' => $assetTransfer->id,
            'project_code' => $assetTransfer->toSite?->code,
            'status' => 'acknowledged'
        ];

        $postJournalEntriesResponse = $this->sap->postAssetTransferReturn(json_encode($postData));

        DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception("Acknowledge asset transfer failed: " . $e->getMessage(), 500);
        }
    }

    protected function acknowledgeAssetReturn($id)
    {
        $assetReturn = AssetReturnApplication::findOrFail($id);
        $assetReturn->received_acknowledged = 1;
        $assetReturn->save();
        $postData = [
            'return_id' => $id,
            'status' => 'acknowledged',
            'acknowledged_by' => auth()->user()->id,
            'timestamp' => now()->toDateTimeString()
        ];

        Http::post('https://example.com/api/acknowledge-return', $postData);
    }
}
