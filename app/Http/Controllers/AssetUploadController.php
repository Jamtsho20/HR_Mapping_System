<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ExcelAssetImport;
use Illuminate\Http\Request;

class AssetUploadController extends Controller{

    public function create(Request $request){
        return view('asset.asset-upload');
    }
    public function uploadExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        try {
            Excel::import(new ExcelAssetImport, $request->file('file'));

            return response()->json(['message' => 'Assets uploaded successfully']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong while uploading assets: ' . $e->getMessage()
            ], 400);
        }
    }
}
