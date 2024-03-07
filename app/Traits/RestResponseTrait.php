<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use App\Enums\HTTPStatusCodeEnum;
use App\Enums\ResponseStatusEnum;

trait RestResponseTrait
{
    public function createSuccessResponse(string $message, ?array $data = null): JsonResponse
    {
        $response = [
            'status' => ResponseStatusEnum::SUCCESS->value,
            'message' => $message,
        ];

        $data && $response['data'] = $data;


        return Response::json($response, HTTPStatusCodeEnum::OK->value);
    }


    public function createErrorResponse(string $message, $errors, HTTPStatusCodeEnum $statusCode): JsonResponse
    {
        $response = [
            'status' => ResponseStatusEnum::ERROR->value,
            'message' => $message,
            'errors' => $errors
        ];

        return Response::json($response, $statusCode->value);
    }
}
