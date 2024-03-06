<?php

namespace App\Services;

use App\Repositories\LDAP\LDAPRepository;

class AuthService
{
    protected $repository;

    public function __construct(LDAPRepository $repository)
    {
        $this->repository = $repository;
    }

    public function authenticate($cpf, $password)
    {
        return $this->repository->authenticate($cpf, $password);
    }
}
