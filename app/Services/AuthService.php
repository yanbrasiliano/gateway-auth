<?php

namespace App\Services;

use Throwable;
use Illuminate\Support\Facades\Hash;
use App\Repositories\LDAP\LDAPRepository;
use App\Enums\HTTPStatusCodeEnum;

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
