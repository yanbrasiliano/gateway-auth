<?php

namespace App\Console\Commands;

use App\Models\LDAPUser;
use Illuminate\Console\Command;
use App\LDAP\User as LDAPUserModel;
use Illuminate\Support\Facades\Log;

class SyncLdapUsers extends Command
{
    protected $signature = 'ldap:sync';
    protected $description = 'Synchronize LDAP users with local database';

    public function handle()
    {
        $ldapUsers = LDAPUserModel::get();
        foreach ($ldapUsers as $ldapUser) {
            if ($ldapUser->hasAttribute('employeeid')) {
                $cpf = preg_replace('/\D/', '', $ldapUser->getFirstAttribute('employeeid'));

                if (strlen($cpf) === 11) {
                    LDAPUser::updateOrCreate(
                        ['cpf' => $cpf],
                        [
                            'name' => $ldapUser->getFirstAttribute('cn'),
                            'email' => $ldapUser->getFirstAttribute('mail'),
                            'dn' => $ldapUser->getDn(),
                        ]
                    );
                } else {
                    Log::warning("Invalid CPF FOUND: {$cpf}");
                }
            }
        }

        $this->info('LDAP users synchronized successfully.');
    }
}
