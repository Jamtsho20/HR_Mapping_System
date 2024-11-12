<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait JsonResponseTrait
{
    protected function successResponse($data = null, string $message = 'Success', int $status = 200): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    
    protected function errorResponse(string $message = 'Error', int $status = 400, $data = null): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    /**
     * Return a validation error JSON response.
     *
     * @param  mixed  $errors
     * @param  string  $message
     * @param  int  $status
     * @return \Illuminate\Http\JsonResponse
     */
    protected function validationErrorResponse($errors, string $message = 'Validation error', int $status = 422): JsonResponse
    {
        return response()->json([
            'status' => 'fail',
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }
}