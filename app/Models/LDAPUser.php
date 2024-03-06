<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class LDAPUser extends Model
{
    protected $table = 'ldap_users';
    protected $fillable = ['dn', 'cpf', 'email', 'name'];

    protected function cpf(): Attribute
    {
        return new Attribute(
            set: fn ($value) => preg_replace('/\D/', '', $value),
        );
    }

    protected function name(): Attribute
    {
        return new Attribute(
            get: fn ($value) => ucwords($value),
        );
    }
}
