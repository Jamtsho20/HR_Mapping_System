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
        try {
            if ($type === 'assettransfer') {
                return $this->acknowledgeAssetTransfer($id);
            } elseif ($type === 'return') {
                return $this->acknowledgeAssetReturn($id);
            } else {
                throw new \Exception("Unknown acknowledgement type: $type");
            }
        } catch (\Exception $e) {
            throw new \Exception("Acknowledgement failed: " . $e->getMessage(), 500);
        }
    }


    protected function acknowledgeAssetTransfer($id)
    {
        try {
            DB::beginTransaction();

            $assetTransfer = AssetTransferApplication::find($id);
            $assetTransfer->received_acknowledged = 1;
            $assetTransfer->save();

            $masAssetIds = $assetTransfer->details->pluck('mas_asset_id');
            $assets = MasAssets::whereIn('id', $masAssetIds)->get();

            $toDepartmentCode = $assetTransfer->toEmployee?->empJob->department->sap_asset_code ?? null;
            $toSiteCode = $assetTransfer->toSite?->code ?? null;

            $postData = [];

            foreach ($assetTransfer->details as $detail) {
                $empLine = $detail->asset->emp_line_num ?? null;
                $prjLine = $detail->asset->prj_line_num ?? null;

                $formattedItemCode = $detail->asset?->asset_no ?? null;
                if (!$formattedItemCode) {
                    DB::rollBack();
                    return [
                        'success' => false,
                        'message' => "Asset serial number is required for transfer detail {$detail->id}"
                    ];
                }

                $postDataItem = [
                    "AssetNo" => $formattedItemCode,
                    "ItemProjects" => [
                        [
                            "LineNumber" => $prjLine,
                            "ValidTo" => date('Y-m-d', strtotime('-1 day')),
                        ],
                        [
                            "LineNumber" => $prjLine + 1,
                            "ValidFrom" => date('Y-m-d'),
                            "Project" => $toSiteCode ?? $toDepartmentCode ?? null,
                        ]
                    ]
                ];

                if ($assetTransfer->type->id == 1) {
                    $postDataItem['ItemDistributionRules'] = [
                        [
                            'LineNumber' => $empLine,
                            'ValidTo' => date('Y-m-d', strtotime('-1 day')),
                        ],
                        [
                            'LineNumber' => $empLine + 1,
                            'ValidFrom' => $assetTransfer->transaction_date ?? date('Y-m-d'),
                            'DistributionRule4' => $assetTransfer->toEmployee?->username,
                        ]
                    ];
                }

                $postData[] = $postDataItem;
            }

            // Call SAP
           $sapResponse = $this->sap->postAssetTransferReturn(json_encode($postData), $assetTransfer, 1);

            if (!empty($sapResponse['msg_error'])) {
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => $sapResponse['msg_error'],
                    'payload' => $sapResponse['payload'] ?? null
                ];
            }

            // Update assets after SAP success
            foreach ($assets as $asset) {
                $asset->is_transfered = 0;
                $asset->status = 2;

                if ($toSiteCode) {
                    $asset->current_site_id = $assetTransfer->to_site_id;
                } else {
                    $asset->current_employee_id = $assetTransfer->to_employee_id;
                }

                $asset->save();
            }

            DB::commit();

            return [
                'success' => true,
                'message' => 'Receipt acknowledged successfully.'
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Acknowledge asset transfer failed: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to acknowledge receipt.',
                'error' => $e->getMessage()
            ];
        }
    }


    protected function acknowledgeAssetReturn($id)
    {
        try {
            DB::beginTransaction();

            $assetReturn = AssetReturnApplication::find($id);
            $assetReturn->received_acknowledged = 1;
            $assetReturn->save();

            $masAssetIds = $assetReturn->details->pluck('mas_asset_id');
            $assets = MasAssets::whereIn('id', $masAssetIds)->get();

            $postData = [];

            foreach ($assetReturn->details as $detail) {
                $empLine = $detail->asset->emp_line_num ?? null;
                $prjLine = $detail->asset->prj_line_num ?? null;

                $formattedItemCode = $detail->asset?->asset_no ?? null;
                $storeCode = $detail->store->code ?? null;
                if (!$formattedItemCode) {
                    DB::rollBack();
                    return [
                        'success' => false,
                        'message' => "Asset serial number is required for return detail {$detail->id}"
                    ];
                }

                $postDataItem = [
                    "AssetNo" => $formattedItemCode,
                    "U_Status" => "Return",
                    "ItemProjects" => [
                        [
                            "LineNumber" => $prjLine,
                            "ValidTo" => date('Y-m-d', strtotime('-1 day')),
                        ],
                        [
                            "LineNumber" => $prjLine + 1,
                            "ValidFrom" => date('Y-m-d'),
                            "Project" => $storeCode ?? null,
                        ]
                    ]
                ];

                // if ($assetReturn->type->id == 1) {
                //     $postDataItem['ItemDistributionRules'] = [
                //         [
                //             'LineNumber' => $empLine,
                //             'ValidTo' => date('Y-m-d', strtotime('-1 day')),
                //         ],
                //         [
                //             'LineNumber' => $empLine + 1,
                //             'ValidFrom' => $assetReturn->transaction_date ?? date('Y-m-d'),
                //             'DistributionRule4' => $assetReturn->toEmployee?->username,
                //         ]
                //     ];
                // }

                $postData[] = $postDataItem;
            }

            dd($postData);
            // Call SAP
           $sapResponse = $this->sap->postAssetTransferReturn(json_encode($postData), $assetReturn, 2);

            if (!empty($sapResponse['msg_error'])) {
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => $sapResponse['msg_error'],
                    'payload' => $sapResponse['payload'] ?? null
                ];
            }


            DB::commit();

            return [
                'success' => true,
                'message' => 'Receipt acknowledged successfully.'
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Acknowledge asset return failed: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to acknowledge receipt.',
                'error' => $e->getMessage()
            ];
        }
    }
}
