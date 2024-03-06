<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LDAPUser extends Model
{
    protected $table = 'ldap_users';
    protected $fillable = ['cpf', 'name', 'email'];
}
