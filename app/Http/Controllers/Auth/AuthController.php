<?php

namespace App\Http\Controllers\Auth;

use App\Enums\LDAPAuthEnum;
use App\Services\AuthService;
use App\Enums\HTTPStatusCodeEnum;
use App\Traits\RestResponseTrait;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\DataTransferObjects\Auth\LoginDTO;

class AuthController extends Controller
{
    use RestResponseTrait;

    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $auth = LoginDTO::fromArray($request->validated());
        $authResult = $this->authService->authenticate($auth->cpf, $auth->password);

        $response = match ($authResult) {
            LDAPAuthEnum::AUTHENTICATED => $this->createResponse(LDAPAuthEnum::AUTHENTICATED, 'Authenticated successfully with LDAP server.', HTTPStatusCodeEnum::OK),
            LDAPAuthEnum::USER_NOT_FOUND => $this->createResponse(LDAPAuthEnum::USER_NOT_FOUND, 'User not found in LDAP server. Please, try again.', HTTPStatusCodeEnum::NOT_FOUND),
            LDAPAuthEnum::INVALID_CREDENTIALS => $this->createResponse(LDAPAuthEnum::INVALID_CREDENTIALS, 'Invalid credentials provided. Please, try again.', HTTPStatusCodeEnum::UNAUTHORIZED),
            LDAPAuthEnum::LDAP_ERROR => $this->createResponse(LDAPAuthEnum::LDAP_ERROR, 'LDAP server error occurred. Please, try again later.', HTTPStatusCodeEnum::INTERNAL_SERVER_ERROR),
            default => $this->createResponse(LDAPAuthEnum::UNKNOWN_ERROR, 'Unknown error occurred. Please, try again later.', HTTPStatusCodeEnum::UNAUTHORIZED),
        };

        return $this->createJsonResponse($response);
    }
}
