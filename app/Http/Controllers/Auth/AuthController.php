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

/**
 * @OA\Info(
 *     title="AUTH API",
 *     version="1.0.0"
 * )
 */

class AuthController extends Controller
{
    use RestResponseTrait;

    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @OA\Post(
     *     path="/api/v1/login",
     *     summary="Autentica um usuário via LDAP",
     *     tags={"Auth"},
     *     description="Autentica um usuário usando CPF e senha.",
     *     operationId="login",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Dados de autenticação do usuário",
     *         @OA\JsonContent(
     *             required={"cpf", "password"},
     *             @OA\Property(property="cpf", type="string", format="text", example="12345678901"),
     *             @OA\Property(property="password", type="string", format="password", example="yourpassword")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Autenticação bem-sucedida",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Autenticação realizada com sucesso no servidor LDAP."),
     *             @OA\Property(property="status", type="string", example="AUTENTICADO")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Credenciais inválidas ou usuário não encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Credenciais inválidas ou usuário não encontrado.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Erro interno do servidor.")
     *         )
     *     )
     * )
     */

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
