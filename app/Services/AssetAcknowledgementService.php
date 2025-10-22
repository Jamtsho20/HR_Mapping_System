<?php

namespace App\Services;

use App\Models\AssetTransferApplication;
use App\Models\AssetReturnApplication;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Api\SAP\ApiController;
use Illuminate\Support\Facades\DB;
use App\Traits\JsonResponseTrait;
use App\Models\MasAssets;

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
            $assets = MasAssets::whereIn('id', $masAssetIds)->get();


            $toDepartmentCode = $assetTransfer->toEmployee?->empJob->department->sap_asset_code ?? null;
            $toSiteCode = $assetTransfer->toSite?->code ?? null;
            foreach ($assets as $asset) {
                $asset->is_transfered = 0;
                $asset->save();
            }

            foreach ($assetTransfer->details as $detail) {
                $empLine = $detail->asset->emp_line_num ?? null;
                $prjLine = $detail->asset->prj_line_num ?? null;
                $itemCode = $detail->asset->receivedSerial?->requisitionDetail->grnItemDetail->item->item_no ?? $detail->asset->SapAssets->item_code ?? 'NA';
                $serialNumber = $detail->receivedSerial?->asset_serial_no ?? $detail->asset->serial_number ?? null;
                $formattedItemCode = $itemCode . '-' . $serialNumber;
                $items[] = [
                    'emp_line_num' => $empLine,
                    'prj_line_num' => $prjLine,
                    'item' => $formattedItemCode,
                ];

            }
            $postData = [];
            // Log::info($postData);

            foreach ($items as $item) {

                $postDataItem = [
                "ItemProjects" => [
                    [
                        "LineNumber" => $item['prj_line_num'],
                        "ValidTo" => date('Y-m-d') ?? null,
                    ],
                    [
                        "LineNumber" => $item['prj_line_num']+1,
                        "ValidFrom" => date('Y-m-d') ?? null,
                        "ValidTo" =>  null,
                        "Project" => $toSiteCode ?? $toDepartmentCode ?? null,
                    ]
                ]
            ];

            // Conditionally add ItemDistributionRules
            if ($assetTransfer->type->id == 1) {
                $postDataItem['ItemDistributionRules'] = [
                    [
                        'LineNumber' => $item['emp_line_num'],
                        'ValidFrom' => date('Y-m-d'),
                        'ValidTo' => date('Y-m-d'),
                        'DistributionRule4' => $assetTransfer->fromEmployee?->username,
                    ],
                    [
                        'LineNumber' => $item['emp_line_num'] + 1,
                        'ValidFrom' => $assetTransfer->transaction_date ?? date('Y-m-d'),
                        'ValidTo' => '2099-12-31', // default end date
                        'DistributionRule4' => $assetTransfer->toEmployee?->username,
                    ]
                ];
            }

            // Append to $postData
            $postData[] = $postDataItem;
            }

            $postJournalEntriesResponse = $this->sap->postAssetTransferReturn(json_encode($postData), $assetTransfer, 1);
            dd($postData);

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
