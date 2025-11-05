<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use App\Jobs\ProcessAssetBatch;


class ExcelAssetImport implements ToCollection, WithChunkReading
{

    public function collection(Collection $rows)
    {
        $rows->chunk(5000)->each(function ($chunk) {
            $assetsBatch = [];

            foreach ($chunk as $row) {
                $assetsBatch[] = [
                    'employee_id' => $row[0] ?? null,
                    'site_code' => $row[1] ?? null,
                    'serial_no' => $row[2] ?? null,
                    'asset_no' => $row[3] ?? null,
                    'item_code' => $row[4] ?? null,
                    'description' => $row[5] ?? null,
                    'quantity' => $row[6] ?? null,
                    'amount' => $row[7] ?? null,
                    'uom' => $row[8] ?? null,
                    'capitalization_date' => is_numeric($row[9])
                        ? Date::excelToDateTimeObject($row[9])->format('Y-m-d')
                        : $row[9],
                    'end_date' => is_numeric($row[10])
                        ? Date::excelToDateTimeObject($row[10])->format('Y-m-d')
                        : $row[10],
                    'category' => $row[11] ?? null,
                    'grn_number' => $row[12] ?? null,
                    'prj_line_num' => $row[13] ?? null,
                    'emp_line_num' => $row[14] ?? null,
                ];
            }

            // Directly process here without queue
            $controller = app(\App\Http\Controllers\Api\SAP\ApiController::class);
            $request = new \Illuminate\Http\Request(['assets' => $assetsBatch]);
            $response = $controller->getAssetData($request);
            \Log::info('getAssetData response: '.json_encode($response));
            if ($response instanceof \Illuminate\Http\JsonResponse) {
                $data = $response->getData(true);
                if (isset($data['status']) && $data['status'] === 'error') {
                    throw new \Exception($data['message']);
                }

                 if (isset($data['status']) && $data['status'] === 'fail') {
                // Throw exception to stop import and return to frontend
                throw new \Exception('Validation failed: '. json_encode($data['errors']));
            }
            }

        });
    }

    public function chunkSize(): int
    {
        return 5000; // Laravel Excel reads 2000 rows at a time from the file
    }
}
