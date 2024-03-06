<?php

namespace App\Http\Controllers;

use LdapRecord\Container;
use App\LDAP\User;


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
    public function index()
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
                'total' => $filteredUsers->count(),
                'users' => $formattedUsers
            ];

            return response()->json($response);
        } catch (\LdapRecord\Auth\BindException $e) {
            return response()->json(['error' => 'Could not connect to LDAP server'], 500);
        }
    }
}
