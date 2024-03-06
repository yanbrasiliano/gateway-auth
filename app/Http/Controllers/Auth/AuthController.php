<?php

namespace App\Http\Controllers\Auth;

use App\Enums\LDAPAuthEnum;
use App\Services\AuthService;
use App\Enums\HTTPStatusCodeEnum;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\DataTransferObjects\Auth\LoginDTO;

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
        $authResult = $this->authService->authenticate($loginData->cpf, $loginData->password);

        switch ($authResult) {
            case LDAPAuthEnum::AUTHENTICATED:
                return response()->json([
                    'status' => HTTPStatusCodeEnum::OK,
                    'message' => LDAPAuthEnum::AUTHENTICATED,
                    'details' => 'Authenticated successfully with LDAP server.'
                ]);
            case LDAPAuthEnum::USER_NOT_FOUND:
                return response()->json([
                    'status' => HTTPStatusCodeEnum::NOT_FOUND,
                    'message' => LDAPAuthEnum::USER_NOT_FOUND,
                    'details' => 'User not found in LDAP server. Please, try again.'
                ]);
            case LDAPAuthEnum::INVALID_CREDENTIALS:
                return response()->json([
                    'status' => HTTPStatusCodeEnum::UNAUTHORIZED,
                    'message' => LDAPAuthEnum::INVALID_CREDENTIALS,
                    'details' => 'Invalid credentials provided. Please, try again.'
                ]);
            case LDAPAuthEnum::LDAP_ERROR:
                return response()->json([
                    'status' => HTTPStatusCodeEnum::INTERNAL_SERVER_ERROR,
                    'message' => LDAPAuthEnum::LDAP_ERROR,
                    'details' => 'LDAP server error occurred while trying to authenticate user. Please, try again later.'
                ]);
            default:
                return response()->json([
                    'status' => HTTPStatusCodeEnum::UNAUTHORIZED,
                    'details' => LDAPAuthEnum::UNKNOWN_ERROR,
                    'message' => 'Unknown error occurred while trying to authenticate user. Please, try again later.'
                ],);
        }
    }
}
