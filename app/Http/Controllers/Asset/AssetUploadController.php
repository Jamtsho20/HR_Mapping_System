<?php
namespace App\Http\Controllers\Asset;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ExcelAssetImport;
use Illuminate\Http\Request;
use App\Models\MasAssets;
use App\Models\MasSite;
use App\Models\MasItem;

class AssetUploadController extends Controller{


    public function create(Request $request){
        return view('asset.asset-upload');
    }
  public function uploadExcel(Request $request)
{
    ini_set('memory_limit', '1G');  // Increase if needed
    ini_set('max_execution_time', 10000); // 10 minutes for reading large files

     try {
        $file = $request->file('file');

        if (!$file || !$file->isValid()) {
            return response()->json([
                'success' => false,
                'error' => 'Invalid file upload.'
            ]);
        }

        // Run import (not queued)
        \Excel::import(new ExcelAssetImport, $file);

        return response()->json([
            'success' => true,
            'message' => 'File imported successfully.'
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
        ], 422);
    }
}

public function createAssetNo(){
    return view('asset.create-asset-no');
}

public function uploadAssetFile(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt,xlsx|max:2048',
        ]);


        $file = $request->file('file');

        $extension = $file->getClientOriginalExtension();

        $rows = [];

        // Handle CSV or Excel
        if ($extension === 'csv' || $extension === 'txt') {
            $rows = array_map('str_getcsv', file($file->getRealPath()));
        } else {
            $rows = Excel::toArray([], $file)[0]; // requires maatwebsite/excel
        }

        // Assuming the first row is headers
        $header = array_map('strtolower', $rows[0]);
        unset($rows[0]);

        $updatedCount = 0;
        $failedRows = [];

       foreach ($rows as $index => $row) {
            $data = array_combine($header, $row);

            if (!$data) {
                $failedRows[] = [
                    'row' => $index + 1,
                    'reason' => 'Invalid row format',
                ];
                continue;
            }

            // Fetch IDs from related tables
            $itemId = MasItem::where('item_no', trim($data['item_id']))->value('id');
            $siteId = MasSite::where('code', trim($data['current_site_id']))->value('id');

            // Ensure required columns exist
            if (!isset($data['serial_number'], $data['sap_asset_id'], $data['asset_no'])) {
                $failedRows[] = [
                    'row' => $index + 1,
                    'reason' => 'Missing required columns',
                ];
                continue;
            }

            // Find the asset using DB-fetched IDs
            $asset = MasAssets::where('serial_number', trim($data['serial_number']))
                ->where('item_id', $itemId)
                ->where('current_site_id', $siteId)
                ->first();

            \Log::info($asset );
            if ($asset) {
                $asset->update(['asset_no' => trim($data['asset_no'])]);
                $updatedCount++;
            } else {
                $failedRows[] = [
                    'row' => $index + 1,
                    'serial_number' => $data['serial_number'] ?? null,
                    'item_id' => $data['item_id'] ?? null,
                    'site_id' => $data['current_site_id'] ?? null,
                    'sap_asset_id' => $data['sap_asset_id'] ?? null,
                    'reason' => 'No matching asset found',
                ];
            }
        }


        // Log failures to storage/logs/laravel.log
        if (!empty($failedRows)) {
            \Log::info('Asset Upload Failures', $failedRows);

        }

        return response()->json([
            'status' => 'success',
            'updated' => $updatedCount,
            'failed' => count($failedRows),
            'failed_details' => $failedRows
        ]);
    }
}
