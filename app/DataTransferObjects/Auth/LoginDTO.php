<?php

namespace App\DataTransferObjects\Auth;

class LoginDTO
{
    public $cpf;
    public $password;

    public function __construct($cpf, $password)
    {
        $this->cpf = $cpf;
        $this->password = $password;
    }

    /**
     * Create a new instance of the DTO from an array of attributes.
     *
     * @param array $attributes
     * @return static
     */
    public static function fromArray(array $attributes)
    {
        return new static(
            $attributes['cpf'] ?? null,
            $attributes['password'] ?? null
        );
    }

    /**
     * Validate the DTO.
     *
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function validate()
    {
        if (empty($this->cpf) || empty($this->password)) {
            throw new \InvalidArgumentException('cpf and password are required.');
        }


        return true;
    }
}
