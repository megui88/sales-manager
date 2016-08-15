<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('name');
            $table->string('last_name');
            $table->string('document')->nuleable();
            $table->string('address')->nuleable();
            $table->string('location')->nuleable();
            $table->string('phone')->nuleable();
            $table->string('cellphone')->nuleable();
            $table->string('internal_phone')->nuleable();
            $table->decimal('credit_max')->nuleable();
            $table->dateTime('birth_date')->nuleable();
            $table->string('state')->nuleable();
            $table->integer('group_id')->nuleable();
            $table->boolean('debit_automatic');
            $table->string('cuil_cuit')->nuleable();
            $table->string('cbu')->nuleable();
            $table->string('fantasy_name')->nuleable();
            $table->string('business_name')->nuleable();
            $table->integer('category_id');
            $table->string('web')->nuleable();
            $table->boolean('stand');
            $table->dateTime('discharge_date')->nuleable();
            $table->dateTime('leaving_date')->nuleable();
            $table->string('role')->nuleable();
            $table->string('code')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
