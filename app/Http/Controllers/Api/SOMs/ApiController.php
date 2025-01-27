<?php

namespace App\Http\Controllers\Api\SOMs;

use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    protected $basePath = 'https://soms-vm.tashicell.com/';
    protected $userName = 'E00000';
    protected $password = 'p@ssword';


    public function startSession()
    {
        try {
            $curl = curl_init();

            $postFields = json_encode([
                "username" => $this->userName,
                "password" => $this->password,
            ]);

            curl_setopt_array($curl, array(
                CURLOPT_URL => $this->basePath . 'api/v1/auth/authenticate',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>$postFields,
                CURLOPT_HTTPHEADER => array(
                  'Content-Type: application/json'
                ),
              ));

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
                \Log::info('SOMs API Response: ' . $response);
                \Log::info('HTTP Status Code: ' . $httpCode);
            }

            // Decode the JSON response
            $responseArray = json_decode($response, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                // Handle JSON decoding errors
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid JSON response from SOMs API: ' . json_last_error_msg(),
                    'http_code' => $httpCode,
                ]);
            }

            // Validate required keys in the response
            if (isset($responseArray['access_token'], $responseArray['refresh_token'])) {
                $accessToken = $responseArray['access_token'];
                $refreshToken = $responseArray['refresh_token'];

                return response()->json([
                    'success' => true,
                    'access_token' => $accessToken,
                    'refresh_token' => $refreshToken,
                    'http_code' => $httpCode,
                ]);
            } else {
                // Handle missing keys in the response
                return response()->json([
                    'success' => false,
                    'message' => 'Required keys are missing in the SOMs API response.',
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

    public function postEmployeeToSoms($data)
    {
        $response = $this->startSession();

        if (json_last_error() === JSON_ERROR_NONE) {
            $token = json_decode($response->getContent(), true);

            $accessToken = $token['access_token'] ?? null;
        } else {
            return response()->json(['msg_error' => 'Invalid JSON response: ' . json_last_error_msg()], 500);
        }

        if (empty($accessToken)) {
            return response()->json(['msg_error' => 'Failed to retrieve access token'], 500);
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->basePath . 'Api/HRMS/employeeMaster',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                "access_token: $accessToken",
                'Content-Type: application/json',
            ],
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
            $errorMessage = $responseArray['error']['message']['value'] ?? 'Something went wrong from SOMs API';
            \Log::info($errorMessage);
            return response()->json(['msg_error' => $errorMessage], $httpCode);
        }
        \Log::info('SOMs response: ' . $responseArray);
        return response()->json(['success' => true, 'data' => $responseArray], 201);
    }
}
