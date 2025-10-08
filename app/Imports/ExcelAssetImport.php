<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use App\Http\Controllers\Api\SAP\ApiController;

class ExcelAssetImport implements ToCollection, WithChunkReading
{
    public function collection(Collection $rows)
    {
        // Skip the header row
        $rows->shift();

        $assets = [];

        foreach ($rows as $row) {
            $assets[] = [
                'employee_id' => $row[0] ?? null,
                'site_code' => $row[1] ?? null,
                'serial_no' => $row[2],
                'asset_no' => $row[3],
                'item_code' => $row[4] ?? null,
                'description' => $row[5],
                'quantity' => $row[6],
                'amount' => $row[7],
                'uom' => $row[8],
                'capitalization_date' => is_numeric($row[9]) ? Date::excelToDateTimeObject($row[9])->format('Y-m-d') : $row[9],
                'end_date' => is_numeric($row[10]) ? Date::excelToDateTimeObject($row[10])->format('Y-m-d') : $row[10],
                'category' => $row[11],
                'grn_number' => $row[12] ?? null,
            ];
        }

        if (!empty($assets)) {
            // Create a fake request object
            $request = Request::create('/dummy', 'POST', ['assets' => $assets]);

            // Call your existing controller
            $controller = App::make(ApiController::class);
            try {
                $response = $controller->getAssetData($request);
                \Log::info('Assets batch processed', ['count' => count($assets)]);
            } catch (\Exception $e) {
                \Log::error('Excel import failed: ' . $e->getMessage());
            }
        }
    }

    public function chunkSize(): int
    {
       return 2000;
    }
}
