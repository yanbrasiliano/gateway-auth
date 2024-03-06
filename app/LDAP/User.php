<?php

namespace App\LDAP;

use LdapRecord\Models\Model;

class User extends Model
{
    public static array $objectClasses = [
        'top',
        'person',
        'organizationalperson',
        'user',
    ];

    protected array $attributes = [
        'company' => ['mail'],
        'description' => ['User Account'],

    ];
}
