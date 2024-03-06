<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait RestResponseTrait
{
    public function createResponse($message, $details, $status): array
    {
        return [
            'status' => $status,
            'message' => $message,
            'details' => $details
        ];
    }

    public function createJsonResponse(array $response): JsonResponse
    {
        return response()->json($response, $response['status']->value);
    }
}
