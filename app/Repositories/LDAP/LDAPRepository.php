<?php

namespace App\Repositories\LDAP;

use LdapRecord\Container;
use App\Enums\LDAPAuthEnum;
use LdapRecord\Models\ActiveDirectory\User as LdapUser;
use App\Repositories\Contracts\LDAP\LDAPRepositoryInterface;

class LdapRepository implements LDAPRepositoryInterface
{
    protected $connection;

    public function __construct()
    {
        $this->connection = Container::getConnection('default');
    }


    public function authenticate($cpf, $password)
    {
        try {
            $user = LdapUser::findBy('employeeid', $cpf);

            if (!$user) {
                return LDAPAuthEnum::USER_NOT_FOUND;
            }

            if ($this->connection->auth()->attempt($user->getDn(), $password)) {
                return LDAPAuthEnum::AUTHENTICATED;
            }

            return LDAPAuthEnum::INVALID_CREDENTIALS;
        } catch (\LdapRecord\Auth\BindException $e) {
            return
            [
                'error' => LDAPAuthEnum::LDAP_ERROR,
                'message' => $e->getMessage(),
            ];
        }
    }
}
