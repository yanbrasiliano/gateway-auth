<?php

namespace App\DataTransferObjects\Auth;

class LoginDTO
{
    public string $cpf;
    public string $password;

    public function __construct(string $cpf, string $password)
    {
        $this->cpf = $cpf;
        $this->password = $password;
    }
}
