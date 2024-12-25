<?php

namespace App\Http\Controllers\Api\SAP;

use App\Http\Controllers\Controller as BaseController;
use App\Models\MasItem;
use App\Models\MasStore;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\Request;
class ApiController extends BaseController
{

    use JsonResponseTrait;
    protected $countryId = 1;
    protected $superUser = 1;

    public function saveStore(Request $request) {
        $rules = [
            'name' => 'required',
            'code' => 'required',
            'store_location' => 'required',
            // 'store_email' => 'required',
            // 'phone_number' => 'required',
            // 'contact_person' => 'required',
            // 'contact_email' => 'required',
            // 'contact_number' => 'required',
            // 'dzongkhag_code' => 'required',
            'region_id' => 'required'
        ];

        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $parentStoreId = null;
        if($request->has('parent_store_code') && $request->parent_store_code){
            $parentStoreId = MasStore::where('code', $request->parent_store_code)->value('id');
            if (!$parentStoreId) {
                return $this->errorResponse('Parent store code not found.');
            }
        }

        try {
            // Check if store exists based on store code
            $store = MasStore::where('code', $request->code)->first();

            if ($store) {
                // If store exists, update it
                $store->parent_store_id = $parentStoreId;
                $store->name = $request->name;
                $store->store_location = $request->store_location;
                $store->store_email = isset($request->store_email) ? $request->store_email : null;
                $store->phone_number = isset($request->phone_number) ? $request->phone_number : null;
                $store->contact_person = isset($request->contact_person) ? $request->contact_person : null;
                $store->contact_email = isset($request->contact_email) ? $request->contact_email : null;
                $store->contact_number = isset($request->contact_number) ? $request->contact_number : null;
                $store->country_id = $this->countryId;
                $store->region_id = isset($request->region_id) ? $request->region_id : null;
                $store->status = $request->status ?? 1;
                $store->updated_by = $this->superUser; // Track who updated it
                $store->save();
                $message = 'Store updated successfully.';
            } else {
                // If store does not exist, create a new one
                $store = new MasStore();
                $store->parent_store_id = $parentStoreId;
                $store->name = $request->name;
                $store->code = $request->code;
                $store->store_location = $request->store_location;
                $store->store_email = isset($request->store_email) ? $request->store_email : null;
                $store->phone_number = isset($request->phone_number) ? $request->phone_number : null;
                $store->contact_person = isset($request->contact_person) ? $request->contact_person : null;
                $store->contact_email = isset($request->contact_email) ? $request->contact_email : null;
                $store->contact_number = isset($request->contact_number) ? $request->contact_number : null;
                $store->country_id = $this->countryId;
                $store->region_id = isset($request->region_id) ? $request->region_id : null;
                $store->status = $request->status ?? 1;
                $store->created_by = $this->superUser;
                $store->save();
                $message = 'Store created successfully.';
            }
        } catch(\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }

        return $this->successResponse($store, $message);
    }

    public function saveItem(Request $request) {
        $rules = [
            'store_code' => 'required',
            'item_category' => 'required',
            'item_number' => 'required',
            'item_description' => 'required',
            'uom' => 'required',
            'quantity' => 'required',
            'status' => 'required',
        ];

        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        // Find store ID by store code
        $storeId = null;
        if ($request->has('store_code') && $request->store_code) {
            $storeId = MasStore::where('code', $request->store_code)->value('id');
            if (!$storeId) {
                return $this->errorResponse('Store code not found.');
            }
        }

        try {
            // Check if item exists based on item_number
            $item = MasItem::where('item_number', $request->item_number)->first();

            if ($item) {
                // If item exists, update it
                $item->store_id = $storeId;
                $item->item_category = $request->item_category;
                $item->asset_type = $request->asset_type;
                $item->asset_class = $request->asset_class;
                $item->item_description = $request->item_description;
                $item->uom = $request->uom;
                $item->quantity = $request->quantity;
                $item->fa_enabled = $request->fa_enabled ?? 1;
                $item->status = $request->status ?? 1;
                $item->updated_by = $this->superUser; // Track who updated it
                $item->save();
                $message = 'Item updated successfully.';
            } else {
                // If item does not exist, create a new one
                $item = new MasItem();
                $item->store_id = $storeId;
                $item->item_category = $request->item_category;
                $item->asset_type = $request->asset_type;
                $item->asset_class = $request->asset_class;
                $item->item_description = $request->item_description;
                $item->uom = $request->uom;
                $item->quantity = $request->quantity;
                $item->fa_enabled = $request->fa_enabled ?? 1;
                $item->status = $request->status ?? 1;
                $item->updated_by = $this->superUser;
                $item->save();
                $message = 'Item created successfully.';
            }
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }

        return $this->successResponse($item, $message);
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
    public function postJournalEntries($postFields)
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
            CURLOPT_URL => SAP_BASE_URL . ':' . SAP_PORT . '/b1s/v1/JournalEntries',
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
