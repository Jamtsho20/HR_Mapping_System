<?php

namespace App\Http\Controllers\Api\v1\Asset;

use Illuminate\Http\Request;
use App\Traits\JsonResponseTrait;
use App\Models\MasAssets;
use App\Http\Controllers\Controller;

class MyAssetApiController extends Controller
{
     use JsonResponseTrait;

      public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function getAsset()
{
    try {
        $assets = MasAssets::where('current_employee_id', auth()->user()->id)
            ->with('receivedSerial')
            ->get();

        $data = $assets->map(function ($item) {
            return [
                'serial_number' => $item->receivedSerial->requisitionDetail->grnItemDetail->item->item_no.'-'.$item->serial_number,
                'asset_description' => $item->receivedSerial->asset_description ?? null,
                'quantity' => $item->receivedSerial->quantity ?? null,
                'amount' => $item->receivedSerial->amount ?? null,
            ];
        });

        return $this->successResponse($data, 'Assets retrieved successfully');
    } catch (\Exception $e) {
        return $this->errorResponse($e->getMessage());
    }
}

}
