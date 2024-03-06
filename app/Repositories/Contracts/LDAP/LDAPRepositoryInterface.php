<?php

namespace App\Repositories\Contracts\LDAP;

interface LDAPRepositoryInterface
{
    public function authenticate($username, $password);
}
