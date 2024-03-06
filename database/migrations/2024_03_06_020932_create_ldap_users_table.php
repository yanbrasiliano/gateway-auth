<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLdapUsersTable extends Migration
{
    public function up()
    {
        Schema::create('ldap_users', function (Blueprint $table) {
            $table->id();
            $table->string('cpf')->unique();
            $table->string('email');
            $table->string('name');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ldap_users');
    }
}
