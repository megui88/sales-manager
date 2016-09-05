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
            $table->date('birth_date')->nuleable();
            $table->integer('group_id')->nuleable();
            $table->boolean('debit_automatic');
            $table->string('cuil_cuit')->nuleable();
            $table->string('cbu')->nuleable();
            $table->string('fantasy_name')->nuleable();
            $table->string('business_name')->nuleable();
            $table->integer('category_id');
            $table->string('web')->nuleable();
            $table->date('discharge_date')->nuleable();
            $table->date('leaving_date')->nuleable();
            $table->string('role')->nuleable();
            $table->string('code')->unique();
            $table->string('email')->unique();
            $table->string('state')->default(\App\Services\BusinessCore::MEMBER_AFFILIATE);
            $table->string('password');
            $table->boolean('enable');
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
