<?php

namespace App\Repositories\Contracts\LDAP;

interface LdapRepositoryInterface
{
    public function authenticate($username, $password);
}
