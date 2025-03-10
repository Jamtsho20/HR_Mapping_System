<?php

namespace App\Http\Controllers\Api\SAP;

use App\Http\Controllers\Controller as BaseController;
use App\Models\GoodsReceivedDetail;
use App\Models\GoodsReceivedDetailSerial;
use App\Models\GrnItemMapping;
use App\Models\ItemMappingDetail;
use App\Models\MasGoodsReceivedByUser;
use App\Models\MasItem;
use App\Models\MasStore;
use App\Models\RequisitionApplication;
use App\Models\RequisitionDetail;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class ApiController extends BaseController
{

    use JsonResponseTrait;
    protected $country = "Bhutan";
    protected $sapUser = 2;

    public function saveStore(Request $request) {
        $rules = [
            'name' => 'required',
            'code' => 'required',
            'dzongkhag' => 'required',
        ];

        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $parentStoreId = null;
        if($request->has('parent_store_code') && $request->parent_store_code){
            $parentStoreId = MasStore::where('code', $request->parent_store_code)->value('id');
            if (!$parentStoreId) {
                return $this->errorResponse('Parent store code associated for store code not found in HRMS system.');
            }
        }

        try {
            // Check if store exists based on store code
            $store = MasStore::where('code', $request->code)->first();

            if ($store) {
                // If store exists, update it
                $store->parent_store_id = $parentStoreId;
                $store->name = $request->name;
                // $store->code = $request->code;
                $store->country = isset($request->country) ? $request->country : $this->country;
                $store->dzongkhag = $request->dzongkhag ?? null;
                $store->region = isset($request->region) ? $request->region : null;
                $store->store_email = isset($request->store_email) ? $request->store_email : null;
                $store->phone_number = isset($request->phone_number) ? $request->phone_number : null;
                $store->contact_person = isset($request->contact_person) ? $request->contact_person : null;
                $store->contact_email = isset($request->contact_email) ? $request->contact_email : null;
                $store->contact_number = isset($request->contact_number) ? $request->contact_number : null;
                $store->status = $request->status ?? 1;
                $store->updated_by = $this->sapUser; // Track who updated it
                $store->save();
                $message = 'Warehouse updated successfully.';
            } else {
                // If store does not exist, create a new one
                $store = new MasStore();
                $store->parent_store_id = $parentStoreId;
                $store->name = $request->name;
                $store->code = $request->code;
                $store->country = isset($request->country) ? $request->country : $this->country;
                $store->dzongkhag = $request->dzongkhag ?? null;
                $store->region = isset($request->region) ? $request->region : null;
                $store->store_email = isset($request->store_email) ? $request->store_email : null;
                $store->phone_number = isset($request->phone_number) ? $request->phone_number : null;
                $store->contact_person = isset($request->contact_person) ? $request->contact_person : null;
                $store->contact_email = isset($request->contact_email) ? $request->contact_email : null;
                $store->contact_number = isset($request->contact_number) ? $request->contact_number : null;
                $store->status = $request->status ?? 1;
                $store->created_by = $this->sapUser;
                $store->save();
                $message = 'Warehouse created successfully.';
            }
        } catch(\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }

        return $this->successResponse($store, $message);
    }

    public function saveItem(Request $request) {
        $rules = [
            'item_category' => 'required',
            'item_no' => 'required',
            'item_description' => 'required',
            'uom' => 'required',
            'status' => 'required',
        ];

        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        try {
            // Check if item exists based on item_number
            $item = MasItem::where('item_no', $request->item_no)->first();

            if ($item) {
                // If item exists, update it
                $item->item_group = $request->item_category;
                $item->item_description = $request->item_description;
                $item->item_no = $request->item_no;
                $item->item_no_old = $request->item_no_old;
                $item->uom = $request->uom;
                $item->is_fixed_asset = $request->fa_enabled ?? 1;
                $item->status = $request->status ?? 1;
                $item->updated_by = $this->sapUser; // Track who updated it
                $item->save();
                $message = 'Item master updated successfully.';
            } else {
                // If item does not exist, create a new one
                $item = new MasItem();
                $item->item_group = $request->item_category;
                $item->item_description = $request->item_description;
                $item->item_no = $request->item_no;
                $item->item_no_old = $request->item_no_old;
                $item->uom = $request->uom;
                $item->is_fixed_asset = $request->fa_enabled ?? 1;
                $item->status = $request->status ?? 1;
                $item->created_by = $this->sapUser;
                $item->save();
                $message = 'Item master created successfully.';
            }
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }

        return $this->successResponse($item, $message);
    }

    public function saveGrnItemMapping(Request $request)
    {
        $rules = [
            'items.*.details.*.item_no' => 'required',
            'items.*.grn_no' => 'required',
            'items.*.details' => 'required|array',
            'items.*.details.*.store' => 'required',
            'items.*.details.*.quantity' => 'required|numeric',
        ];

        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        \DB::beginTransaction();
        try {
            $createdMappings = [];

            foreach ($request->items as $itemData) {
                // Check if GRN already exists in grn_item_mappings
                $itemMapping = GrnItemMapping::where('grn_no', $itemData['grn_no'])->first();

                if (!$itemMapping) {
                    // Create new GRN entry if it does not exist
                    $itemMapping = new GrnItemMapping();
                    $itemMapping->grn_no = $itemData['grn_no'];
                    $itemMapping->last_synced_at = now();
                    $itemMapping->status = $itemData['status'] ?? 1;
                    $itemMapping->save();
                }

                foreach ($itemData['details'] as $detail) {
                    $storeId = MasStore::where('code', $detail['store'])->value('id');
                    if (!$storeId) {
                        \DB::rollBack();
                        return $this->errorResponse("Store code {$detail['store']} not found in HRMS system.");
                    }

                    $item = MasItem::where('item_no', $detail['item_no'])->first();
                    if (!$item) {
                        \DB::rollBack();
                        return $this->errorResponse("Item no. {$detail['item_no']} not available for the given store.");
                    }
                    // Check if item already exists in item_mapping_details for the same GRN and store
                    $existingItemDetail = ItemMappingDetail::where([
                        'store_id' => $storeId,
                        'item_id' => $item->id,
                        'mapping_id' => $itemMapping->id,
                        'code' => $detail['code']
                    ])->first();

                    if ($existingItemDetail) {
                        // Update stock if item already exists
                        $existingItemDetail->quantity += $detail['quantity'];
                        $existingItemDetail->save();
                    } else {
                        // Create a new record if item does not exist
                        $itemDetails = new ItemMappingDetail();
                        $itemDetails->store_id = $storeId;
                        $itemDetails->item_id = $item->id;
                        $itemDetails->mapping_id = $itemMapping->id; // Foreign key to grn_item_mappings
                        $itemDetails->code = $detail['code'];
                        $itemDetails->quantity = $detail['quantity'];
                        $itemDetails->save();
                    }

                    $createdMappings[] = [
                        'grn_mapping' => $itemMapping,
                        'item_detail' => $existingItemDetail ?? $itemDetails
                    ];
                }
            }

            \DB::commit();
            return $this->successResponse($createdMappings, 'GRN item mappings created/updated successfully.');

        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->errorResponse($e->getMessage());
        }
    }

    public function saveGoodsIssued(Request $request)
    {
        $reqApplication = RequisitionApplication::with('details')->where('doc_no', $request->purchase_req_doc_no)->first();
        if(!$reqApplication){
            \Log::info("Purchase requisition doc no. {$request->purchase_req_doc_no} not found in HRMS system.");
            return $this->errorResponse("Purchase requisition doc no. {$request->purchase_req_doc_no} not found in HRMS system.");
        }
        // $reqDetails = RequisitionDetail::where('requisition_id', $reqApplication['id'])->collect();
        DB::beginTransaction();
        try {
            // Save the Goods Received by User
            $goodsReceived = MasGoodsReceivedByUser::create([
                'requisition_application_id' => $reqApplication['id'],
                'total_requested_quantity' => $reqApplication['total_quantity_required'],
                'total_received_quantity' => $request->received_quantity,
                'received_from' => $this->sapUser,
                'received_by' => $reqApplication['created_by'],
                'doc_no' => $request->doc_no, //issued sap doc no
                // 'received_at' => now(),
                // 'is_confirmed' => $request->is_confirmed ?? 0,
            ]);

            // Validate Details
            if (!isset($request->details) || !is_array($request->details)) {
                \Log::info("Invalid or missing goods received details for {$request->doc_no}.");
                return $this->errorResponse("Invalid or missing goods received details for {$request->doc_no}.");
            }

            $goodsReceivedDetails = [];
            $serialNumbers = [];

            foreach ($request->details as $detail) {

                $reqDetail = $reqApplication->details->where('grn_no', $detail['grn_no'])->first();
                $item_id = MasItem::where('item_no', $detail['item_code'])->value('id');
                if(!$item_id){
                    return $this->errorResponse("Item code {$detail['item_code']} not found in HRMS system.");
                }
                $goodsReceivedDetails[] = [
                    'item_id' => $item_id,
                    'goods_received_by_user_id' => $goodsReceived->id,
                    'req_detail_id' => $reqDetail->id,
                    'grn_no' => $detail['grn_no'],
                    'uom' => $detail['uom'] ?? $reqDetail->uom,
                    'item_description' => $detail['item_description'] ?? $reqDetail->item_description,
                    'asset_type' => $detail['asset_type'],
                    'asset_class' => $detail['asset_class'],
                    'requested_quantity' => $detail['requested_quantity'] ?? $reqDetail->requested_quantity,
                    'received_quantity' => $detail['received_quantity'],
                    // 'comissioned_quantity' => $detail['comissioned_quantity'],
                    // 'commissioned_status' => $detail['commissioned_status'],
                    // 'created_at' => now(),
                    // 'updated_at' => now(),
                ];
                // Bulk insert
                $goodsReceivedDetails = GoodsReceivedDetail::insert($goodsReceivedDetails);

                if (!empty($serialNumbers)) {


                if (!empty($detail['serials'])) {
                    foreach ($detail['serials'] as $serial) {
                        $serialNumbers[] = [
                            'goods_received_detail_id' => $goodsReceivedDetails->id,
                            'asset_serial_no' => $serial['asset_serial_no'],
                            'asset_description' => $serial['asset_description'] ?? $detail['item_description'],
                            // 'is_commissioned' => $serial['is_commissioned'] ?? 0,
                            // 'created_at' => now(),
                            // 'updated_at' => now(),
                        ];
                    }
                }
            }

                GoodsReceivedDetailSerial::insert($serialNumbers);
            }

            DB::commit();
            // return response()->json(['message' => 'Goods issued and received successfully!', 'data' => $goodsReceived], 201);
            return $this->successResponse($goodsReceived, 'Goods issued and received successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::info("Failed to save data for {$request->doc_no} " . $e->getMessage());
            return $this->errorResponse("Failed to save data for {$request->doc_no} " . $e->getMessage());
            // return response()->json(['error' => "Failed to save data for {$request->doc_no}", 'message' => $e->getMessage()], 500);
        }

    }

    public function startSession()
    {
        try {
            $curl = curl_init();

            $postFields = json_encode([
                "CompanyDB" => SAP_CONPANY_DB,
                "Password" => SAP_PASSWORD,
                "UserName" => SAP_USERNAME,
            ]);

            curl_setopt_array($curl, [
                CURLOPT_URL => SAP_BASE_URL . ':' . SAP_PORT . '/b1s/v1/Login',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $postFields,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                ],
                // Disable SSL verification. REMOVE IN PRODUCTION.
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
            ]);

            $response = curl_exec($curl);

            // Capture HTTP status code
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            if ($response === false) {
                // Handle cURL errors
                $curlError = curl_error($curl);
                curl_close($curl);

                return response()->json([
                    'success' => false,
                    'message' => 'CURL Error: ' . $curlError,
                    'http_code' => $httpCode,
                ]);
            }

            // Close the cURL session
            curl_close($curl);

            // Debug logs (only in non-production environments)
            if (env('APP_DEBUG')) {
                \Log::info('SAP API Response: ' . $response);
                \Log::info('HTTP Status Code: ' . $httpCode);
            }

            // Decode the JSON response
            $responseArray = json_decode($response, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                // Handle JSON decoding errors
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid JSON response from SAP API: ' . json_last_error_msg(),
                    'http_code' => $httpCode,
                ]);
            }

            // Validate required keys in the response
            if (isset($responseArray['SessionId'], $responseArray['odata.metadata'])) {
                $sessionId = $responseArray['SessionId'];
                $odataMetadata = $responseArray['odata.metadata'];
                $version = $responseArray['Version'] ?? null;
                $sessionTimeout = $responseArray['SessionTimeout'] ?? null;

                return response()->json([
                    'success' => true,
                    'odataMetadata' => $odataMetadata,
                    'sessionId' => $sessionId,
                    'version' => $version,
                    'sessionTimeout' => $sessionTimeout,
                    'http_code' => $httpCode,
                ]);
            } else {
                // Handle missing keys in the response
                return response()->json([
                    'success' => false,
                    'message' => 'Required keys are missing in the SAP API response.',
                    'response' => $responseArray,
                    'http_code' => $httpCode,
                ]);
            }
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
                'http_code' => 500,
            ]);
        }
    }

    // public function postJournalEntries($accountCode, $shortName, $memo, $amount, $costingCode = null, $costingCode2 = null)
    public function postJournalEntries($postFields, $assetFlag)
    {
        // Start SAP session and retrieve session ID
        $response = $this->startSession();

        if (json_last_error() === JSON_ERROR_NONE) {
            $session = json_decode($response->getContent(), true);

            $sessionId = $session['sessionId'] ?? null;
        } else {
            return response()->json(['msg_error' => 'Invalid JSON response: ' . json_last_error_msg()], 500);
        }

        if (empty($sessionId)) {
            return response()->json(['msg_error' => 'Failed to retrieve session ID'], 500);
        }

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => SAP_BASE_URL . ':' . SAP_PORT . ($assetFlag ? '/b1s/v1/PurchaseRequests' : '/b1s/v1/JournalEntries'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $postFields,
            CURLOPT_HTTPHEADER => [
                "Cookie: $sessionId; B1SESSION=$sessionId",
                'Content-Type: application/json',
            ],
            CURLOPT_SSL_VERIFYPEER => false, // REMOVE IN PRODUCTION
            CURLOPT_SSL_VERIFYHOST => false, // REMOVE IN PRODUCTION
        ]);

        $response = curl_exec($curl);
        $responseArray = json_decode($response, true);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            curl_close($curl);
            return response()->json(['msg_error' => 'Curl error: ' . $error_msg], 500);
        }

        curl_close($curl);

        if ($httpCode != 201) {
            $errorMessage = $responseArray['error']['message']['value'] ?? 'Something went wrong from SAP API';
            return response()->json(['msg_error' => $errorMessage], $httpCode);
        }

        return response()->json(['success' => true, 'data' => $responseArray], 201);
    }

    public function postEmployeeToSap($data)
    {
        // dd(json_encode($data));
        $response = $this->startSession();

        if (json_last_error() === JSON_ERROR_NONE) {
            $session = json_decode($response->getContent(), true);

            $sessionId = $session['sessionId'] ?? null;
        } else {
            return response()->json(['msg_error' => 'Invalid JSON response: ' . json_last_error_msg()], 500);
        }

        if (empty($sessionId)) {
            return response()->json(['msg_error' => 'Failed to retrieve session ID'], 500);
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => SAP_BASE_URL . ':' . SAP_PORT . '/b1s/v1/BusinessPartners',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                "Cookie: $sessionId; B1SESSION=$sessionId",
                'Content-Type: application/json',
            ],
            CURLOPT_SSL_VERIFYPEER => false, // REMOVE IN PRODUCTION
            CURLOPT_SSL_VERIFYHOST => false, // REMOVE IN PRODUCTION
        ));

        $response = curl_exec($curl);
        $responseArray = json_decode($response, true);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        // dd('http_code:' . $httpCode, $response);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            curl_close($curl);
            return response()->json(['msg_error' => 'Curl error: ' . $error_msg], 500);
        }

        curl_close($curl);

        if ($httpCode != 201) {
            $errorMessage = $responseArray['error']['message']['value'] ?? 'Something went wrong from SAP API';
            \Log::info($errorMessage);
            return response()->json(['msg_error' => $errorMessage], $httpCode);
        }
        // dd($responseArray);
        \Log::info('sap response: ' . json_encode($responseArray));
        return response()->json(['success' => true, 'data' => $responseArray], 201);
    }

}
