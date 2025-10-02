<?php

namespace App\Traits;

trait ApiResponse
{
    protected function apiResponse($data = null, string $message = '', int $status = 200, bool $success = true, $errors = null): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data,
            'errors' => $errors
        ], $status);
    }
}
