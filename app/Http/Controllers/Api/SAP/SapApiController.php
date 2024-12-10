<?php

namespace App\Http\Controllers\Api\SAP;

use App\Http\Controllers\Controller;
use App\Models\MasItem;
use App\Models\MasStore;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\Request;

class SapApiController extends Controller
{
    use JsonResponseTrait;
    protected $countryId = 1;
    protected $superUser = 1; 
    
    public function saveStore(Request $request) {
        $rules = [
            'name' => 'required',
            'code' => 'required',
            'store_location' => 'required',
            'store_email' => 'required',
            'phone_number' => 'required',
            'contact_person' => 'required',
            'contact_email' => 'required',
            'contact_number' => 'required',
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
                $store->store_email = $request->store_email;
                $store->phone_number = $request->phone_number;
                $store->contact_person = $request->contact_person;
                $store->contact_email = $request->contact_email;
                $store->contact_number = $request->contact_number;
                $store->country_id = $this->countryId;
                $store->region_id = $request->region_id;
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
                $store->store_email = $request->store_email;
                $store->phone_number = $request->phone_number;
                $store->contact_person = $request->contact_person;
                $store->contact_email = $request->contact_email;
                $store->contact_number = $request->contact_number;
                $store->country_id = $this->countryId;
                $store->region_id = $request->region_id;
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
                $item->item_description = $request->item_description;
                $item->uom = $request->uom;
                $item->quantity = $request->quantity;
                $item->status = $request->status ?? 1;
                $item->updated_by = $this->superUser; // Track who updated it
                $item->save();
                $message = 'Item updated successfully.';
            } else {
                // If item does not exist, create a new one
                $item = new MasItem();
                $item->store_id = $storeId;
                $item->item_category = $request->item_category;
                $item->item_number = $request->item_number;
                $item->item_description = $request->item_description;
                $item->uom = $request->uom;
                $item->quantity = $request->quantity;
                $item->status = $request->status ?? 1;
                $item->created_by = $this->superUser;
                $item->save();
                $message = 'Item created successfully.';
            }
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    
        return $this->successResponse($item, $message);
    }
    
}
