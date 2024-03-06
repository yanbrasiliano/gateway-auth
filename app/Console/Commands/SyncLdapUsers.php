<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LDAPUser;
use App\LDAP\User as LDAPUserModel;

class SyncLdapUsers extends Command
{
    protected $signature = 'ldap:sync';
    protected $description = 'Synchronize LDAP users with local database';

    public function handle()
    {
        $ldapUsers = LDAPUserModel::get();

        foreach ($ldapUsers as $ldapUser) {
            if ($ldapUser->hasAttribute('employeeid')) {
                LDAPUser::updateOrCreate(
                    ['cpf' => $ldapUser->getFirstAttribute('employeeid')],
                    [
                        'name' => $ldapUser->getFirstAttribute('cn'),
                        'email' => $ldapUser->getFirstAttribute('mail') 
                    ]
                );
            }
        }

        $this->info('LDAP users synchronized successfully.');
    }
}
