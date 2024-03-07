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
        $dto = new LoginDTO($request->cpf, $request->password);
        $authResult = $this->authService->authenticate($dto->cpf, $dto->password);

        return match ($authResult) {
            LDAPAuthEnum::AUTHENTICATED => $this->createSuccessResponse('Autenticação realizada com sucesso no servidor LDAP.'),
            LDAPAuthEnum::USER_NOT_FOUND => $this->createErrorResponse('USUÁRIO_NÃO_ENCONTRADO', 'Usuário não encomtrado na base. POr favor, tente novamente.', HTTPStatusCodeEnum::NOT_FOUND),
            LDAPAuthEnum::INVALID_CREDENTIALS => $this->createErrorResponse('CREDENCIAIS_INVÁLIDAS', 'Credenciais fornecidas inválidas. Por favor, tente novamente.', HTTPStatusCodeEnum::UNAUTHORIZED),
            LDAPAuthEnum::LDAP_ERROR => $this->createErrorResponse('ERRO_LDAP', 'Ocorreu um erro de servidor LDAP durante a tentativa de autenticação do usuário. Por favor, tente mais tarde.', HTTPStatusCodeEnum::INTERNAL_SERVER_ERROR),
            LDAPAuthEnum::UNKNOWN_ERROR => $this->createErrorResponse('ERRO_DESCONHECIDO', 'Ocorreu um erro desconhecido durante a tentativa de autenticação do usuário. Por favor, tente mais tarde.', HTTPStatusCodeEnum::INTERNAL_SERVER_ERROR),
        };
    }
}
