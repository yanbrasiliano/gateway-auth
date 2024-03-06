<?php

namespace App\Http\Controllers;

use App\LDAP\User;
use LdapRecord\Container;
use App\Enums\HTTPStatusCodeEnum;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\GetUserLDAP;


class LDAPController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/ldap",
     *     summary="Lista usuários LDAP",
     *     tags={"LDAP"},
     *     @OA\Response(
     *         response=200,
     *         description="Operação bem-sucedida"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao conectar ao servidor LDAP"
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $ldapConnection = Container::getConnection('default');

        try {
            $ldapConnection->connect();
            $users = User::get();
            $filteredUsers = $users->filter(function ($user) {
                return $user->hasAttribute('employeeid');
            });

            $formattedUsers = $filteredUsers->transform(function ($user) {
                return [
                    'employeeid' => $user->getFirstAttribute('employeeid'),
                    'cn' => $user->getFirstAttribute('cn'),
                    'dn' => $user->getDn(),
                    'name' => $user->getFirstAttribute('name'),
                    'email' => $user->getFirstAttribute('mail'),
                ];
            });

            $response = [
                'data' => [
                    'total' => $filteredUsers->count(),
                    'users' => $formattedUsers
                ]
            ];

            return response()->json($response);
        } catch (\LdapRecord\Auth\BindException $e) {
            return response()->json(['error' => 'Could not connect to LDAP server'], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/ldap/getByCPF",
     *     operationId="getByCpf",
     *     tags={"LDAP"},
     *     summary="Recupera usuário LDAP pelo CPF",
     *     description="Retorna detalhes do usuário LDAP baseado no CPF fornecido",
     *     @OA\Parameter(
     *         name="cpf",
     *         in="query",
     *         required=true,
     *         description="CPF do usuário a ser recuperado",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Operação bem-sucedida",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="employeeid", type="string"),
     *                 @OA\Property(property="cn", type="string"),
     *                 @OA\Property(property="dn", type="string"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="email", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuário não encontrado"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno no servidor"
     *     )
     * )
     */

    public function getByCPF(GetUserLDAP $request): JsonResponse
    {

        $cpf = $request->input('cpf');
        $ldapConnection = Container::getConnection('default');
        try {
            $ldapConnection->connect();
            $user = User::where('employeeid', '=', $cpf)->firstOrFail();
            $formattedUser =
                [
                    'data' =>
                    [
                        'employeeid' => $user->getFirstAttribute('employeeid'),
                        'cn' => $user->getFirstAttribute('cn'),
                        'dn' => $user->getDn(),
                        'name' => $user->getFirstAttribute('name'),
                        'email' => $user->getFirstAttribute('mail'),
                    ]
                ];
            return response()->json($formattedUser);
        } catch (\LdapRecord\Models\ModelNotFoundException $e) {
            return response()->json(['error' => 'User not found'], HTTPStatusCodeEnum::NOT_FOUND->value);
        } catch (\LdapRecord\Auth\BindException $e) {
            return response()->json(['error' => 'Could not connect to LDAP server'], HTTPStatusCodeEnum::INTERNAL_SERVER_ERROR->value);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], HTTPStatusCodeEnum::INTERNAL_SERVER_ERROR->value);
        }
    }
}
