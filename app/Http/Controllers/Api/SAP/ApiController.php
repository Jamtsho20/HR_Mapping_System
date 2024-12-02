<?php

namespace App\Http\Controllers\Api\SAP;

use App\Http\Controllers\Controller as BaseController;

class ApiController extends BaseController
{
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

            if ($response === false) {
                return response()->json(['success' => false, 'message' => 'CURL Error: ' . curl_error($curl)]);
            }

            curl_close($curl);

            $response = json_decode($response, true);

            if (is_null($response)) {
                return response()->json(['success' => false, 'message' => 'Invalid JSON response from SAP API: ' . $response]);
            }

            return response()->json(['success' => true, 'response' => $response]);
        } catch (\Exception $error) {
            return response()->json(['success' => false, 'message' => $error->getMessage()]);
        }
    }

}
