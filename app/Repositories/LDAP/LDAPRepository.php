<?php

namespace App\Repositories\LDAP;

use App\Repositories\Contracts\LDAP\LDAPRepositoryInterface;
use LdapRecord\Container;
use LdapRecord\Connection;

class LdapRepository implements LDAPRepositoryInterface
{
    protected $connection;

    public function __construct()
    {
        // Assume that you have set up your LDAP connection settings in your .env file
        $this->connection = Container::getConnection('default');
    }

    public function authenticate($cpf, $password)
    {
        try {

            // Attempt to authenticate the user
            return $this->connection->auth()->attempt($cpf, $password);
        } catch (\LdapRecord\Auth\BindException $e) {
            return false;
        }
    }
}
