<?php

namespace App\Http\Controllers;

use LdapRecord\Container;
use App\LDAP\User;

class LDAPController extends Controller
{
    public function index()
    {
        $ldapConnection = Container::getConnection('default');
        try {
            $ldapConnection->connect();
            $users = User::get();

            $formattedUsers = $users->toArray();

            return response()->json($formattedUsers);
        } catch (\LdapRecord\Auth\BindException $e) {
            return response()->json(['error' => 'Could not connect to LDAP server'], 500);
        }
    }
}
