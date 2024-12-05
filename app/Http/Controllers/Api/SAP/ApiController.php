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
                // Handle curl error
                return response()->json(['success' => false, 'message' => 'CURL Error: ' . curl_error($curl)]);
            }

            // Close curl connection
            curl_close($curl);

            // Log or inspect the raw response
            \Log::info('SAP API Response: ' . $response);

            // Check if the response is a valid JSON string
            if (json_last_error() === JSON_ERROR_NONE) {
                $responseArray = json_decode($response, true); // Decode into an associative array
                // Check if the expected keys exist in the response
                // if (isset($responseArray['success']) && $responseArray['success'] === true) {
                // Extract session data from the response
                $odataMetadata = $responseArray['odata.metadata'];
                $sessionId = $responseArray['SessionId'];
                $version = $responseArray['Version'];
                $sessionTimeout = $responseArray['SessionTimeout'];

                // Prepare response data to return
                $responseData = [
                    'success' => true,
                    'odataMetadata' => $odataMetadata,
                    'sessionId' => $sessionId,
                    'version' => $version,
                    'sessionTimeout' => $sessionTimeout,
                ];

                return response()->json($responseData);
                // } else {
                //     return response()->json(['success' => false, 'message' => 'Unexpected response format or failure']);
                // }
            } else {
                // Output the error if JSON decoding fails
                return response()->json(['success' => false, 'message' => 'Invalid JSON response from SAP API: ' . json_last_error_msg()]);
            }
        } catch (\Exception $error) {
            // Catch any other exceptions and return an error message
            return response()->json(['success' => false, 'message' => $error->getMessage()]);
        }
    }

    public function postJournalEntries($postFields)
    {
        $response = $this->startSession();

        if (json_last_error() === JSON_ERROR_NONE) {
            $session = json_decode($response->getContent(), true);

            $sessionId = $session['sessionId'];
        } else {
            return response()->json(['msg_error' => json_last_error_msg()], 500);
        }

        if (empty($sessionId)) {
            return response()->json(['msg_error' => 'Failed to retrieve session ID'], 500);
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => SAP_BASE_URL . ':' . SAP_PORT . '/b1s/v1/JournalEntries',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $postFields,
            CURLOPT_HTTPHEADER => array(
                "Cookie: $sessionId; B1SESSION=$sessionId",
                'Content-Type: application/json',
            ),
            
            // Disable SSL verification. REMOVE IN PRODUCTION.
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ));

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            curl_close($curl);
            return response()->json(['msg_error' => 'Curl error: ' . $error_msg], 500);
        }

        curl_close($curl);

        return $response;
    }
}
