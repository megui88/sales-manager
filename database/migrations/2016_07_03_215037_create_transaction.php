<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransaction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('transactional_id');
            $table->string('transactional_type');
            $table->integer('client_id');
            $table->integer('office_id');
            $table->integer('operator_id')->unsigned();
            $table->integer('supervisor_id')->nullable();
            $table->integer('collector_id')->unsigned();
            $table->integer('payer_id')->unsigned();
            $table->integer('comment_id')->unsigned();
            $table->string('state')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('transactions');
    }
}
