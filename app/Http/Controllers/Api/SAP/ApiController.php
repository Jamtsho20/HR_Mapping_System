<?php

namespace App\Http\Controllers\Api\SAP;

use App\Http\Controllers\Controller as BaseController;
use App\Models\ReceivedSerial;
use App\Models\MasGrnItem;
use App\Models\MasGrnItemDetail;
use App\Models\MasGoodsReceivedByUser;
use App\Models\MasItem;
use App\Models\MasStore;
use App\Models\User;
use App\Mail\GoodsIssuedMail;
use App\Models\MasDzongkhag;
use App\Models\MasSite;
use App\Models\MasAssets;
use App\Models\SapAsset;
use App\Models\RequisitionApplication;
use App\Models\RequisitionDetail;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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
            \Log::info("Error saving store: " . $e->getMessage());
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
            'asset_class_id' => 'required'
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
                $item->item_group_id = $request->asset_class_id;
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
                $item->item_group_id = $request->asset_class_id;
                $item->created_by = $this->sapUser;
                $item->save();
                $message = 'Item master created successfully.';
            }
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
            \Log::info("Error saving item: " . $e->getMessage());
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
            \Log::info("Validation error in saveGrnItemMapping: " . json_encode($validator->errors()));
        }

        \DB::beginTransaction();
        try {
            $createdMappings = [];

            foreach ($request->items as $itemData) {
                // Check if GRN already exists in grn_item_mappings
                $itemMapping = MasGrnItem::where('grn_no', $itemData['grn_no'])->first();

                if (!$itemMapping) {
                    // Create new GRN entry if it does not exist
                    $itemMapping = new MasGrnItem();
                    $itemMapping->grn_no = $itemData['grn_no'];
                    $itemMapping->last_synced_at = now();
                    $itemMapping->status = $itemData['status'] ?? 1;
                    $itemMapping->save();
                }

                foreach ($itemData['details'] as $detail) {
                    $storeId = MasStore::where('code', $detail['store'])->value('id');
                    if (!$storeId) {
                        \DB::rollBack();
                        return $this->errorResponse("Store code {$detail['store']} not found in HRMS.");
                    }

                    $item = MasItem::where('item_no', $detail['item_no'])->first();
                    if (!$item) {
                        \DB::rollBack();
                        return $this->errorResponse("Item no. {$detail['item_no']} not found in HRMS.");
                    }
                    // Check if item already exists in item_mapping_details for the same GRN and store
                    $existingItemDetail = MasGrnItemDetail::where([
                        'store_id' => $storeId,
                        'item_id' => $item->id,
                        'grn_id' => $itemMapping->id
                    ])->first();

                    if ($existingItemDetail) {
                        // Update stock if item already exists
                        $existingItemDetail->quantity = $detail['quantity'];
                        $existingItemDetail->save();
                    } else {
                        // Create a new record if item does not exist
                        $itemDetails = new MasGrnItemDetail();
                        $itemDetails->store_id = $storeId;
                        $itemDetails->item_id = $item->id;
                        $itemDetails->description = $detail['description'] ?? null;
                        $itemDetails->grn_id = $itemMapping->id; // Foreign key to grn_item_mappings
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
            \Log::info("Error saving GRN item mappings: " . $e->getMessage());
            return $this->errorResponse($e->getMessage());
        }
    }


    public function saveGoodIssueConsumable(Request $request){
        $rules = [
            'purchase_req_doc_no' => 'required|string|exists:requisition_applications,doc_no',
            'doc_no' => 'required|string',
            'details.*.asset_serial_no' => 'nullable|string|required_without:details.*.batch_no',
            'details.*.batch_no'        => 'nullable|string|required_without:details.*.asset_serial_no',
            'details.*.item_code' => 'required|string|exists:mas_items,item_no',
            'details.*.store_code' => 'required|string|exists:mas_stores,code',
        ];

        $messages=[
            'purchase_req_doc_no.required' => 'Purchase request document number is required',
            'doc_no.required' => 'Document number is required',
            'details.*.asset_serial_no.required_without' => 'Either Asset Serial No or Batch No must be provided',
            'details.*.batch_no.required_without'        => 'Either Batch No or Asset Serial No must be provided',
            'details.*.item_code.exists' => 'Item code :input not found in HRMS system.',
            'details.*.store_code.exists' => 'Store code :input not found in HRMS system.',

        ];

         $validator = \Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            \Log::error("Validation error in saveGrnItemMapping: " . json_encode($validator->errors()));
            return $this->validationErrorResponse($validator->errors());
        }

        DB::beginTransaction();
        try {

            $requisitionAppliciaiton = RequisitionApplication::where('doc_no', $request->purchase_req_doc_no)->first();
            if(!$requisitionAppliciaiton){
                return $this->errorResponse('Purchase request document number not found.');
            }
            $requisitionAppliciaiton->good_issue_doc_no = $request->doc_no;

            $requisitionAppliciaiton->save();

            foreach ($request->details as $detail) {

                $item_id = MasItem::where('item_no', $detail['item_code'])->value('id');
                $store_id = MasStore::where('code', $detail['store_code'])->value('id');
                $reqDetail = RequisitionDetail::where('requisition_id', $requisitionAppliciaiton->id)->where('item_id', $item_id)->where('store_id', $store_id)->first();
                if (!$reqDetail) {
                    return $this->errorResponse(
                        'Requisition detail not found for item ' . $detail['item_code'] .
                        ' and store ' . $detail['store_code']
                    );
                    }

                $reqDetail->received_quantity = (int)  $detail['quantity'];
                $reqDetail->save();



                $bulkSerials[] = [
                    'requisition_detail_id' => $reqDetail->id,
                    'asset_serial_no'       => $detail['asset_serial_no'] ?? null,
                    'batch_no'              => $detail['batch_no'] ?? null,
                    'asset_description'     => $detail['asset_description'] ?? null,
                    'amount'                => $detail['amount'] ?? null,
                    'quantity'              => $detail['quantity'] ?? null,
                    'created_at'            => now(),
                    'updated_at'            => now(),
                ];
            }

            if (!empty($bulkSerials)) {
                ReceivedSerial::insert($bulkSerials);
            }


            $employee = User::find($requisitionAppliciaiton->created_by); // Ensure employee_id exists in requisition
            DB::commit();
            if ($employee && $employee->email) {
                Mail::to($employee->email)->send(new GoodsIssuedMail($employee, $requisitionAppliciaiton));
            } else {
                \Log::warning("Email not sent: No email found for user ID {$requisitionAppliciaiton->created_by}");
            }


            return $this->successResponse($requisitionAppliciaiton, 'Good issue document number saved successfully.');
        }
        catch(\Exception $e){
            DB::rollBack();
            return $this->errorResponse($e->getMessage());
        }
    }

    public function saveGoodsIssued(Request $request)
    {

        $rules = [
            'purchase_req_doc_no' => 'required|string|exists:requisition_applications,doc_no',
            'doc_no' => 'required|string',
            'details' => 'required|array|min:1',
            'details.*.grn_no' => 'required|string|exists:mas_grn_items,grn_no',
            'details.*.line_item' => 'required|array|min:1',
            'details.*.line_item.*.item_code' => 'required|string|exists:mas_items,item_no',
            'details.*.line_item.*.store_code' => 'required|string|exists:mas_stores,code',
            'details.*.line_item.*.received_quantity' => 'required|integer|min:1',
            'details.*.line_item.*.serials' => 'sometimes|array',
            'details.*.line_item.*.serials.*.asset_serial_no' => 'required|string',
            'details.*.line_item.*.serials.*.asset_description' => 'required|string',
            'details.*.line_item.*.serials.*.amount' => 'required|string'
        ];
        $messages = [
            'purchase_req_doc_no.exists' => 'Purchase requisition doc no. :input not found in HRMS system.',
            'details.*.grn_no.exists' => 'GRN No. :input not found in HRMS system.',
            'details.*.line_item.*.item_code.exists' => 'Item code :input not found in HRMS system.',
            'details.*.line_item.*.store_code.exists' => 'Store code :input not found in HRMS system.',
        ];

        $validator = \Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            \Log::error("Validation error in saveGrnItemMapping: " . json_encode($validator->errors()));
            return $this->validationErrorResponse($validator->errors());
        }

        DB::beginTransaction();
        try {

            $reqApplication = RequisitionApplication::where('doc_no', $request->purchase_req_doc_no)->firstOrFail();
            $reqApplication->good_issue_doc_no = $request->doc_no;
            //$reqApplication->is_received = 1;
            $reqApplication->save();
            foreach ($request->details as $detail) {
                $grn_id = MasGrnItem::where('grn_no', $detail['grn_no'])->value('id');

                foreach ($detail['line_item'] as $line) {

                    $item_id = MasItem::where('item_no', $line['item_code'])->value('id');
                    $store_id = MasStore::where('code', $line['store_code'])->value('id');
                    $grn_item_detail_id = MasGrnItemDetail::where('item_id', $item_id)
                        ->where('store_id', $store_id)
                        ->where('grn_id', $grn_id)
                        ->value('id');
                        if (!$grn_item_detail_id) {
                            // DB::rollBack();
                            return $this->errorResponse("GRN item detail not found for item_code: {$line['item_code']} and store_code: {$line['store_code']}");
                        }


                        $requisition_detail = RequisitionDetail::where('requisition_id', $reqApplication->id)
                        ->where('grn_item_id', $grn_id)
                        ->where('grn_item_detail_id', $grn_item_detail_id)
                        ->first();

                    if (!$requisition_detail) {
                        // DB::rollBack();
                        return $this->errorResponse("Requisition application details not found for item_code: {$line['item_code']} and grn_no: {$detail['grn_no']}");
                    }

                    $requisition_detail->received_quantity = $line['received_quantity'];
                    //$requisition_detail->is_received = 1;
                    $requisition_detail->received_at = now();
                    $requisition_detail->received_by = $reqApplication->created_by;
                    $requisition_detail->save();


                    if (!empty($line['serials'])) {
                        $serialsData = [];
                        foreach ($line['serials'] as $serial) {

                            $serialsData[] = [
                                'requisition_detail_id' => $requisition_detail->id,
                                'asset_serial_no' => $serial['asset_serial_no'],
                                'asset_description' => $serial['asset_description'],
                                'amount' => $serial['amount'],
                                'quantity' => isset($serial['quantity']) ? $serial['quantity'] : 1,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        }
                        if(!empty($serialsData)){
                            ReceivedSerial::insert($serialsData);
                        }
                    }
                }
            }

            $employee = User::find($reqApplication->created_by); // Ensure employee_id exists in requisition
            if ($employee && $employee->email) {
                Mail::to($employee->email)->send(new GoodsIssuedMail($employee, $reqApplication));
            } else {
                \Log::warning("Email not sent: No email found for user ID {$reqApplication->created_by}");
            }

            DB::commit();
            return $this->successResponse($reqApplication, 'Goods issued and received successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Failed to save data for {$request->doc_no}: " . $e->getMessage());
            return $this->errorResponse("Failed to save data for {$request->doc_no}: " . $e->getMessage());
        }
    }

    public function saveSite(Request $request)
    {

        $validator = \Validator::make($request->all(), [
            'PrjCode' => 'required',
            'PrjName' => 'required',
            'U_Dzongkhag' => 'required',
        ]);

        if ($validator->fails()) {
            \Log::error("Validation error while saving sites: " . json_encode($validator->errors()));
            return $this->validationErrorResponse($validator->errors());
        }

        DB::beginTransaction();

        try {
            $dzongkhagId = MasDzongkhag::where('sap_dzongkhag', $request->U_Dzongkhag)->value('id');

            if (!$dzongkhagId) {
                throw new \Exception("Dzongkhag '{$request->U_Dzongkhag}' not found.");
            }

            $site = MasSite::updateOrCreate(
                ['code' => $request->PrjCode], // Search condition
                [                            // Fields to update or insert
                    'name' => $request->PrjName,
                    'dzongkhag_id' => $dzongkhagId,
                ]
            );

            DB::commit();

            return $this->successResponse('Site saved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Error saving site: " . $e->getMessage());
            return $this->errorResponse($e->getMessage());
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

    public function getStock($itemCode)
    {
        // Start a new session and get session ID
        $response = $this->startSession();

        // Decode response if valid JSON
        if (json_last_error() === JSON_ERROR_NONE) {
            $session = json_decode($response->getContent(), true);
            $sessionId = $session['sessionId'] ?? null;
        } else {
            return response()->json(['msg_error' => 'Invalid JSON response: ' . json_last_error_msg()], 500);
        }

        // If session ID is missing, return an error
        if (empty($sessionId)) {
            return response()->json(['msg_error' => 'Failed to retrieve session ID'], 500);
        }

        // Initialize cURL for SAP API request
        $curl = curl_init();

        $url = SAP_BASE_URL . ':' . SAP_PORT . '/b1s/v1/Items(\'' . $itemCode . '\')/ItemWarehouseInfoCollection';

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30, // Set a reasonable timeout
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                'Cookie: B1SESSION=' . $sessionId, // Pass session ID dynamically
                'Content-Type: application/json',
            ],
            CURLOPT_SSL_VERIFYPEER => false,  // Disable SSL verification
            CURLOPT_SSL_VERIFYHOST => false,  // Disable host verification

        ]);

        // Execute cURL and check for errors

        $curlResponse = curl_exec($curl);

        // If cURL fails, return an error response
        if ($curlResponse === false) {
            $curlError = curl_error($curl);
            curl_close($curl);
            return response()->json(['msg_error' => 'CURL Error: ' . $curlError], 500);
        }

        // Close the cURL session
        curl_close($curl);

        // Decode the response from SAP API
        $responseArray = json_decode($curlResponse, true);

        // Check if response is valid JSON
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['msg_error' => 'Invalid JSON response from SAP API: ' . json_last_error_msg()], 500);
        }
        $responseFormated = [];

        if (isset($responseArray['error']['message']['value'])) {
            return $this->errorResponse($responseArray['error']['message']['value']);
        }




        $storesCodes = MasStore::all()->pluck('code')->toArray();
        if($responseArray['ItemWarehouseInfoCollection']) {
        foreach($responseArray['ItemWarehouseInfoCollection'] as $response) {
            if(!in_array($response['WarehouseCode'], $storesCodes)) {
                continue;
            }

            if($response['InStock'] == 0) {
                continue;
            }
            $responseFormated[] = [
                'code' => $response['WarehouseCode'],
                'stock' => $response['InStock'],
            ];

            }
        }
        if (empty($responseFormated)) {
            return $this->errorResponse('No stock found for the selected item');
        }

        // Return the response (success case)
        return response()->json([
            'success' => true,
            'data' => $responseFormated,
        ]);
    }


    private function sendPostRequest($url, $postFields, $sessionId)
        {
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => SAP_BASE_URL . ':' . SAP_PORT . $url,
                CURLOPT_RETURNTRANSFER => true,
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
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $responseArray = json_decode($response, true);

            curl_close($curl);

            if ($httpCode !== 201) {
                return [
                    'status' => $httpCode,
                    'error' => $responseArray['error']['message']['value'] ?? 'Something went wrong from SAP API'
                ];
            }

            return ['status' => 201, 'data' => $responseArray];
        }

    public function postAssetTransferReturn($postFields){
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

        $data = json_decode($postFields, true);
        if($data['asset_post_type'] == 'transfer') {
            $items = explode(',', $data['items']);
            $project = $data['project_code'];
            $dateToday = date('Y-m-d');
            $oneDayEarly = date('Y-m-d', strtotime('-1 day'));

            foreach ($items as $itemCode) {
                $itemCode = trim($itemCode); // Clean up the item code
                $url = "/b1s/v1/Items('" . $itemCode . "')"; // API URL for each item
                $postField = []; // Reset per item

                if ($project) {
                    // Build ItemProjects for this item
                    $postField['ItemProjects'] = [
                        [
                            'LineNumber' => 0,
                            'ValidTo' => $oneDayEarly,
                        ],
                        [
                            'LineNumber' => 1,
                            'ValidFrom' => $dateToday,
                            'ValidTo' => null,
                            'Project' => $project
                        ]
                    ];
                } else {
                    // Build ItemDistributionRules for this item
                    $postField['ItemDistributionRules'] = [
                        [
                            'LineNumber' => 0,
                            'ValidTo' => $oneDayEarly,
                        ],
                        [
                            'LineNumber' => 1,
                            'ValidFrom' => $dateToday,
                            'ValidTo' => null,
                            'DistributionRule2' => '106'
                        ]
                    ];
                }

                $response = $this->sendPostRequest($url, json_encode($postField), $sessionId);
                if ($response['status'] !== 201) {
                    return response()->json(['msg_error' => $response['error'] ?? 'Something went wrong from SAP API'], $response['status']);
                }

                $responseArray = $response;
                return response()->json(['success' => true, 'data' => $responseArray], 201);

            }
        }elseif($data['asset_post_type'] == 'return') {
            $items = explode(',', $data['items']);
            foreach ($items as $itemCode) {
                $itemCode = trim($itemCode); // Clean up the item code
                $url = "/b1s/v1/Items/('" . $itemCode . "')"; // API URL for each item
                $postField = [];

                $postField['ItemProjects'] = [
                    [
                        'U_Status' => 'return'
                    ]
                ];

                // dd($postField, $url);
                // $response = $this->sendPostRequest($url, json_encode($postField), $sessionId);
                // if ($response['status'] !== 201) {
                //     return response()->json(['msg_error' => $response['error'] ?? 'Something went wrong from SAP API'], $response['status']);
                // }

                $responseArray = $response;
                return response()->json(['success' => true, 'data' => $responseArray], 201);
            }
        }

    }
    public function postCommission($postFields){
         // Start SAP session and retrieve session ID
         $response = $this->startSession();

          if (is_string($postFields)) {
                $postFields = json_decode($postFields, true);
            }

         if (json_last_error() === JSON_ERROR_NONE) {
             $session = json_decode($response->getContent(), true);

             $sessionId = $session['sessionId'] ?? null;
         } else {
             return response()->json(['msg_error' => 'Invalid JSON response: ' . json_last_error_msg()], 500);
         }

         if (empty($sessionId)) {
             return response()->json(['msg_error' => 'Failed to retrieve session ID'], 500);
         }

        foreach ($postFields as $data) {
                $items = $data['Items'];
                $assetDocLines = $data['AssetDocumentLineCollection'];

                if (empty($items) && empty($assetDocLines)) {
                    return response()->json(['msg_error' => 'No items found in the payload'], 400);
                }

                // 1. POST each Item to /Items
                foreach ($items as $item) {
                    $formattedItem = [
                        "ItemCode" => $item['ItemCode'] ?? null,
                        "ItemName" => $item['ItemName'] ?? null,
                        "ForeignName" => $item['ForeignName'] ?? null,
                        "ItemsGroupCode" => 102,
                        "ItemType" => "F",
                        "AssetClass" => $item['AssetClass'] ?? "Furnitures",
                        "AssetGroup" => $item['AssetGroup'] ?? null,
                        "InventoryNumber" => $item['InventoryNumber'] ?? null,
                        "AssetSerialNumber" => $item['AssetSerialNumber'] ?? null,
                        "Location" => $item['Location'] ?? null,
                        "ItemProjects" => [
                            [
                                "LineNumber" => 0,
                                "ValidFrom" => $item['ItemProjects'][0]['ValidFrom'] ?? null,
                                "ValidTo" => $item['ItemProjects'][0]['ValidTo'] ?? null,
                                "Project" => $item['ItemProjects'][0]['Project'] ?? null,
                            ]
                            ],
                        "ItemDistributionRules" => [
                            [
                                "LineNumber" => 0,
                                "ValidFrom" => $item['ItemDistributionRules'][0]['ValidFrom'] ?? null,
                                "ValidTo" => $item['ItemDistributionRules'][0]['ValidTo'] ?? null,
                                "DistributionRule4" => $item['ItemDistributionRules'][0]['DistributionRule4'] ?? null,
                            ]
                        ]
                    ];

                    $jsonFormattedItem = json_encode($formattedItem, JSON_PRETTY_PRINT);
                    $url1 = '/b1s/v1/Items';
                    $response = $this->sendPostRequest($url1, $jsonFormattedItem, $sessionId);

                    if ($response['status'] !== 201) {
                        return response()->json([
                            'msg_error' => $response['error'] ?? 'Item creation failed in SAP'
                        ], $response['status']);
                    }
                }

                // 2. POST once to /AssetCapitalization per group
                $formattedAssetCapitalization = [
                    "AssetDocumentLineCollection" => $assetDocLines,
                    "AssetValueDate" => $data['AssetValueDate'],
                    "DocumentDate" => $data['DocumentDate'],
                    "PostingDate" => $data['PostingDate']
                ];

                $jsonFormattedAssetCapitalization = json_encode($formattedAssetCapitalization, JSON_PRETTY_PRINT);
                $url2 = '/b1s/v1/AssetCapitalization';
                $response = $this->sendPostRequest($url2, $jsonFormattedAssetCapitalization, $sessionId);

                if ($response['status'] !== 201) {
                    return response()->json([
                        'msg_error' => $response['error'] ?? 'Asset capitalization failed in SAP'
                    ], $response['status']);
                }
            }


        $responseArray = $response;
         return response()->json(['success' => true, 'data' => $responseArray], 201);
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
            CURLOPT_SSL_VERIFYPEER => false, // REMOVE IN PRODUCTIONPconstant
            CURLOPT_SSL_VERIFYHOST => false, // REMOVE IN PRODUCTION
        ));

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
            \Log::info($errorMessage);
            return response()->json(['msg_error' => $errorMessage], $httpCode);
        }

        \Log::info('sap response: ' . json_encode($responseArray));
        return response()->json(['success' => true, 'data' => $responseArray], 201);
    }

    public function postProfitCenter($data){
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
            CURLOPT_URL => SAP_BASE_URL . ':' . SAP_PORT . '/b1s/v1/ProfitCenters',
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
            CURLOPT_SSL_VERIFYPEER => false, // REMOVE IN PRODUCTIONPconstant
            CURLOPT_SSL_VERIFYHOST => false, // REMOVE IN PRODUCTION
        ));

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
            \Log::info($errorMessage);
            return response()->json(['msg_error' => $errorMessage], $httpCode);
        }

        \Log::info('sap response: ' . json_encode($responseArray));
        return response()->json(['success' => true, 'data' => $responseArray], 201);
    }


    public function getAssetData(Request $request)
    {
        $assets = $request->input('assets') ?? [];
        if (!is_array($assets)) {
            $assets = [$assets];
        }

        $rules = [
            'assets.*.employee_id' => 'required_without:assets.*.site_code',
            'assets.*.site_code'   => 'required_without:assets.*.employee_id|exists:mas_sites,code',
            'assets.*.serial_no'   => 'required',
            'assets.*.item_code'   => 'nullable',
            'assets.*.description' => 'required',
            'assets.*.quantity'    => 'required|numeric',
            'assets.*.amount'      => 'required|numeric',
            'assets.*.uom' => 'required',
            'assets.*.capitalization_date' => 'required|date',
            'assets.*.end_date' => 'required|date',
            'assets.*.category' => 'required',
        ];

        $validator = \Validator::make(['assets' => $assets], $rules);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        try{
        $result = DB::transaction(function () use ($assets) {
        foreach ($assets as $item) {
                $employee   = User::where('username', $item['employee_id'] ?? null)->first();
                $site       = MasSite::where('code', $item['site_code'] ?? null)->first();
                $i_code = null;
                if (!empty($item['item_code'])) {
                    $i_code = MasItem::firstOrCreate(
                        ['item_no' => $item['item_code']],
                        [
                            'item_no' => $item['item_code'],
                            'item_description' => $item['description'],
                            'uom' => $item['uom'],
                            'item_group' => $item['category'],
                            'is_fixed_asset' => $item['is_fixed_asset'] ?? 1,
                            'status' => 1,
                        ]
                    );
                }

              $pushedAsset = SapAsset::where('serial_number', $item['serial_no'])->first();

                if ($pushedAsset) {
                    return $this->errorResponse('Asset with serial number ' . $item['serial_no'] . ' already exists.');
                }

                // Create if not found
                $pushedAsset = SapAsset::create([
                    'serial_number' => $item['serial_no'],
                    'item_id' => $i_code?->id,
                    'uom' => $i_code ? $i_code->uom : $item['uom'],
                    'grn_number' => $item['grn_number'] ?? null,
                    'item_description' => $i_code ? $i_code->item_description : $item['description'],
                    'created_by' => auth()->id(),
                    'quantity' => $item['quantity'],
                    'amount' => $item['amount'],
                    'capitalization_date' => $item['capitalization_date'],
                    'end_date' => $item['end_date'],
                    'category' => $item['category'],
                ]);

                // --- Push to MasAssets ---
                $masAsset = MasAssets::where('serial_number', $item['serial_no'])->first();

                 if ($masAsset) {
                    return $this->errorResponse('Asset with serial number ' . $item['serial_no'] . ' already exists.');
                }

                // Create if not found
                $masAsset = MasAssets::create([
                    'serial_number' => $item['serial_no'],
                    'current_employee_id' => $employee?->id,
                    'current_site_id' => $site?->id,
                    'initial_owner_id' => $employee?->id,
                    'created_by' => auth()->id(),
                    'sap_asset_id' => $pushedAsset->id,
                ]);

            }
        });
        return $this->successResponse('Asset saved successfully');
        }catch (\Exception $e) {
            // \Log::error("Asset save failed: " . $e->getMessage());
            return $this->errorResponse('Something went wrong while saving assets. Please try again.'. $e->getMessage());
        }

    }


}
