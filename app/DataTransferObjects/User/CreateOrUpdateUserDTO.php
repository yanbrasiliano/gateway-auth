<?php

declare(strict_types=1);

namespace App\DataTransferObjects\User;

use App\Http\Requests\StoreOrUpdateUserRequest;

class CreateOrUpdateUserDTO
{
    public string $name;
    public string $email;
    public ?string $password;

    public function __construct(string $name, string $email, ?string $password = null)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }

    public static function makeFromRequest(StoreOrUpdateUserRequest $data): self
    {
        return new self(
            $data['name'],
            $data['email'],
            $data['password'] ?? null
        );
    }
}
