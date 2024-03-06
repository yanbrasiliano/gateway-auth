<?php

namespace App\Enums;

enum LDAPAuthEnum: string
{
    case USER_NOT_FOUND = 'User not found';
    case AUTHENTICATED = 'Authenticated successfully';
    case INVALID_CREDENTIALS = 'Invalid credentials';
    case LDAP_ERROR = 'LDAP server error';
    case UNKNOWN_ERROR = 'Unknown error';
}
