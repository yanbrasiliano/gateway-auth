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
            LDAPAuthEnum::AUTHENTICATED => $this->createResponse('AUTENTICADO', 'Autenticação realizada com sucesso no servidor LDAP.', HTTPStatusCodeEnum::OK),
            LDAPAuthEnum::USER_NOT_FOUND => $this->createResponse('USUÁRIO_NÃO_ENCONTRADO', 'Usuário não encontrado no servidor LDAP. Por favor, tente novamente.', HTTPStatusCodeEnum::NOT_FOUND),
            LDAPAuthEnum::INVALID_CREDENTIALS => $this->createResponse('CREDENCIAIS_INVÁLIDAS', 'Credenciais fornecidas inválidas. Por favor, tente novamente.', HTTPStatusCodeEnum::UNAUTHORIZED),
            LDAPAuthEnum::LDAP_ERROR => $this->createResponse('ERRO_LDAP', 'Ocorreu um erro de servidor LDAP durante a tentativa de autenticação do usuário. Por favor, tente mais tarde.', HTTPStatusCodeEnum::INTERNAL_SERVER_ERROR),
            default => $this->createResponse('ERRO_DESCONHECIDO', 'Ocorreu um erro desconhecido durante a tentativa de autenticação do usuário. Por favor, tente mais tarde.', HTTPStatusCodeEnum::UNAUTHORIZED),
        };

        return $this->createJsonResponse($response);
    }
}
