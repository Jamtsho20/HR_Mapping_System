<?php

namespace App\Services;

use App\Models\AssetTransferApplication;
use App\Models\AssetReturnApplication;
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
            $assetTransfer = AssetTransferApplication::find($id);
        $assetTransfer->received_acknowledged = 1;
        $assetTransfer->save();


        $masAssetIds = $assetTransfer->details->pluck('mas_asset_id');



        $items = [];

        foreach ($assetTransfer->details as $detail) {
            $lineNum = AssetTransferApplication::whereHas('details', function ($query) use ($detail) {
                $query->where('mas_asset_id', $detail->mas_asset_id);
            })->count();
            $itemCode = $detail->asset->receivedSerial?->requisitionDetail->grnItemDetail->item->item_no ?? $detail->asset->item_code ?? null;
            $serialNumber = $detail->receivedSerial?->asset_serial_no ?? $detail->asset->serial_number ?? null;
            $formattedItemCode = $itemCode . '-' . $serialNumber;
            if ($formattedItemCode) {
                 $items[$lineNum] = $formattedItemCode;
            };

        }
$postData = [];

foreach ($items as $lineNum => $formattedItemCode) {
    $postData[] = [
        'ItemDistributionRules' => [
            [
                'LineNumber' => $lineNum,
                'ValidFrom' => date('Y-m-d'), // or any logic
                'ValidTo' => date('Y-m-d'),   // or any logic
                'DistributionRule4' => $assetTransfer->fromEmployee?->username,
            ],
            [
                'LineNumber' => $lineNum + 1,
                'ValidFrom' => $assetTransfer->transaction_date ?? date('Y-m-d'),
                'ValidTo' => '2099-12-31', // default end date
                'DistributionRule4' => $assetTransfer->toEmployee?->username,
            ]
        ]
    ];
}

        $postData = [
            'asset_post_type' => 'transfer',
            'items' => '',
            'transfer_id' => $assetTransfer->id,
            'project_code' => $assetTransfer->toSite?->code,
            'status' => 'acknowledged'
        ];

        $postJournalEntriesResponse = $this->sap->postAssetTransferReturn(json_encode($postData));

        DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception("Acknowledge asset transfer failed: " . $e->getMessage());
        }
    }

    protected function acknowledgeAssetReturn($id)
    {
        try{
            DB::beginTransaction();
        $assetReturn = AssetReturnApplication::findOrFail($id);
        $assetReturn->received_acknowledged = 1;
        $assetReturn->save();

        $items = [];

        foreach ($assetReturn->details as $detail) {
            $itemCode = $detail->receivedSerial->requisitionDetail->grnItemDetail->item->item_no ?? null;
            $serialNumber = $detail->receivedSerial->asset_serial_no ?? null;
            $formattedItemCode = $itemCode . '-' . $serialNumber;
            if ($formattedItemCode) {
                $items[] = $formattedItemCode;
            };

        }
        $joinedItems = implode(',', $items); // Join with comma


        $postData = [
            'asset_post_type' => 'return',
            'items' => $joinedItems,
        ];

        $postJournalEntriesResponse = $this->sap->postAssetTransferReturn(json_encode($postData));

        DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception("Acknowledge asset transfer failed: " . $e->getMessage(), 500);
        }
    }
}
