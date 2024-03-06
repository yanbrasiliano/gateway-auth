<?php

namespace App\Http\Controllers\Auth;

use App\DataTransferObjects\Auth\LoginDTO;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $loginData = LoginDTO::fromArray($request->toArray());

        if ($this->authService->authenticate($loginData->cpf, $loginData->password)) {
            return response()->json(['message' => 'Authenticated successfully'], 200);
        }

        return response()->json(['message' => 'Authentication failed'], 401);
    }
}
